<script setup>
import { ref, watch, onMounted, onUpdated, onBeforeUnmount, nextTick } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.css';

DataTable.use(DataTablesCore);

const props = defineProps({
    events: Array,
});

// Key to force DataTable re-render when events change
const dataTableKey = ref(0);

// Watch for events prop changes and reload DataTable
watch(() => props.events, () => {
    nextTick(() => {
        dataTableKey.value++;
        // Reapply styles after DataTable reloads
        setTimeout(() => {
            applyLengthSelectorStyle();
        }, 100);
    });
}, { deep: true });

const page = usePage();
const successMessage = ref(page.props.flash?.success || null);
const eventToDelete = ref(null);
const showDeleteModal = ref(false);

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

const formatDate = (dateString) => {
    const date = new Date(dateString);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    return `${year}.${month}.${day}. ${hours}:${minutes}`;
};

const confirmDelete = (event) => {
    eventToDelete.value = event;
    showDeleteModal.value = true;
};

const deleteEvent = () => {
    if (eventToDelete.value) {
        router.delete(route('events.destroy', eventToDelete.value.id), {
            preserveScroll: true,
            preserveState: false,
            onSuccess: () => {
                showDeleteModal.value = false;
                eventToDelete.value = null;
                // Force DataTable to re-render by incrementing key
                dataTableKey.value++;
            },
        });
    }
};

const restoreEvent = (event) => {
    router.post(route('events.restore', event.id), {}, {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            // Force DataTable to re-render by incrementing key
            dataTableKey.value++;
        },
    });
};

const isDeleted = (event) => {
    return event.deleted_at !== null;
};

// Clear success message after 5 seconds if it exists on mount
if (successMessage.value) {
    setTimeout(() => {
        successMessage.value = null;
    }, 5000);
}

const dataTableOptions = {
    paging: true,
    info: true,
    lengthChange: true,
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
    pageLength: 10,
    order: [[0, 'desc']],
    language: {
        lengthMenu: "Show _MENU_ entries",
        info: "Showing _START_ to _END_ of _TOTAL_ entries",
        infoEmpty: "Showing 0 to 0 of 0 entries",
        infoFiltered: "(filtered from _MAX_ total entries)",
    },
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


const applyLengthSelectorStyle = () => {
    // Try multiple selectors to find the DataTables length selector
    const selectors = [
        '#dt-length-0',
        'select[name="dt-length-0"]',
        '.dt-length select',
        'select.dt-length',
    ];
    
    let lengthSelector = null;
    for (const selector of selectors) {
        lengthSelector = document.querySelector(selector);
        if (lengthSelector) break;
    }
    
    // Also try by ID pattern
    if (!lengthSelector) {
        const allSelects = document.querySelectorAll('select');
        for (const select of allSelects) {
            if (select.id && select.id.startsWith('dt-length-')) {
                lengthSelector = select;
                break;
            }
        }
    }
    
    if (lengthSelector) {
        lengthSelector.style.setProperty('width', '60px', 'important');
        lengthSelector.style.setProperty('-webkit-appearance', 'none', 'important');
        lengthSelector.style.setProperty('-moz-appearance', 'none', 'important');
        lengthSelector.style.setProperty('appearance', 'none', 'important');
        lengthSelector.style.setProperty('background-image', 'none', 'important');
        lengthSelector.classList.add('dt-length-select-styled');
        return true;
    }
    return false;
};

let observer = null;

const setupObserver = () => {
    if (observer) {
        observer.disconnect();
    }
    
    observer = new MutationObserver((mutations) => {
        let shouldCheck = false;
        mutations.forEach((mutation) => {
            if (mutation.addedNodes.length > 0) {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === 1) { // Element node
                        if (node.tagName === 'SELECT' || node.querySelector?.('select')) {
                            shouldCheck = true;
                        }
                    }
                });
            }
        });
        
        if (shouldCheck) {
            setTimeout(() => {
                applyLengthSelectorStyle();
            }, 10);
        }
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
};

onMounted(() => {
    setupObserver();
    
    nextTick(() => {
        // Apply styles immediately
        applyLengthSelectorStyle();
        
        // Retry with increasing delays to catch DataTables initialization
        const intervals = [10, 50, 100, 200, 500, 1000];
        intervals.forEach((delay) => {
            setTimeout(() => {
                applyLengthSelectorStyle();
            }, delay);
        });
    });
});

onUpdated(() => {
    // Reapply styles after component updates (e.g., after Inertia navigation)
    nextTick(() => {
        setupObserver();
        applyLengthSelectorStyle();
        
        const intervals = [10, 50, 100, 200, 500];
        intervals.forEach((delay) => {
            setTimeout(() => {
                applyLengthSelectorStyle();
            }, delay);
        });
    });
});

onBeforeUnmount(() => {
    if (observer) {
        observer.disconnect();
        observer = null;
    }
});
</script>

