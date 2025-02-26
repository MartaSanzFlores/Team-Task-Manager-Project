// assets/js/fullcalendar.js

document.addEventListener('DOMContentLoaded', function() {

    var calendarEl = document.getElementById('calendar');

    if (calendarEl) {

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            firstDay: 1,
            height: '100%',
            aspectRatio: 1.5,
            events: '/api/calendar-events'
        });
        calendar.render();
    }

});
