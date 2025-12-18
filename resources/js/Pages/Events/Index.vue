<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    events: Object,
    auth: {
        type: Object,
        default: () => ({}),
    },
    canLogin: {
        type: Boolean,
        default: false,
    },
    canRegister: {
        type: Boolean,
        default: false,
    },
});

const page = usePage();
const urlParams = new URLSearchParams(window.location.search);
const initialPerPage = parseInt(urlParams.get('per_page')) || 6;
const perPage = ref([6, 9].includes(initialPerPage) ? initialPerPage : 6);
const successMessage = ref(page.props.flash?.success || null);

// Watch for flash message changes
watch(() => page.props.flash?.success, (newValue) => {
    if (newValue) {
        successMessage.value = newValue;
        // Clear after 5 seconds
        setTimeout(() => {
            successMessage.value = null;
        }, 5000);
    }
});

// Clear success message after 5 seconds if it exists on mount
if (successMessage.value) {
    setTimeout(() => {
        successMessage.value = null;
    }, 5000);
}

const formatDate = (dateString) => {
    const date = new Date(dateString);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    return `${year}.${month}.${day}. ${hours}:${minutes}`;
};

const changePerPage = (newPerPage) => {
    perPage.value = newPerPage;
    router.get(route('events.index'), { per_page: newPerPage, page: 1 }, {
        preserveState: false,
        preserveScroll: false,
    });
};

const placesLeft = (event) => {
    const used = event.attendees_count || 0;
    return event.limit > 0 ? Math.max(event.limit - used, 0) : null;
};

const isRegistered = (event) => {

    return Boolean(event.is_registered);
};

const registerForEvent = (event) => {
    if (!props.auth.user) {
        router.visit(route('login'));
        return;
    }

    router.post(route('events.register', event.id), {}, {
        preserveScroll: true,
    });
};

const goToPage = (url) => {
    if (url) {
        router.visit(url, {
            preserveState: true,
            preserveScroll: true,
        });
    }
};

const logout = () => {
    router.post(route('logout'));
};

const eventImageUrl = (event) => {
    if (!event.image) {
        return 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=800&h=600&fit=crop';
    }
    if (event.image.startsWith('http')) {
        return event.image;
    }
    return `/storage/${event.image}`;
};
</script>

