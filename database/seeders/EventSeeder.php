<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'name' => 'Tech Conference 2025',
                'user_id' => Auth::user()->id ?? 1,
                'description' => 'Join us for the biggest tech conference of the year! Featuring keynote speakers, workshops, and networking opportunities with industry leaders. Topics include AI, cloud computing, cybersecurity, and more.',
                'date' => Carbon::now()->addMonths(2)->setTime(9, 0),
                'image' => 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?w=800&h=600&fit=crop',
                'limit' => rand(50, 500),
            ],
            [
                'name' => 'Summer Music Festival',
                'user_id' => Auth::user()->id ?? 1,
                'description' => 'A three-day outdoor music festival featuring top artists from around the world. Enjoy live performances, food vendors, and a vibrant atmosphere. Tickets are selling fast!',
                'date' => Carbon::now()->addMonths(3)->setTime(14, 0),
                'image' => 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=800&h=600&fit=crop',
                'limit' => rand(50, 500),
            ],
            [
                'name' => 'Web Development Workshop',
                'user_id' => Auth::user()->id ?? 1,
                'description' => 'Hands-on workshop covering modern web development techniques. Learn React, Vue.js, and Laravel from experienced developers. Perfect for both beginners and intermediate developers.',
                'date' => Carbon::now()->addWeeks(3)->setTime(10, 0),
                'image' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=800&h=600&fit=crop',
                'limit' => rand(50, 500),
            ],
            [
                'name' => 'Art Gallery Opening',
                'user_id' => Auth::user()->id ?? 1,
                'description' => 'Exclusive opening night of our new contemporary art exhibition. Featuring works from emerging and established artists. Wine and refreshments will be served.',
                'date' => Carbon::now()->addWeeks(1)->setTime(18, 0),
                'image' => 'https://images.unsplash.com/photo-1541961017774-22349e4a1262?w=800&h=600&fit=crop',
                'limit' => rand(50, 500),
            ],
            [
                'name' => 'Marathon Run 2025',
                'user_id' => Auth::user()->id ?? 1,
                'description' => 'Join thousands of runners for the annual city marathon. Choose from full marathon, half marathon, or 5K routes. All proceeds go to local charities.',
                'date' => Carbon::now()->addMonths(1)->setTime(7, 0),
                'image' => 'https://images.unsplash.com/photo-1571008887538-b36bb32f4571?w=800&h=600&fit=crop',
                'limit' => rand(50, 500),
            ],
            [
                'name' => 'Food & Wine Tasting',
                'user_id' => Auth::user()->id ?? 1,
                'description' => 'Experience a curated selection of fine wines paired with gourmet dishes from renowned chefs. Limited seating available. Reserve your spot today!',
                'date' => Carbon::now()->addDays(10)->setTime(19, 0),
                'image' => 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?w=800&h=600&fit=crop',
                'limit' => rand(50, 500),
            ],
            [
                'name' => 'Startup Pitch Night',
                'user_id' => Auth::user()->id ?? 1,
                'description' => 'Watch innovative startups pitch their ideas to a panel of investors. Network with entrepreneurs, investors, and fellow startup enthusiasts. Free admission!',
                'date' => Carbon::now()->addWeeks(2)->setTime(18, 30),
                'image' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=800&h=600&fit=crop',
                'limit' => rand(50, 500),
            ],
            [
                'name' => 'Yoga & Wellness Retreat',
                'user_id' => Auth::user()->id ?? 1,
                'description' => 'A weekend retreat focused on mindfulness, yoga, and wellness. Includes meditation sessions, healthy meals, and workshops on stress management.',
                'date' => Carbon::now()->addMonths(2)->setTime(8, 0),
                'image' => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=800&h=600&fit=crop',
                'limit' => rand(50, 500),
            ],
            [
                'name' => 'Photography Exhibition',
                'user_id' => Auth::user()->id ?? 1,
                'description' => 'Stunning collection of landscape and portrait photography from award-winning photographers. Exhibition runs for two weeks.',
                'date' => Carbon::now()->addDays(5)->setTime(11, 0),
                'image' => 'https://images.unsplash.com/photo-1492691527719-9d1e07e534b4?w=800&h=600&fit=crop',
                'limit' => rand(50, 500),
            ],
            [
                'name' => 'Charity Gala Dinner',
                'user_id' => Auth::user()->id ?? 1,
                'description' => 'Elegant black-tie event raising funds for children\'s education. Featuring live entertainment, silent auction, and a three-course dinner.',
                'date' => Carbon::now()->addWeeks(4)->setTime(19, 30),
                'image' => 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?w=800&h=600&fit=crop',
                'limit' => rand(50, 500),
            ],
            [
                'name' => 'Hackathon 2025',
                'user_id' => Auth::user()->id ?? 1,
                'description' => '48-hour coding competition for developers. Build innovative solutions, compete for prizes, and network with fellow developers. Food and drinks provided.',
                'date' => Carbon::now()->addMonths(1)->setTime(9, 0),
                'image' => 'https://images.unsplash.com/photo-1531482615713-2afd69097998?w=800&h=600&fit=crop',
                'limit' => rand(50, 500),
            ],
            [
                'name' => 'Comedy Night',
                'user_id' => Auth::user()->id ?? 1,
                'description' => 'An evening of laughter with stand-up comedians from across the country. Perfect for a fun night out with friends. 18+ event.',
                'date' => Carbon::now()->addDays(7)->setTime(20, 0),
                'image' => 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?w=800&h=600&fit=crop',
                'limit' => rand(50, 500),
            ],
            [
                'name' => 'Book Launch Party',
                'user_id' => Auth::user()->id ?? 1,
                'description' => 'Celebrate the release of the latest bestseller with the author. Book signing, Q&A session, and refreshments included.',
                'date' => Carbon::now()->addWeeks(2)->setTime(17, 0),
                'image' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=800&h=600&fit=crop',
                'limit' => rand(50, 500),
            ],
            [
                'name' => 'Cycling Tour',
                'user_id' => Auth::user()->id ?? 1,
                'description' => 'Guided cycling tour through scenic countryside routes. Suitable for all fitness levels. Bikes and safety equipment provided.',
                'date' => Carbon::now()->addWeeks(3)->setTime(8, 0),
                'image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&h=600&fit=crop',
                'limit' => rand(50, 500),
            ],
            [
                'name' => 'Film Screening',
                'user_id' => Auth::user()->id ?? 1,
                'description' => 'Exclusive screening of an award-winning independent film followed by a discussion with the director and cast members.',
                'date' => Carbon::now()->addDays(12)->setTime(19, 0),
                'image' => 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=800&h=600&fit=crop',
                'limit' => rand(50, 500),
            ],
            [
                'name' => 'Cooking Class',
                'user_id' => Auth::user()->id ?? 1,
                'description' => 'Learn to cook authentic Italian cuisine from a professional chef. Hands-on experience with fresh ingredients. Take home recipes included.',
                'date' => Carbon::now()->addDays(8)->setTime(18, 0),
                'image' => 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?w=800&h=600&fit=crop',
                'limit' => rand(50, 500),
            ],
            [
                'name' => 'Science Fair',
                'user_id' => Auth::user()->id ?? 1,
                'description' => 'Annual science fair showcasing innovative projects from students and researchers. Interactive exhibits and demonstrations for all ages.',
                'date' => Carbon::now()->addWeeks(5)->setTime(10, 0),
                'image' => 'https://images.unsplash.com/photo-1532619675605-1ede6c9ed2d9?w=800&h=600&fit=crop',
                'limit' => rand(50, 500),
            ],
            [
                'name' => 'Jazz Concert',
                'user_id' => Auth::user()->id ?? 1,
                'description' => 'Intimate jazz performance featuring renowned musicians. Enjoy smooth melodies in an elegant setting with full bar service.',
                'date' => Carbon::now()->addDays(15)->setTime(20, 30),
                'image' => 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=800&h=600&fit=crop',
                'limit' => rand(50, 500),
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}
