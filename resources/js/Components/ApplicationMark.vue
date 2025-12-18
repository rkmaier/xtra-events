<template>
    <!-- Event-style logo: calendar with checkmark -->
    <svg
        viewBox="0 0 48 48"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
    >
        <!-- Background circle -->
        <circle cx="24" cy="24" r="22" class="text-indigo-600" fill="currentColor" opacity="0.12" />

        <!-- Calendar body -->
        <rect
            x="11"
            y="14"
            width="26"
            height="22"
            rx="3"
            class="text-white"
            fill="currentColor"
        />
        <!-- Calendar header bar -->
        <rect
            x="11"
            y="14"
            width="26"
            height="6"
            rx="3"
            class="text-indigo-600"
            fill="currentColor"
        />

        <!-- Calendar rings -->
        <rect x="16" y="11" width="2.5" height="6" rx="1.25" class="text-indigo-600" fill="currentColor" />
        <rect x="29.5" y="11" width="2.5" height="6" rx="1.25" class="text-indigo-600" fill="currentColor" />

        <!-- Checkmark inside calendar -->
        <path
            d="M18 26.5L22 30.5L30 22.5"
            stroke="#22c55e"
            stroke-width="2.4"
            stroke-linecap="round"
            stroke-linejoin="round"
        />
    </svg>
</template>
