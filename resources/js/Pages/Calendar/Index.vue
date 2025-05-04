<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    events: Array,
});

const calendarRef = ref(null);

const calendarOptions = {
    plugins: [dayGridPlugin, interactionPlugin],
    initialView: 'dayGridMonth',
    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,dayGridWeek,dayGridDay'
    },
    events: props.events.map(event => ({
        id: event.id,
        title: event.title,
        start: event.start,
        end: event.end,
        allDay: event.all_day,
        color: event.color || '#e63946',
    })),
    editable: true,
    selectable: true,
    select: (info) => {
        const title = prompt('Enter event title:');
        if (title) {
            router.post(route('calendar.store'), {
                title: title,
                start: info.startStr,
                end: info.endStr,
                all_day: info.allDay,
                color: '#e63946'
            });
        }
    },
    eventClick: (info) => {
        if (confirm(`Delete "${info.event.title}" event?`)) {
            router.delete(route('calendar.destroy', info.event.id));
        }
    }
};
</script>

<template>
    <Head title="Calendar" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-blue-500">
                Calendar
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden p-6">
                    <FullCalendar ref="calendarRef" :options="calendarOptions" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