<template>
    <AppLayout title="Events">
        <Head title="Events" />
        <!-- Navigation Header -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between" style="height: 85px;">
                    <div class="flex items-center">
                     <h2 class="text-xl font-bold text-gray-900">
                         Upcoming Events
                    </h2>
                    </div>
                    <div class="flex items-center">
                        <Link
                        :href="route('events.manage')"
                        class="inline-flex float-right items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Manage Events
                    </Link>
                    </div>
                </div>
            </div>
        </nav>


        <!-- Main Content -->
        <main>
            <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Success Message -->
                <div
                    v-if="successMessage"
                    class="mb-6 rounded-md bg-green-50 p-4"
                >
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg
                                class="h-5 w-5 text-green-400"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ successMessage }}
                            </p>
                        </div>
                        <div class="ml-auto pl-3">
                            <div class="-mx-1.5 -my-1.5">
                                <button
                                    type="button"
                                    @click="successMessage = null"
                                    class="inline-flex rounded-md bg-green-50 p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-green-50"
                                >
                                    <span class="sr-only">Dismiss</span>
                                    <svg
                                        class="h-5 w-5"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Per Page Selector -->
                <div class="mb-6 flex justify-end items-center gap-4">
                    <label for="per-page" class="text-sm font-medium text-gray-700">
                        Events per page:
                    </label>
                    <div class="relative">
                        <select
                            id="per-page"
                            v-model="perPage"
                            @change="changePerPage(perPage)"
                            class="select-dropdown appearance-none bg-white rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm pr-8 pl-3 py-1.5"
                        >
                            <option :value="6">6</option>
                            <option :value="9">9</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                            <svg
                                class="h-4 w-4 text-gray-400"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.27a.75.75 0 01.02-1.06z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Events Grid -->
                <div class="mb-8">
                    <div
                        v-if="events.data && events.data.length > 0"
                        class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3"
                    >
                        <div
                            v-for="event in events.data"
                            :key="event.id"
                            class="bg-white overflow-hidden shadow-md rounded-lg flex flex-col"
                        >
                            <div class="h-40 w-full overflow-hidden">
                                <img
                                    :src="eventImageUrl(event)"
                                    :alt="event.name"
                                    class="h-full w-full object-cover transition-transform duration-300 hover:scale-105"
                                />
                            </div>
                            <div class="p-5 flex flex-col flex-1">
                                <div class="flex items-start justify-between gap-3">
                                    <h3 class="text-lg font-semibold text-gray-900 line-clamp-1">
                                        {{ event.name }}
                                    </h3>
                                    <span
                                        v-if="event.limit > 0"
                                        :class="{
                                            'bg-green-100 text-green-800': placesLeft(event) > 10,
                                            'bg-yellow-100 text-yellow-800':
                                                placesLeft(event) > 0 && placesLeft(event) <= 10,
                                            'bg-red-100 text-red-800': placesLeft(event) === 0,
                                        }"
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                                    >
                                        <span v-if="placesLeft(event) === 0">Full</span>
                                        <span v-else>{{ placesLeft(event) }} left</span>
                                    </span>
                                    <span
                                        v-else
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700"
                                    >
                                        No limit
                                    </span>
                                </div>

                                <p class="mt-2 text-sm text-gray-600 line-clamp-3">
                                    {{ event.description }}
                                </p>

                                <div class="mt-4 text-sm text-gray-500">
                                    <span class="font-medium text-gray-700">Date:</span>
                                    {{ formatDate(event.date) }}
                                </div>

                                <div class="mt-4 flex items-center justify-between">
                                    <div class="text-xs text-gray-500">
                                        <span class="font-medium">Registered:</span>
                                        {{ event.attendees_count || 0 }}
                                        <span v-if="event.limit > 0">
                                            / {{ event.limit }}
                                        </span>
                                    </div>

                                    <button
                                        v-if="event.limit === 0 || placesLeft(event) > 0 || isRegistered(event)"
                                        @click="!isRegistered(event) && registerForEvent(event)"
                                        :disabled="isRegistered(event)"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2"
                                        :class="[
                                            isRegistered(event)
                                                ? 'bg-gray-300 text-gray-600 cursor-not-allowed'
                                                : 'text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500',
                                        ]"
                                    >
                                        <span v-if="isRegistered(event)">Already registered</span>
                                        <span v-else>Register</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div
                        v-else
                        class="text-center py-12 bg-white shadow-sm rounded-lg"
                    >
                        <svg
                            class="mx-auto h-12 w-12 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                            />
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">No events</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Get started by creating a new event.
                        </p>
                    </div>
                </div>

                <!-- Pagination -->
                <div
                    v-if="events.links && events.links.length > 3"
                    class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6 rounded-lg"
                >
                    <div class="flex flex-1 justify-between sm:hidden">
                        <button
                            @click="goToPage(events.prev_page_url)"
                            :disabled="!events.prev_page_url"
                            :class="{
                                'opacity-50 cursor-not-allowed': !events.prev_page_url,
                                'hover:bg-gray-50': events.prev_page_url,
                            }"
                            class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700"
                        >
                            Previous
                        </button>
                        <button
                            @click="goToPage(events.next_page_url)"
                            :disabled="!events.next_page_url"
                            :class="{
                                'opacity-50 cursor-not-allowed': !events.next_page_url,
                                'hover:bg-gray-50': events.next_page_url,
                            }"
                            class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700"
                        >
                            Next
                        </button>
                    </div>
                    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing
                                <span class="font-medium">{{ events.from }}</span>
                                to
                                <span class="font-medium">{{ events.to }}</span>
                                of
                                <span class="font-medium">{{ events.total }}</span>
                                results
                            </p>
                        </div>
                        <div>
                            <nav
                                class="isolate inline-flex -space-x-px rounded-md shadow-sm"
                                aria-label="Pagination"
                            >
                                <button
                                    @click="goToPage(events.prev_page_url)"
                                    :disabled="!events.prev_page_url"
                                    :class="{
                                        'opacity-50 cursor-not-allowed': !events.prev_page_url,
                                        'hover:bg-gray-50': events.prev_page_url,
                                    }"
                                    class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 focus:z-20 focus:outline-offset-0"
                                >
                                    <span class="sr-only">Previous</span>
                                    <svg
                                        class="h-5 w-5"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                        aria-hidden="true"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </button>
                                <template v-for="(link, index) in events.links" :key="index">
                                    <button
                                        v-if="link.url"
                                        @click="goToPage(link.url)"
                                        v-html="link.label"
                                        :class="{
                                            'z-10 bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600': link.active,
                                            'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0': !link.active,
                                        }"
                                        class="relative inline-flex items-center px-4 py-2 text-sm font-semibold focus:z-20"
                                    />
                                    <span
                                        v-else
                                        v-html="link.label"
                                        class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 focus:outline-offset-0"
                                    />
                                </template>
                                <button
                                    @click="goToPage(events.next_page_url)"
                                    :disabled="!events.next_page_url"
                                    :class="{
                                        'opacity-50 cursor-not-allowed': !events.next_page_url,
                                        'hover:bg-gray-50': events.next_page_url,
                                    }"
                                    class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 focus:z-20 focus:outline-offset-0"
                                >
                                    <span class="sr-only">Next</span>
                                    <svg
                                        class="h-5 w-5"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                        aria-hidden="true"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </button>
                            </nav>
                        </div>
                    </div>
                </div>

            </div>
            </div>
        </main>
    </AppLayout>
</template>

<style scoped>
#per-page,
select#per-page,
.select-dropdown {
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    appearance: none !important;
    background-image: none !important;
}

#per-page::-ms-expand,
select#per-page::-ms-expand,
.select-dropdown::-ms-expand {
    display: none !important;
}
</style>

