<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0">
        <div class="flex flex-col flex-grow pt-5 bg-white dark:bg-gray-800 overflow-y-auto border-r border-gray-200 dark:border-gray-700">
            <div class="flex items-center flex-shrink-0 px-4">
                <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-orange-500">
                    TaskMaster
                </span>
            </div>
            <div class="mt-5 flex-1 flex flex-col">
                <nav class="flex-1 px-2 pb-4 space-y-1">
                    <Link
                        :href="route('dashboard')"
                        class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700"
                        :class="{ 'bg-gray-200 dark:bg-gray-700': $page.url === '/dashboard' }"
                    >
                        <svg class="mr-3 h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </Link>
                    <Link
                        :href="route('ProjectManagement.index')"
                        class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700"
                        :class="{ 'bg-gray-200 dark:bg-gray-700': $page.url.startsWith('/ProjectManagement') }"
                    >
                        <svg class="mr-3 h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Projects
                    </Link>
                    <Link
                        :href="route('tasks.index')"
                        class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700"
                        :class="{ 'bg-gray-200 dark:bg-gray-700': $page.url.startsWith('/tasks') }"
                    >
                        <svg class="mr-3 h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Tasks
                    </Link>
                    <Link
                        :href="route('calendar.index')"
                        class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700"
                        :class="{ 'bg-gray-200 dark:bg-gray-700': $page.url.startsWith('/calendar') }"
                    >
                        <svg class="mr-3 h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Calendar
                    </Link>
                </nav>

                <!-- Bottom section for user/settings -->
                <div class="px-4 py-4 mt-auto">
                    <Link
                        :href="route('profile.edit')"
                        class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700"
                    >
                        <svg class="mr-3 h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profile
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
