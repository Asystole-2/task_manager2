<script setup>
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';

const props = defineProps({
    events: Array,
});

const calendarOptions = {
    plugins: [dayGridPlugin],
    initialView: 'dayGridMonth',
    headerToolbar: false,
    height: '400px',
    events: props.events.map(event => ({
        id: event.id,
        title: event.title,
        start: event.start,
        end: event.end,
        allDay: event.all_day,
        color: event.color || '#e63946',
    })),
};
</script>

<template>
    <FullCalendar :options="calendarOptions" />
</template>
