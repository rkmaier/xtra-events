<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import DateTimePicker from '@/Components/DateTimePicker.vue';
import ImageUpload from '@/Components/ImageUpload.vue';

const form = useForm({
    name: '',
    description: '',
    date: '',
    image: null,
    limit: 1,
});

const createEvent = () => {
    form.post(route('events.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <AppLayout title="Create Event">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create Event
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <FormSection @submitted="createEvent">
                    <template #title> Event Information </template>

                    <template #description>
                        Create a new event. Fill in all the required
                        information below.
                    </template>

                    <template #form>
                        <!-- Name -->
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="name" value="Event Name" />
                            <TextInput
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="mt-1 block w-full"
                                autofocus
                            />
                            <InputError
                                :message="form.errors.name"
                                class="mt-2"
                            />
                        </div>

                        <!-- Description -->
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="description" value="Description" />
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="4"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            ></textarea>
                            <InputError
                                :message="form.errors.description"
                                class="mt-2"
                            />
                        </div>

                        <!-- Date -->
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="date" value="Date & Time" />
                            <DateTimePicker
                                id="date"
                                v-model="form.date"
                            />
                            <InputError
                                :message="form.errors.date"
                                class="mt-2"
                            />
                        </div>

                        <!-- Image Upload -->
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="image" value="Event Image" />
                            <ImageUpload
                                id="image"
                                v-model="form.image"
                            />
                            <InputError
                                :message="form.errors.image"
                                class="mt-2"
                            />
                        </div>

                        <!-- Limit -->
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="limit" value="Total Capacity" />
                            <TextInput
                                id="limit"
                                v-model="form.limit"
                                type="number"
                                min="1"
                                class="mt-1 block w-full"
                            />
                            <InputError
                                :message="form.errors.limit"
                                class="mt-2"
                            />
                            <p class="mt-1 text-sm text-gray-500">
                                Maximum number of attendees for this event.
                            </p>
                        </div>

                    </template>

                    <template #actions>
                        <Link :href="route('events.manage')">
                            <SecondaryButton>Cancel</SecondaryButton>
                        </Link>

                        <PrimaryButton
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                            class="ml-3"
                        >
                            Create Event
                        </PrimaryButton>
                    </template>
                </FormSection>
            </div>
        </div>
    </AppLayout>
</template>

