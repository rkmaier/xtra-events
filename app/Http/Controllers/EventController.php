<?php

namespace App\Http\Controllers;

use App\Mail\EventRegistrationConfirmation;
use App\Mail\EventRegistrationNotification;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 6);
        $perPage = in_array($perPage, [6, 9]) ? $perPage : 6;

        $userId = Auth::id();

        $events = Event::withCount('attendees')
            ->when($userId, function ($query) use ($userId) {
                $query->withCount([
                    'attendees as is_registered' => function ($q) use ($userId) {
                        $q->where('user_id', $userId);
                    },
                ]);
            })
            ->orderBy('date', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return Inertia::render('Events/Index', [
            'events' => $events,
            'canLogin' => \Illuminate\Support\Facades\Route::has('login'),
            'canRegister' => \Illuminate\Support\Facades\Route::has('register'),
        ]);
    }

    public function manage(Request $request)
    {
        $userId = Auth::id();
        
        $events = Event::withTrashed()
            ->where('user_id', $userId)
            ->withCount('attendees')
            ->orderBy('date', 'desc')
            ->get();

        return Inertia::render('Events/Manage', [
            'events' => $events,
        ]);
    }

    public function create()
    {
        return Inertia::render('Events/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'image' => 'nullable|image|max:2048',
            'limit' => 'required|integer|min:1',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $validated['image'] = $path;
        }

        $validated['user_id'] = Auth::id();
        Event::create($validated);

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
        $this->authorize('update', $event);

        return Inertia::render('Events/Edit', [
            'event' => $event,
        ]);
    }

    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'name' => 'required|string|max:255|min:5',
            'description' => 'required|string|max:5000',
            'date' => 'required|date',
            'image' => 'nullable|image|max:2048',
            'limit' => 'required|integer|min:1',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $validated['image'] = $path;
        } else {
            // If no new image uploaded, don't overwrite existing image path
            unset($validated['image']);
        }

        $event->update($validated);

        return redirect()->route('events.index')
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);

        $event->delete();

        return redirect()->route('events.manage')
            ->with('success', 'Event deleted successfully.');
    }

    public function restore($id)
    {
        $event = Event::withTrashed()->findOrFail($id);
        
        $this->authorize('restore', $event);
        
        $event->restore();

        return redirect()->route('events.manage')
            ->with('success', 'Event restored successfully.');
    }

    /**
     * Register the authenticated user for the given event.
     */
    public function register(Event $event)
    {
        $user = Auth::user();

        if (! $user) {
            abort(403);
        }

  
        if ($event->attendees()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'You are already registered for this event.');
        }

        // Use database transaction with row lock to prevent race conditions
        try {
            DB::transaction(function () use ($event, $user) {
       
                $lockedEvent = Event::lockForUpdate()->findOrFail($event->id);

            
                $currentAttendees = $lockedEvent->attendees()->count();

                if ($lockedEvent->limit > 0 && $currentAttendees >= $lockedEvent->limit) {
                    throw new \Exception('This event is already full.');
                }

            
                $lockedEvent->attendees()->syncWithoutDetaching([
                    $user->id => [
                        'registered_at' => now(),
                    ],
                ]);
            });

            // Send confirmation email to the user who registered
            Mail::to($user->email)->send(
                new EventRegistrationConfirmation($event->fresh(), $user)
            );

            // Send notification email to the event creator
            if ($event->user_id && $event->user) {
                Mail::to($event->user->email)->send(
                    new EventRegistrationNotification($event->fresh(), $user)
                );
            }

            return back()->with('success', 'You have been registered for this event.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
