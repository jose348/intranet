document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    // Inicializa FullCalendar con idioma español y cambio de "today" a "hoy"
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'es',
        buttonText: {
            today: 'Hoy'
        },
        initialView: 'dayGridMonth',
        height: 'auto',
        contentHeight: 700,
        editable: true,

        eventClick: function(info) {
            // Muestra los datos de la capacitación en el modal
            document.getElementById('modalTitulo').textContent = info.event.title;
            document.getElementById('modalExpositor').textContent = "Expositor: " + info.event.extendedProps.expositor;
            document.getElementById('modalFlyer').src = info.event.extendedProps.flyer;

            // Asignar el ID del evento al botón de eliminar
            document.getElementById('btnEliminar').setAttribute('data-id', info.event.id);

            // Mostrar el modal
            $('#eventInfoModal').modal('show');
        }
    });

    calendar.render();

    // Manejar el formulario de agregar nueva capacitación
    document.getElementById('capacitacionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var titulo = document.getElementById('tituloCapacitacion').value;
        var expositor = document.getElementById('expositorCapacitacion').value;
        var fechaInicio = document.getElementById('fechaInicio').value;
        var fechaFin = document.getElementById('fechaFin').value;
        var archivo = document.getElementById('archivoCapacitacion').files[0]; // Obtener el archivo subido
        var flyer = document.getElementById('flyerCapacitacion').files[0]; // Obtener el flyer subido

        // Generar un color aleatorio de la lista
        var colores = ['#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8'];
        var colorAleatorio = colores[Math.floor(Math.random() * colores.length)];

        // Agregar el evento al calendario
        calendar.addEvent({
            id: String(new Date().getTime()), // Generar un ID único basado en el tiempo
            title: titulo + ' - ' + expositor,
            start: fechaInicio,
            end: fechaFin,
            backgroundColor: colorAleatorio,
            borderColor: colorAleatorio,
            textColor: '#fff',
            flyer: URL.createObjectURL(flyer), // Usar la URL de la imagen subida
            expositor: expositor
        });

        // Cerrar el modal
        $('#addCapacitacionModal').modal('hide');

        // Limpiar el formulario
        document.getElementById('capacitacionForm').reset();
    });

    // Manejar el botón de eliminar
    document.getElementById('btnEliminar').addEventListener('click', function() {
        var eventId = this.getAttribute('data-id');
        var event = calendar.getEventById(eventId);
        if (event) {
            event.remove(); // Elimina el evento del calendario
            $('#eventInfoModal').modal('hide'); // Cerrar el modal
        }
    });
});