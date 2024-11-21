document.addEventListener("DOMContentLoaded", function() {
            const url = "../../controller/boleta.php?op=listar";
            const basePath = "http://10.10.10.16/sisRemuneracion/public/doc/"; // Cambiar al dominio correcto
            let currentPage = 1;
            const recordsPerPage = 5;
            let allData = [];

            // Función principal para cargar boletas
            function cargarBoletas() {
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            mostrarError("No se encontraron boletas para el DNI especificado.");
                            return;
                        }

                        allData = data; // Guardamos todos los datos para búsquedas y paginación
                        mostrarPagina(currentPage);
                    })
                    .catch(error => {
                        console.error("Error al cargar las boletas:", error);
                        mostrarError("Ocurrió un error al cargar las boletas.");
                    });
            }

            // Función para mostrar la página actual
            function mostrarPagina(page) {
                const tbody = document.querySelector("#boletasTable tbody");
                tbody.innerHTML = "";

                // Filtrar por año ingresado
                const searchYear = document.getElementById("searchYear").value.trim();
                const filteredData = searchYear ?
                    allData.filter(boleta => boleta.anio.includes(searchYear)) :
                    allData;

                const start = (page - 1) * recordsPerPage;
                const end = start + recordsPerPage;
                const pageData = filteredData.slice(start, end);

                if (pageData.length === 0) {
                    mostrarError("No hay registros disponibles.");
                    return;
                }

                pageData.forEach(boleta => {
                    tbody.appendChild(crearFila(boleta));
                });

                renderPagination(filteredData.length);
            }

            // Función para crear una fila de la tabla
            function crearFila(boleta) {
                const tr = document.createElement("tr");
                const archivoUrl = boleta.bodc_archivo ?
                    boleta.bodc_archivo.replace("/var/www/html/sisRemuneracion/public/doc/", basePath) :
                    null;

                tr.innerHTML = `
            <td>${boleta.anio}</td>
            <td>${getMonthName(parseInt(boleta.mes))}</td>
            <td>${boleta.tiproc_nombre}</td>
            <td>
                ${archivoUrl 
                    ? `<a href="#" class="pdf-link" data-ruta="${archivoUrl}">
                           <i class="fa fa-file-pdf-o" style="color: red;"></i>
                       </a>`
                    : "No disponible"}
            </td>
            <td>
                ${archivoUrl 
                    ? `<a href="${archivoUrl}" download>
                           <i class="fa fa-download" style="color: green;"></i>
                       </a>`
                    : "No disponible"}
            </td>
        `;

        // Añadir evento para abrir PDF
        const pdfLink = tr.querySelector(".pdf-link");
        if (pdfLink) {
            pdfLink.addEventListener("click", function (e) {
                e.preventDefault();
                const ruta = this.getAttribute("data-ruta");
                if (ruta) {
                    window.open(ruta, "_blank");
                } else {
                    alert("El archivo no está disponible.");
                }
            });
        }

        return tr;
    }

    // Función para renderizar los controles de paginación
    function renderPagination(totalRecords) {
        const paginationControls = document.getElementById("paginationControls");
        paginationControls.innerHTML = "";
        const totalPages = Math.ceil(totalRecords / recordsPerPage);

        for (let i = 1; i <= totalPages; i++) {
            const button = document.createElement("button");
            button.textContent = i;
            button.className = `btn btn-sm ${i === currentPage ? "btn-primary" : "btn-light"} mx-1`;

            button.addEventListener("click", function () {
                currentPage = i;
                mostrarPagina(currentPage);
            });

            paginationControls.appendChild(button);
        }
    }

    // Función para mostrar errores en la tabla
    function mostrarError(mensaje) {
        const tbody = document.querySelector("#boletasTable tbody");
        tbody.innerHTML = `<tr><td colspan="5" style="text-align: center;">${mensaje}</td></tr>`;
    }

    // Función para convertir número de mes a nombre
    function getMonthName(monthNumber) {
        const months = [
            "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", 
            "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
        ];
        return months[monthNumber - 1];
    }

    // Evento para búsqueda dinámica
    document.getElementById("searchYear").addEventListener("input", function () {
        currentPage = 1; // Reinicia a la primera página
        mostrarPagina(currentPage);
    });

    // Carga inicial de boletas
    cargarBoletas();
});