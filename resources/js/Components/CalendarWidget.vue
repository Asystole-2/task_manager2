<script setup>
// Core CSS must come first
import '@fullcalendar/core/vdom'
import '@fullcalendar/common/main.css'
import '@fullcalendar/daygrid/main.css'

// Then core library
import FullCalendar from '@fullcalendar/vue3'

// Then plugins
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'

import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    events: Array,
})

const calendarRef = ref(null)

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
        const title = prompt('Enter event title:')
        if (title) {
            router.post(route('calendar.store'), {
                title: title,
                start: info.startStr,
                end: info.endStr,
                all_day: info.allDay,
                color: '#e63946'
            })
        }
    },
    eventClick: (info) => {
        if (confirm(`Delete "${info.event.title}" event?`)) {
            router.delete(route('calendar.destroy', info.event.id))
        }
    }
}
</script>

<template>
    <FullCalendar ref="calendarRef" :options="calendarOptions" />
</template>
