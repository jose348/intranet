document.addEventListener("DOMContentLoaded", () => {
    const btnCargar = document.getElementById("btn-cargar");
    const fechaInput = document.getElementById("fecha-select");

    // Inicializar Flatpickr con solo mes y año
    flatpickr(fechaInput, {
        plugins: [
            new monthSelectPlugin({
                shorthand: true, // Mostrar nombres de meses abreviados
                dateFormat: "Y-m", // Formato de salida: Año-Mes (YYYY-MM)
                altFormat: "F Y", // Formato visual: Mes Año (ejemplo: Enero 2024)
            }),
        ],
        locale: "es", // Idioma en español
    });

    btnCargar.addEventListener("click", () => {
        const fechaValor = fechaInput.value;

        if (!fechaValor) {
            alert("Por favor, selecciona una fecha válida en el formato YYYY-MM.");
            return;
        }

        const [anio, mes] = fechaValor.split("-");
        const mesNombre = convertirMes(mes);

        cargarAsistencias(anio, mesNombre);
    });
});

function convertirMes(mes) {
    const meses = [
        "ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO",
        "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE"
    ];
    return meses[parseInt(mes, 10) - 1];
}

function cargarAsistencias(anio, mes) {
    fetch(`/Intranet/controller/asistencia.php?op=listar_por_mes&anio=${anio}&mes=${mes}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById("tabla-asistencias-body");
            tbody.innerHTML = "";

            if (data.error) {
                const tr = document.createElement("tr");
                tr.className = "no-data";
                tr.innerHTML = `<td colspan="3">${data.error}</td>`;
                tbody.appendChild(tr);
                return;
            }

            if (data.length === 0) {
                const tr = document.createElement("tr");
                tr.className = "no-data";
                tr.innerHTML = `<td colspan="3">No hay registros disponibles para este mes.</td>`;
                tbody.appendChild(tr);
                return;
            }

            data.forEach(row => {
                const tr = document.createElement("tr");

                // Asignar clase 'verde' para entradas y 'rojo' para salidas
                const entradaClass = row.entrada === "Sin registro" ? "amarillo" : "verde";
                const salidaClass = row.salida === "Sin registro" ? "amarillo" : "rojo";

                tr.innerHTML = `
                    <td>${row.dia}</td>
                    <td class="${entradaClass}">${row.entrada}</td>
                    <td class="${salidaClass}">${row.salida}</td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(error => {
            console.error("Error al cargar los datos:", error);
            alert("Ocurrió un error al cargar los datos.");
        });



}