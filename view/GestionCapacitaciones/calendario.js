document.addEventListener('DOMContentLoaded', function() {
    // Asegúrate de que `usuarioId` esté accesible desde el contexto global
    console.log("Usuario ID en js:", usuarioId); // Verifica el ID del usuario

    const calendarEl = document.getElementById('calendar');
    const btnNotificaciones = document.getElementById("btnNotificaciones");
    const notificacionesCount = document.getElementById("notificacionesCount");
    const listaNotificaciones = document.getElementById("listaNotificaciones");

    // Inicializa FullCalendar
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'es',
        buttonText: {
            today: 'Hoy'
        },
        initialView: 'dayGridMonth',
        height: 'auto',
        contentHeight: 700,
        editable: false,
        events: function(fetchInfo, successCallback, failureCallback) {
            fetch(`/Intranet/controller/capacitacion.php?op=obtener_capacitaciones_id&pers_id=${usuarioId}`)
                .then(response => response.json())
                .then(events => {
                    console.log("Eventos recibidos:", events); // Log de eventos recibidos
                    successCallback(events);
                })
                .catch(error => {
                    console.error("Error al cargar eventos:", error);
                    failureCallback(error);
                });
        },
        eventClick: function(info) {
            document.getElementById('modalTitulo').textContent = info.event.title;
            document.getElementById('modalExpositor').textContent = "Expositor: " + info.event.extendedProps.expositor;
            document.getElementById('modalFlyer').src = info.event.extendedProps.flyer;
            $('#eventInfoModal').modal('show');
        }
    });

    calendar.render();

    // Función para obtener notificaciones
    function obtenerNotificaciones() {
        fetch(`/Intranet/controller/capacitacion.php?op=obtener_notificaciones&pers_id=${usuarioId}`)
            .then(response => response.json())
            .then(data => {
                if (data.notificaciones) {
                    mostrarNotificaciones(data.notificaciones);
                }
            })
            .catch(error => console.error("Error al obtener notificaciones:", error));
    }

    // Muestra las notificaciones en el modal y actualiza el contador
    function mostrarNotificaciones(notificaciones) {
        listaNotificaciones.innerHTML = ""; // Limpiar lista de notificaciones
        notificacionesCount.textContent = notificaciones.length;

        notificaciones.forEach(notificacion => {
            const li = document.createElement("li");
            li.className = "list-group-item d-flex justify-content-between align-items-center";
            li.innerHTML = `
                <div>
                    <strong>${notificacion.capa_titulo}</strong><br>
                    <small>Expositor: ${notificacion.capa_expositor}</small><br>
                    <small>Fecha: ${notificacion.capa_fecha_inicio} ${notificacion.capa_hora_inicio}</small>
                </div>
                <button class="btn btn-sm btn-primary btn-marcar-leida" data-id="${notificacion.caper_id}">
                    Marcar como leída
                </button>
            `;

            listaNotificaciones.appendChild(li);
        });

        document.querySelectorAll(".btn-marcar-leida").forEach(button => {
            button.addEventListener("click", function() {
                const caperId = this.getAttribute("data-id");
                marcarNotificacionLeida(caperId);
            });
        });
    }

    // Marca la notificación como leída
    function marcarNotificacionLeida(caperId) {
        fetch(`/Intranet/controller/capacitacion.php?op=marcar_notificacion_leida`, {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `caper_id=${caperId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    obtenerNotificaciones();
                }
            })
            .catch(error => console.error("Error al marcar notificación como leída:", error));
    }

    // Cargar notificaciones y evento de botón
    obtenerNotificaciones();
    btnNotificaciones.addEventListener("click", function() {
        $('#modalNotificaciones').modal('show');
    });
});