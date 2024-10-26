document.addEventListener('DOMContentLoaded', function() {
    const capacitacionForm = document.getElementById('capacitacionForm');
    const flyerInput = document.getElementById('flyerCapacitacion');
    const archivoInput = document.getElementById('archivoCapacitacion');
    const videoInput = document.getElementById('videoCapacitacion');
    const previewFlyer = document.getElementById('previewFlyer');
    const previewVideo = document.getElementById('previewVideo');
    const videoSource = document.getElementById('videoSource');
    const modalPreviewContent = document.getElementById('modalPreviewContent');


    const flyerInputEditar = document.getElementById('flyerCapacitacionEditar');
    const archivoInputEditar = document.getElementById('archivoCapacitacionEditar');
    const videoInputEditar = document.getElementById('videoCapacitacionEditar');


    //guardando la capacitacion
    capacitacionForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(capacitacionForm);

        fetch('/Intranet/controller/capacitacion.php?op=guardar_capacitacion', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Éxito',
                        text: 'Capacitación guardada con éxito.',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        limpiarFormulario();

                        $('#addCapacitacionModal').modal('hide')
                        calendar.refetchEvents(); // Refrescar calendario al cerrar el modal




                        // Actualizar la leyenda con el mes actual
                        const currentDate = calendar.getDate();
                        const mes = currentDate.getMonth() + 1; // Mes actual (1-indexado)
                        const anio = currentDate.getFullYear();

                        actualizarLeyenda(mes, anio); // Llamada inmediata a actualizar la leyenda

                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'Error al guardar la capacitación: ' + data.message,


                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            })
            .catch(error => console.error('Error en la petición:', error));
    });


    /*TODO LIMPIAMOS EL FORMUALARIO */
    function limpiarFormulario() {
        capacitacionForm.reset(); // Limpia los campos del formulario

        // Resetear los nombres de archivos seleccionados
        document.getElementById('flyerSeleccionado').textContent = 'Ningún archivo seleccionado';
        document.getElementById('archivoSeleccionado').textContent = 'Ningún archivo seleccionado';
        document.getElementById('videoSeleccionado').textContent = 'Ningún archivo seleccionado';

        // Limpiar el contenido de los inputs de archivo
        flyerInput.value = '';
        archivoInput.value = '';
        videoInput.value = '';
    }







    const listaPasadas = document.getElementById('listaPasadas');
    const listaEnProceso = document.getElementById('listaEnProceso');
    const listaFuturas = document.getElementById('listaFuturas');
    const calendarEl = document.getElementById('calendar');
    let debounceTimer;

    const calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'es',
        buttonText: { today: 'Hoy' },
        initialView: 'dayGridMonth',
        height: 'auto',
        contentHeight: 1000,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: '' // Eliminamos los botones innecesarios
        },
        events: '/Intranet/controller/capacitacion.php?op=obtener_capacitaciones',
        eventDisplay: 'block',

        // Detectar cambio de vista o mes
        datesSet: function(info) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const mes = info.view.currentStart.getMonth() + 1; // Asegurar que el mes sea correcto
                const anio = info.view.currentStart.getFullYear();
                console.log(`Vista cambiada: Mes ${mes}, Año ${anio}`);

                // Actualizar la leyenda al cambiar de mes
                actualizarLeyenda(mes, anio);
            }, 300); // Debounce de 300ms para evitar llamadas duplicadas
        },

        eventDidMount: function(info) {
            const now = new Date(); // Fecha actual
            const eventStart = new Date(info.event.start); // Fecha de inicio del evento
            const eventEnd = new Date(info.event.end || info.event.start); // Fecha de fin del evento
            const punto = document.createElement('span');
            punto.classList.add('estado-punto'); // Punto decorativo

            if (eventEnd < now) {
                // Evento pasado: color rojo
                info.el.classList.add('fc-event-pasado');
                info.el.style.backgroundColor = 'rgba(255, 0, 0, 0.7)'; // Fondo rojo
                info.el.style.borderColor = '#ff0000'; // Borde rojo
                punto.style.backgroundColor = '#ff0000'; // Punto decorativo en rojo
            } else if (eventStart <= now && eventEnd >= now) {
                // Evento en proceso: color amarillo
                info.el.classList.add('fc-event-actual');
                info.el.style.backgroundColor = 'rgba(255, 193, 7, 0.7)'; // Fondo amarillo
                info.el.style.borderColor = '#ffc107'; // Borde amarillo
                punto.style.backgroundColor = '#ffc107'; // Punto decorativo en amarillo
            } else {
                // Evento futuro: color verde
                info.el.classList.add('fc-event-futuro');
                info.el.style.backgroundColor = 'rgba(40, 167, 69, 0.7)'; // Fondo verde
                info.el.style.borderColor = '#28a745'; // Borde verde
                punto.style.backgroundColor = '#28a745'; // Punto decorativo en verde
            }

            // Establecer el color del texto y agregar el punto decorativo
            info.el.style.color = 'white'; // Texto blanco para mayor legibilidad
            info.el.prepend(punto);
        },

        eventClick: function(info) {
            const titulo = info.event.title;
            const expositor = info.event.extendedProps.expositor || 'No especificado';
            Swal.fire({
                title: titulo,
                html: `<strong>Expositor:</strong> ${expositor}`,
                icon: 'info',
                confirmButtonText: 'Aceptar'
            });
        }
    });

    calendar.render();

    // Función para actualizar la leyenda al cambiar de mes
    function actualizarLeyenda(mes, anio) {
        const url = `/Intranet/controller/capacitacion.php?op=listar_capacitaciones_mes&mes=${mes}&anio=${anio}&t=${new Date().getTime()}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log('Capacitaciones:', data);

                // Limpiar las listas de la leyenda
                limpiarLeyenda();

                // Agregar ítems a cada lista de la leyenda
                data.pasadas.forEach(capacitacion => {
                    listaPasadas.appendChild(crearItemCapacitacion(capacitacion));
                });

                data.en_proceso.forEach(capacitacion => {
                    listaEnProceso.appendChild(crearItemCapacitacion(capacitacion));
                });

                data.futuras.forEach(capacitacion => {
                    listaFuturas.appendChild(crearItemCapacitacion(capacitacion));
                });
            })
            .catch(error => {
                console.error('Error al cargar la leyenda:', error);
            });
    }

    // Función para limpiar las listas de la leyenda
    function limpiarLeyenda() {
        listaPasadas.innerHTML = '';
        listaEnProceso.innerHTML = '';
        listaFuturas.innerHTML = '';
    }

    // Función para crear un ítem en la leyenda
    // Función para crear un ítem en la leyenda con botones Editar y Eliminar
    /// Función para crear un ítem en la leyenda con botones Editar y Eliminar
    function crearItemCapacitacion(capacitacion) {
        const item = document.createElement('li');
        item.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');

        // Contenido de la capacitación (título)
        const titulo = document.createElement('span');
        titulo.textContent = capacitacion.capa_titulo;

        // Contenedor para los botones de acción
        const acciones = document.createElement('div');
        acciones.classList.add('d-flex', 'gap-2'); // Espacio entre botones

        // Botón Editar con ícono
        const btnEditar = document.createElement('button');
        btnEditar.classList.add('btn', 'btn-sm', 'btn-primary', 'btn-circle');
        btnEditar.innerHTML = '<i class="fa fa-edit"></i>'; // Ícono de editar
        btnEditar.title = 'Editar'; // Tooltip
        btnEditar.onclick = function() {
            editarCapacitacion(capacitacion.capa_id);
        };

        // Botón Eliminar con ícono
        const btnEliminar = document.createElement('button');
        btnEliminar.classList.add('btn', 'btn-sm', 'btn-danger', 'btn-circle');
        btnEliminar.innerHTML = '<i class="fa fa-trash"></i>'; // Ícono de eliminar
        btnEliminar.title = 'Eliminar'; // Tooltip
        btnEliminar.onclick = function() {
            eliminarCapacitacion(capacitacion.capa_id);
        };

        // Añadir los botones al contenedor de acciones
        acciones.appendChild(btnEditar);
        acciones.appendChild(btnEliminar);

        // Añadir el título y las acciones al ítem de la lista
        item.appendChild(titulo);
        item.appendChild(acciones);

        return item;
    }



    /*TODO ELIMINAR CAPACITACIONES  */
    // Función para eliminar una capacitación
    function eliminarCapacitacion(capa_id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/Intranet/controller/capacitacion.php?op=eliminar_capacitacion', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({ id: capa_id })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire('Eliminado', data.message, 'success');
                            actualizarLeyenda(new Date().getMonth() + 1, new Date().getFullYear());
                            calendar.refetchEvents(); // Refresca los eventos del calendario
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    }


    /*TODO DITAR CAPACITACION */
    /*TODO DITAR CAPACITACION */
    function editarCapacitacion(capa_id) {
        fetch(`/Intranet/controller/capacitacion.php?op=obtener_capacitacion`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ id: capa_id }),
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.status === 'success') {
                    const capacitacion = data.capacitacion;

                    document.getElementById('editCapacitacionId').value = capacitacion.capa_id;
                    document.getElementById('editTituloCapacitacion').value = capacitacion.capa_titulo;
                    document.getElementById('editExpositorCapacitacion').value = capacitacion.capa_expositor;
                    document.getElementById('editFechaInicio').value = capacitacion.capa_fecha_inicio;
                    document.getElementById('editHoraInicio').value = capacitacion.capa_hora_inicio;
                    document.getElementById('editFechaFin').value = capacitacion.capa_fecha_fin;
                    document.getElementById('editHoraFin').value = capacitacion.capa_hora_fin;

                    actualizarNombreArchivo('editFlyerSeleccionado', capacitacion.capa_flyer);
                    actualizarNombreArchivo('editArchivoSeleccionado', capacitacion.capa_archivo);
                    actualizarNombreArchivo('editVideoSeleccionado', capacitacion.capa_video);

                    $('#editCapacitacionModal').modal('show');
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch((error) => console.error('Error:', error));
    }

    function actualizarNombreArchivo(spanId, archivo) {
        const span = document.getElementById(spanId);
        span.textContent = archivo ? archivo.split('/').pop() : 'Ningún archivo adquirido';
    }










    document.getElementById('capacitacionFormEditar').onsubmit = function(e) {
        e.preventDefault(); // Prevenir el envío del formulario

        const formData = new FormData(this); // Crear el FormData desde el formulario
        // Verificar los datos que se están enviando


        if (flyerInputEditar.files.length > 0) {
            formData.set('flyerCapacitacion', flyerInputEditar.files[0]);
        }

        if (archivoInputEditar.files.length > 0) {
            Array.from(archivoInputEditar.files).forEach((file) => {
                formData.append('archivoCapacitacion[]', file);
            });
        }

        if (videoInputEditar.files.length > 0) {
            formData.set('videoCapacitacion', videoInputEditar.files[0]);
        }


        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }
        // Realizamos la solicitud fetch
        fetch('/Intranet/controller/capacitacion.php?op=actualizar_capacitacion', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Respuesta del servidor:', data); // Imprimir la respuesta completa

                if (data.status === 'success') {
                    Swal.fire('Éxito', 'Capacitación actualizada con éxito.', 'success').then(() => {
                        $('#editCapacitacionModal').modal('hide'); // Cerrar el modal
                        calendar.refetchEvents(); // Refrescar el calendario
                    });
                } else {
                    Swal.fire('Error', 'Error al actualizar la capacitación: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error al realizar la solicitud:', error);
                Swal.fire('Error', 'No se pudo completar la solicitud.', 'error');
            });
    };

    // Botones para seleccionar archivos en el modal de edición
    // Botón para seleccionar flyer
    // Botón para seleccionar flyer
    document.getElementById('btnFlyerEditar').addEventListener('click', function() {
        flyerInputEditar.click();
    });
    flyerInputEditar.addEventListener('change', function() {
        const flyer = this.files[0];
        if (flyer) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const flyerName = document.getElementById('editFlyerSeleccionado');
                flyerName.textContent = flyer.name;
                flyerName.style.cursor = 'pointer';

                flyerName.onclick = function() {
                    showModalPreview('img', e.target.result);
                };
            };
            reader.readAsDataURL(flyer);
        }
    });



    /*   document.getElementById('flyerCapacitacionEditar').addEventListener('change', function() {
          const flyer = this.files[0];
          document.getElementById('flyerSeleccionadoEditar').textContent = flyer ? flyer.name : 'Ningún archivo seleccionado';
      }); */

    // Botón para seleccionar archivos
    document.getElementById('btnArchivoEditar').addEventListener('click', function() {
        archivoInputEditar.click();
    });
    archivoInputEditar.addEventListener('change', function() {
        const archivos = Array.from(this.files).map(file => file.name).join(', ');
        const archivoName = document.getElementById('editArchivoSeleccionado');
        archivoName.textContent = archivos || 'Ningún archivo seleccionado';
    });



    /* document.getElementById('archivoCapacitacionEditar').addEventListener('change', function() {
        const archivos = Array.from(this.files).map(file => file.name).join(', ');
        document.getElementById('archivoSeleccionadoEditar').textContent = archivos || 'Ningún archivo seleccionado';
    }); */

    // Botón para seleccionar video
    document.getElementById('btnVideoEditar').addEventListener('click', function() {
        videoInputEditar.click();
    });
    videoInputEditar.addEventListener('change', function() {
        const video = this.files[0];
        const videoName = document.getElementById('editVideoSeleccionado');
        videoName.textContent = video ? video.name : 'Ningún archivo seleccionado';
    });

    /*  document.getElementById('videoCapacitacionEditar').addEventListener('change', function() {
         const video = this.files[0];
         document.getElementById('videoSeleccionadoEditar').textContent = video ? video.name : 'Ningún archivo seleccionado';
     }); */








    // Botón para seleccionar el flyer
    document.getElementById('btnFlyer').addEventListener('click', function() {
        flyerInput.click();
    });

    // Vista previa del flyer
    flyerInput.addEventListener('change', function() {
        const flyer = this.files[0];
        if (flyer) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const flyerName = document.getElementById('flyerSeleccionado');
                flyerName.textContent = flyer.name;
                flyerName.style.cursor = 'pointer';

                // Al hacer clic, mostrar modal con vista previa del flyer
                flyerName.onclick = function() {
                    showModalPreview('img', e.target.result);
                };
            };
            reader.readAsDataURL(flyer);
        }
    });

    // Botón para seleccionar archivos (sílabo o PPT/Word)
    document.getElementById('btnArchivo').addEventListener('click', function() {
        archivoInput.click();
    });

    // Mostrar nombres de archivos seleccionados y habilitar clic para previsualizarlos
    archivoInput.addEventListener('change', function() {
        const archivos = Array.from(this.files).map(file => file.name).join(', ');
        const archivosSpan = document.getElementById('archivoSeleccionado');
        archivosSpan.textContent = archivos || 'Ningún archivo seleccionado';
        archivosSpan.style.cursor = 'pointer';

        if (this.files.length > 0) {
            const fileURL = URL.createObjectURL(this.files[0]);
            archivosSpan.onclick = function() {
                showModalPreview('pdf', fileURL);
            };
        }
    });

    // Botón para seleccionar video
    document.getElementById('btnVideo').addEventListener('click', function() {
        videoInput.click();
    });

    // Vista previa del video
    videoInput.addEventListener('change', function() {
        const video = this.files[0];
        if (video) {
            const videoName = document.getElementById('videoSeleccionado');
            videoName.textContent = video.name;
            videoName.style.cursor = 'pointer';

            const videoURL = URL.createObjectURL(video);
            videoName.onclick = function() {
                showModalPreview('video', videoURL);
            };
        }
    });

    // Función para mostrar la previsualización en un modal
    function showModalPreview(type, source) {
        const modalPreviewContent = document.getElementById('modalPreviewContent');
        modalPreviewContent.innerHTML = '';

        if (type === 'img') {
            const img = document.createElement('img');
            img.src = source;
            img.style.maxWidth = '100%';
            modalPreviewContent.appendChild(img);
        } else if (type === 'video') {
            const video = document.createElement('video');
            video.src = source;
            video.controls = true;
            video.style.maxWidth = '100%';
            modalPreviewContent.appendChild(video);
        } else if (type === 'pdf') {
            const iframe = document.createElement('iframe');
            iframe.src = source;
            iframe.style.width = '100%';
            iframe.style.height = '400px';
            modalPreviewContent.appendChild(iframe);
        }

        $('#previewModal').modal('show');
    }








    /*TODO aqui va lo de mi seleccionar mi publico objetivo   */
    /*TODO aqui va lo de mi seleccionar mi publico objetivo   */
    /*TODO aqui va lo de mi seleccionar mi publico objetivo   */
    /*TODO aqui va lo de mi seleccionar mi publico objetivo   */
    /*TODO aqui va lo de mi seleccionar mi publico objetivo   */
    /*TODO aqui va lo de mi seleccionar mi publico objetivo   */


    //const btnCargarPersonas = document.getElementById('btnCargarPersonas');
    const contenedorTablaPersonas = document.getElementById('contenedorTablaPersonas');
    const listaPersonasSeleccionadas = document.getElementById('listaPersonasSeleccionadas');
    const tablaPersonas = document.querySelector('#tablaPersonas tbody');
    const buscarPersona = document.getElementById('buscarPersona');
    const paginationControls = document.getElementById('paginationControls');
    const selectAllCheckbox = document.getElementById('selectAll'); // Checkbox para seleccionar todos

    let personas = [];
    let personasFiltradas = [];
    let personasSeleccionadas = new Set();
    let currentPage = 1;
    const itemsPerPage = 5;

    function cargarPersonas() {
        fetch('/Intranet/controller/capacitacion.php?op=listar_personas')
            .then(response => response.json())
            .then(data => {
                personas = data;
                personasFiltradas = [...personas];
                renderizarTabla();
                generarControlesPaginacion();
            })
            .catch(error => console.error('Error al cargar personas:', error));
    }

    function renderizarTabla() {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const personasPagina = personasFiltradas.slice(startIndex, endIndex);

        tablaPersonas.innerHTML = '';

        personasPagina.forEach(persona => {
            const isChecked = personasSeleccionadas.has(persona.pers_dni) ? 'checked' : '';

            const row = document.createElement('tr');
            row.innerHTML = `
                    <td>
                        <input type="checkbox" class="persona-checkbox" data-id="${persona.pers_dni}" 
                        data-nombre="${persona.nombre_completo}" ${isChecked}>
                    </td>
                    <td>${persona.pers_dni}</td>
                    <td>${persona.nombre_completo}</td>
                `;
            tablaPersonas.appendChild(row);
        });

        agregarEventosCheckboxes(); // Reagregar eventos a los checkboxes
    }
    // Función para agregar eventos a los checkboxes de cada fila
    function agregarEventosCheckboxes() {
        document.querySelectorAll('.persona-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const personaId = this.getAttribute('data-id');
                const nombre = this.getAttribute('data-nombre');

                if (this.checked) {
                    personasSeleccionadas.add(personaId);
                    agregarPersonaSeleccionada(nombre, personaId);
                } else {
                    personasSeleccionadas.delete(personaId);
                    eliminarPersonaSeleccionada(personaId);
                }
            });
        });
    }

    // Función para seleccionar/deseleccionar todas las personas de la lista completa
    selectAllCheckbox.addEventListener('change', function() {
        const isChecked = this.checked;

        // Iterar sobre todas las personas de la lista completa
        personasFiltradas.forEach(persona => {
            if (isChecked) {
                personasSeleccionadas.add(persona.pers_dni); // Agregar al Set global
                agregarPersonaSeleccionada(persona.nombre_completo, persona.pers_dni);
            } else {
                personasSeleccionadas.delete(persona.pers_dni); // Remover del Set global
                eliminarPersonaSeleccionada(persona.pers_dni);
            }
        });

        // Refrescar la tabla para reflejar los cambios
        renderizarTabla();
    });

    // Función para agregar persona seleccionada a la lista
    function agregarPersonaSeleccionada(nombre, id) {
        if (!document.getElementById(`persona-${id}`)) {
            const li = document.createElement('li');
            li.className = 'list-group-item';
            li.id = `persona-${id}`;
            li.textContent = nombre;
            listaPersonasSeleccionadas.appendChild(li);
        }
    }

    // Función para eliminar persona seleccionada de la lista
    function eliminarPersonaSeleccionada(id) {
        const item = document.getElementById(`persona-${id}`);
        if (item) {
            listaPersonasSeleccionadas.removeChild(item);
        }
    }

    // Lógica para seleccionar/deseleccionar todas las personas
    selectAllCheckbox.addEventListener('change', function() {
        const isChecked = this.checked;
        document.querySelectorAll('.persona-checkbox').forEach(checkbox => {
            checkbox.checked = isChecked;

            const personaId = checkbox.getAttribute('data-id');
            const nombre = checkbox.getAttribute('data-nombre');

            if (isChecked) {
                personasSeleccionadas.add(personaId);
                agregarPersonaSeleccionada(nombre, personaId);
            } else {
                personasSeleccionadas.delete(personaId);
                eliminarPersonaSeleccionada(personaId);
            }
        });
    });

    function generarControlesPaginacion() {
        const totalPages = Math.ceil(personasFiltradas.length / itemsPerPage);
        const maxVisibleButtons = 5;

        paginationControls.innerHTML = '';

        const createPageButton = (page) => {
            const li = document.createElement('li');
            li.className = 'page-item';
            li.innerHTML = `<a class="page-link" href="#">${page}</a>`;
            if (page === currentPage) {
                li.classList.add('active');
            }
            li.addEventListener('click', function() {
                currentPage = page;
                renderizarTabla();
                generarControlesPaginacion();
            });
            return li;
        };

        const prevLi = document.createElement('li');
        prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
        prevLi.innerHTML = `<a class="page-link" href="#"> << </a>`;
        prevLi.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                renderizarTabla();
                generarControlesPaginacion();
            }
        });
        paginationControls.appendChild(prevLi);

        let startPage = Math.max(1, currentPage - Math.floor(maxVisibleButtons / 2));
        let endPage = Math.min(totalPages, startPage + maxVisibleButtons - 1);

        for (let i = startPage; i <= endPage; i++) {
            paginationControls.appendChild(createPageButton(i));
        }

        const nextLi = document.createElement('li');
        nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
        nextLi.innerHTML = `<a class="page-link" href="#"> >> </a>`;
        nextLi.addEventListener('click', function() {
            if (currentPage < totalPages) {
                currentPage++;
                renderizarTabla();
                generarControlesPaginacion();
            }
        });
        paginationControls.appendChild(nextLi);
    }

    buscarPersona.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        personasFiltradas = personas.filter(persona =>
            persona.nombre_completo.toLowerCase().includes(query) ||
            persona.pers_dni.includes(query)
        );
        currentPage = 1;
        renderizarTabla();
        generarControlesPaginacion();
    });

    btnCargarPersonas.addEventListener('click', function() {
        contenedorTablaPersonas.style.display = 'block';
        cargarPersonas();
    });
    /*TODO aqui va lo de mi seleccionar mi publico objetivo   */
    /*TODO aqui va lo de mi seleccionar mi publico objetivo   */
    /*TODO aqui va lo de mi seleccionar mi publico objetivo   */
    /*TODO aqui va lo de mi seleccionar mi publico objetivo   */
    /*TODO aqui va lo de mi seleccionar mi publico objetivo   */
    /*TODO aqui va lo de mi seleccionar mi publico objetivo   */









    /*TODO AHORA EN GENERAL CON EL ENVIAR MI CAPACITACION  */
    document.getElementById("btnEnviarCapacitacion").addEventListener("click", function() {
        // Mostrar el modal
        $('#modalListarCapacitaciones').modal('show');

        // Realizar la petición al servidor para obtener las capacitaciones
        fetch('/Intranet/controller/capacitacion.php?op=listar_capacitacionest')
            .then(response => response.json())
            .then(data => {
                console.log(data); // Verificar la respuesta en consola
                if (data.status === "success" && data.data.length > 0) {
                    let tbody = document.getElementById("capacitacionTableBody");
                    tbody.innerHTML = ""; // Limpiar contenido previo

                    // Ordenar las capacitaciones por fecha de inicio (descendente)
                    let capacitacionesOrdenadas = data.data.sort((a, b) => {
                        return new Date(b.capa_fecha_inicio) - new Date(a.capa_fecha_inicio);
                    });

                    // Iterar sobre las capacitaciones y agregarlas a la tabla
                    capacitacionesOrdenadas.forEach(capacitacion => {
                        let row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${capacitacion.capa_id}</td>
                            <td>${capacitacion.capa_titulo}</td>
                            <td>${capacitacion.capa_expositor}</td>
                            <td>${capacitacion.capa_fecha_inicio} ${capacitacion.capa_hora_inicio}</td> `;

                        // Agregar evento para seleccionar la fila
                        row.addEventListener('click', function() {
                            seleccionarFila(this, capacitacion);
                        });

                        tbody.appendChild(row);
                    });
                } else {
                    alert("No se encontraron capacitaciones activas.");
                }
            })
            .catch(error => console.error('Error:', error));
    });

    // Función para manejar la selección de la fila
    function seleccionarFila(fila, capacitacion) {
        // Eliminar la clase de selección de cualquier fila previamente seleccionada
        let filas = document.querySelectorAll('#tablaCapacitaciones tbody tr');
        filas.forEach(f => f.classList.remove('fila-seleccionada'));

        // Agregar la clase de selección a la fila actual
        fila.classList.add('fila-seleccionada');

        // Almacenar el ID de la capacitación en un input oculto
        document.getElementById("capaId").value = capacitacion.capa_id;

        // Opcional: Mostrar un mensaje de confirmación (puedes eliminarlo si no lo necesitas)
        console.log(`Capacitación seleccionada: ${capacitacion.capa_titulo}`);
    }





});