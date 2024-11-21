document.addEventListener("DOMContentLoaded", () => {
    const uploadForm = document.getElementById("uploadForm");
    const fileInput = document.getElementById("fileInput");
    const fileNameDisplay = document.getElementById("fileName");
    const fileTableBody = document.getElementById("fileTableBody");

    /**
     * Actualizar el texto con el nombre del archivo seleccionado
     */
    fileInput.addEventListener("change", () => {
        fileNameDisplay.textContent = fileInput.files.length > 0 ?
            fileInput.files[0].name :
            "Ningún archivo seleccionado";
    });

    /**
     * Cargar y mostrar los archivos en la tabla
     */
    function cargarArchivos() {
        fetch("../../controller/documentos.php?op=listar_archivos")
            .then(response => {
                if (!response.ok) {
                    throw new Error("Error en la respuesta del servidor");
                }
                return response.text(); // Cambiado a `.text()` para verificar si el JSON es válido
            })
            .then(text => {
                try {
                    const data = JSON.parse(text); // Intenta parsear el texto como JSON
                    if (data.error) {
                        Swal.fire("Error", data.error, "error");
                        return;
                    }

                    // Reiniciar tabla si ya existe
                    if ($.fn.DataTable.isDataTable('#fileTable')) {
                        $('#fileTable').DataTable().clear().destroy();
                    }

                    fileTableBody.innerHTML = "";

                    // Si no hay archivos, mostrar mensaje vacío
                    if (data.length === 0) {
                        fileTableBody.innerHTML = `<tr><td colspan="6">No hay archivos subidos.</td></tr>`;
                    } else {
                        data.forEach((file, index) => agregarFilaArchivo(file, index + 1));
                    }

                    // Reinicializar DataTable
                    $('#fileTable').DataTable({
                        paging: true,
                        searching: false,
                        info: true,
                        lengthChange: false,
                        pageLength: 5,
                        language: {
                            paginate: {
                                previous: "Anterior",
                                next: "Siguiente"
                            },
                            emptyTable: "No hay datos disponibles en la tabla",
                            info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                            infoEmpty: "Mostrando 0 a 0 de 0 entradas"
                        }
                    });
                } catch (error) {
                    console.error("Error al parsear JSON:", error, text);
                    Swal.fire("Error", "Error al procesar los datos del servidor.", "error");
                }
            })
            .catch(error => {
                console.error("Error al cargar archivos:", error);
                Swal.fire("Error", "No se pudo conectar con el servidor.", "error");
            });
    }

    /**
     * Agregar una fila a la tabla
     */
    function agregarFilaArchivo(file, index) {
        const baseURL = "https://www.munichiclayo.gob.pe/Intranet/";
        const fullPath = baseURL + file.dope_ruta;

        const row = `
            <tr>
                <td>${index}</td>
                <td>${file.dope_nombre}</td>
                <td>${file.dope_tipo}</td>
                <td>${(file.dope_tamano / 1024).toFixed(2)} KB</td>
                <td>${new Date(file.dope_fecha_subida).toLocaleString()}</td>
                <td>
                    <button class="btn btn-primary btn-sm me-2" onclick="abrirArchivo('${fullPath}')">
                        <i class="fa fa-eye"></i> 
                    </button>
                    <button class="btn btn-danger btn-sm" onclick="eliminarArchivo(${file.dope_id})">
                        <i class="fa fa-trash"></i> 
                    </button>
                </td>
            </tr>`;
        fileTableBody.insertAdjacentHTML("beforeend", row);
    }

    /**
     * Subir el archivo al servidor y recargar la tabla
     */
    uploadForm.addEventListener("submit", (e) => {
        e.preventDefault();

        const formData = new FormData();
        const file = fileInput.files[0];

        if (!file) {
            Swal.fire("Advertencia", "Por favor selecciona un archivo.", "warning");
            return;
        }

        formData.append("file", file);

        fetch("../../controller/documentos.php?op=subir_archivo", {
                method: "POST",
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire("Éxito", data.success, "success");
                    fileInput.value = "";
                    fileNameDisplay.textContent = "Ningún archivo seleccionado";
                    cargarArchivos(); // Recargar tabla tras subir archivo
                } else {
                    Swal.fire("Error", data.error, "error");
                }
            })
            .catch(error => {
                console.error("Error al subir archivo:", error);
                Swal.fire("Error", "No se pudo conectar con el servidor.", "error");
            });
    });

    /**
     * Función para abrir el archivo en una nueva pestaña o ventana
     */
    function abrirArchivo(rutaCompleta) {
        if (rutaCompleta) {
            window.open(rutaCompleta, "_blank");
        } else {
            Swal.fire("Error", "No se encontró la ruta del archivo.", "error");
        }
    }

    /**
     * Eliminar un archivo
     */
    function eliminarArchivo(dope_id) {
        Swal.fire({
            title: "¿Estás seguro?",
            text: "El archivo será eliminado.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("../../controller/documentos.php?op=eliminar_archivo", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                        body: `dope_id=${dope_id}`,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire("Eliminado", data.success, "success");
                            cargarArchivos();
                        } else {
                            Swal.fire("Error", data.error, "error");
                        }
                    })
                    .catch(error => {
                        console.error("Error al eliminar archivo:", error);
                    });
            }
        });
    }

    // Asignar las funciones globalmente
    window.abrirArchivo = abrirArchivo;
    window.eliminarArchivo = eliminarArchivo;

    // Cargar archivos al iniciar
    cargarArchivos();
});