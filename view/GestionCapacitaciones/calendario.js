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

    function mostrarNotificaciones(notificaciones) {
        listaNotificaciones.innerHTML = ""; // Limpiar lista de notificaciones
        notificacionesCount.textContent = notificaciones.length;

        notificaciones.forEach(notificacion => {
            const li = document.createElement("li");
            li.className = "notificacion-item list-group-item";

            const estadoAsistir = notificacion.caper_confirmar ? "Asistir" : "No Asistir";
            const botonAsistir = `
                <button class="btn btn-success btn-sm btn-asistir" data-id="${notificacion.caper_id}">
                    Asistir
                </button>
            `;
            const botonNoAsistir = `
                <button class="btn btn-danger btn-sm btn-no-asistir" data-id="${notificacion.caper_id}">
                    No Asistir
                </button>
            `;

            li.innerHTML = `
                <div>
                    <strong>${notificacion.capa_titulo}</strong><br>
                    <small>Expositor: ${notificacion.capa_expositor}</small><br>
                    <small>Fecha: ${notificacion.capa_fecha_inicio} ${notificacion.capa_hora_inicio}</small>
                </div>
                <div class="notificacion-botones">
                    ${botonAsistir}
                    ${botonNoAsistir}
                </div>
            `;

            listaNotificaciones.appendChild(li);
        });

        document.querySelectorAll(".btn-asistir").forEach(button => {
            button.addEventListener("click", function() {
                const caperId = this.getAttribute("data-id");
                cambiarEstadoNotificacion(caperId, "asistir", this);
            });
        });

        document.querySelectorAll(".btn-no-asistir").forEach(button => {
            button.addEventListener("click", function() {
                const caperId = this.getAttribute("data-id");
                cambiarEstadoNotificacion(caperId, "no_asistir", this);
            });
        });
    }

    // Función para cambiar el estado de la notificación en el servidor y actualizar el botón
    function cambiarEstadoNotificacion(caperId, accion, button) {
        fetch(`/Intranet/controller/capacitacion.php?op=cambiar_estado_notificacion`, {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `caper_id=${caperId}&accion=${accion}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    if (accion === "asistir") {
                        button.classList.remove("btn-danger");
                        button.classList.add("btn-success");
                        button.textContent = "Asistir";
                        button.nextElementSibling.classList.add("d-none");
                    } else {
                        button.classList.remove("btn-success");
                        button.classList.add("btn-danger");
                        button.textContent = "No Asistir";
                        button.previousElementSibling.classList.add("d-none");
                    }
                }
            })
            .catch(error => console.error("Error al cambiar estado de notificación:", error));
    }



    // Cargar notificaciones y evento de botón
    obtenerNotificaciones();
    btnNotificaciones.addEventListener("click", function() {
        $('#modalNotificaciones').modal('show');
    });
});