<template>
    <AppLayout title="Manage Events">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Manage Events
                </h2>
                <Link
                    :href="route('events.index')"
                    class="inline-flex float-right items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    View Event List
                </Link>
            </div>
        </template>

        <div class="py-6">
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

                <!-- Add Event Button -->
                <div class="mb-6 flex justify-end">
                    <Link :href="route('events.create')">
                        <PrimaryButton>Add Event</PrimaryButton>
                    </Link>
                </div>

                <!-- Events DataTable -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="overflow-x-auto p-4">
                        <DataTable
                            :key="dataTableKey"
                            :options="dataTableOptions"
                            class="display stripe hover w-full text-sm"
                        >
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>Capacity</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr 
                                    v-for="event in events" 
                                    :key="event.id"
                                    :class="{
                                        'opacity-60 bg-gray-50': isDeleted(event)
                                    }"
                                >
                                    <td class="whitespace-nowrap text-sm text-gray-500">
                                        {{ event.id }}
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img
                                                    class="h-10 w-10 rounded-full object-cover"
                                                    :src="eventImageUrl(event)"
                                                    :alt="event.name"
                                                />
                                            </div>
                                            <div class="ml-4">
                                                <div 
                                                    class="text-sm font-medium"
                                                    :class="{
                                                        'text-gray-900': !isDeleted(event),
                                                        'text-gray-500 line-through': isDeleted(event)
                                                    }"
                                                >
                                                    {{ event.name }}
                                                    <span v-if="isDeleted(event)" class="ml-2 text-xs text-red-600 font-normal">
                                                        (Deleted)
                                                    </span>
                                                </div>
                                                <div
                                                    class="text-sm text-gray-500 line-clamp-1"
                                                >
                                                    {{ event.description }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap text-sm text-gray-500">
                                        {{ formatDate(event.date) }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span
                                            :class="{
                                                'bg-green-100 text-green-800': (event.limit - (event.attendees_count || 0)) > 10,
                                                'bg-yellow-100 text-yellow-800':
                                                    (event.limit - (event.attendees_count || 0)) > 0 &&
                                                    (event.limit - (event.attendees_count || 0)) <= 10,
                                                'bg-red-100 text-red-800': (event.limit - (event.attendees_count || 0)) <= 0,
                                            }"
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                        >
                                            {{
                                                (event.limit - (event.attendees_count || 0)) <= 0
                                                    ? 'FULL'
                                                    : event.limit - (event.attendees_count || 0)
                                            }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <template v-if="!isDeleted(event)">
                                                <Link
                                                    :href="route('events.edit', event.id)"
                                                    class="text-indigo-600 hover:text-indigo-900 mr-2"
                                                >
                                                    <SecondaryButton>Edit</SecondaryButton>
                                                </Link>
                                                <DangerButton
                                                    @click="confirmDelete(event)"
                                                >
                                                    Delete
                                                </DangerButton>
                                            </template>
                                            <template v-else>
                                                <PrimaryButton
                                                    @click="restoreEvent(event)"
                                                    class="bg-green-600 hover:bg-green-700 focus:ring-green-500"
                                                >
                                                    Restore
                                                </PrimaryButton>
                                            </template>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </DataTable>
                        <div
                            v-if="events.length === 0"
                            class="px-6 py-4 text-center text-sm text-gray-500"
                        >
                            No events found. Create your first event!
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <ConfirmationModal
            :show="showDeleteModal"
            @close="showDeleteModal = false"
        >
            <template #title> Delete Event </template>

            <template #content>
                Are you sure you want to delete this event? This action cannot
                be undone.
                <div v-if="eventToDelete" class="mt-4">
                    <p class="font-semibold">{{ eventToDelete.name }}</p>
                </div>
            </template>

            <template #footer>
                <SecondaryButton @click="showDeleteModal = false">
                    Cancel
                </SecondaryButton>

                <DangerButton
                    class="ml-3"
                    :class="{ 'opacity-25': false }"
                    @click="deleteEvent"
                >
                    Delete Event
                </DangerButton>
            </template>
        </ConfirmationModal>
    </AppLayout>
</template>

<style scoped>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<style>
    /* Target DataTables length selector by ID */
    #dt-length-0,
    select[id^="dt-length-"],
    .dt-length select,
    select.dt-length {
        width: 60px !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        appearance: none !important;
        background-image: none !important;
        background: white !important;
    }
    
    /* Hide default arrow in IE/Edge */
    #dt-length-0::-ms-expand,
    select[id^="dt-length-"]::-ms-expand,
    .dt-length select::-ms-expand {
        display: none !important;
    }
    
    /* Additional webkit fixes */
    #dt-length-0::-webkit-inner-spin-button,
    #dt-length-0::-webkit-outer-spin-button,
    select[id^="dt-length-"]::-webkit-inner-spin-button,
    select[id^="dt-length-"]::-webkit-outer-spin-button {
        -webkit-appearance: none !important;
        margin: 0 !important;
    }
</style>
