// assets/js/fullcalendar.js

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        firstDay: 1,
        height: '100%', // Ajuste en fonction de la hauteur disponible
        aspectRatio: 1.5, // Garde un bon ratio hauteur/largeur
        events: '/api/calendar-events' // Récupère les événements dynamiquement
    });
    calendar.render();
});
