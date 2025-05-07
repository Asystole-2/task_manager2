<script setup>
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';

const props = defineProps({
    events: Array,
});

const emit = defineEmits(['eventClick', 'dateClick']);

const calendarOptions = {
    plugins: [dayGridPlugin, interactionPlugin],
    initialView: 'dayGridMonth',
    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,dayGridWeek,dayGridDay'
    },
    height: 'auto',
    editable: true,
    selectable: true,
    events: props.events.map(event => ({
        id: event.id,
        title: event.title,
        start: event.start,
        end: event.end,
        allDay: event.all_day, // Make sure this matches your DB column
        color: event.color,
        extendedProps: {
            description: event.description
        }
    })),
    eventClick: (info) => {
        emit('eventClick', info.event);
    },
    dateClick: (info) => {
        emit('dateClick', info);
    }
};
</script>

<template>
    <div class="fc-container">
        <FullCalendar :options="calendarOptions" />
    </div>
</template>

<style>
.fc-container {
    padding: 1rem;
}
.fc {
    max-width: 100%;
    margin: 0 auto;
}
</style>
