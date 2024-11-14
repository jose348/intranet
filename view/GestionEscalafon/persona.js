document.addEventListener("DOMContentLoaded", function() {
    const url = `../../controller/persona.php?op=mostrar`;
    const situacionLaboralUrl = `../../controller/persona.php?op=situacion_laboral`;

    // Función para cargar la información personal
    function cargarInformacionPersonal() {
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error en la solicitud: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    console.error("Error en la respuesta:", data.error);
                    document.getElementById("userName").textContent = "Error: " + data.error;
                } else {
                    document.getElementById("userName").textContent = `${data.pers_apelpat ?? ""} ${data.pers_apelmat ?? ""} ${data.pers_nombre ?? ""}`;
                    document.getElementById("nombre").textContent = data.pers_nombre ? ? "N/A";
                    document.getElementById("apellido").textContent = `${data.pers_apelpat ?? ""} ${data.pers_apelmat ?? ""}`;
                    document.getElementById("dni").textContent = data.pers_dni ? ? "N/A";
                    document.getElementById("fechaNacimiento").textContent = data.pers_fechanac ? ? "N/A";
                    document.getElementById("estadoCivil").textContent = data.esci_denom ? ? "N/A";
                    document.getElementById("telefijo").textContent = data.pers_telefijo ? ? "N/A";
                    document.getElementById("celu01").textContent = data.pers_celu01 ? ? "N/A";
                    document.getElementById("celu02").textContent = data.pers_celu02 ? ? "N/A";
                    document.getElementById("emailp").textContent = data.pers_emailp ? ? "N/A";
                    document.getElementById("emailm").textContent = data.pers_emailm ? ? "N/A";
                    document.getElementById("direccion").textContent = data.pers_direccion ? ? "N/A";

                    const userPhoto = document.getElementById("userPhoto");
                    userPhoto.src = data.pers_foto ? `data:image/jpeg;base64,${data.pers_foto}` : "../../images/default.jpg";
                }
            })
            .catch(error => {
                console.error("Error al obtener los datos:", error);
                document.getElementById("userName").textContent = "Error al obtener los datos.";
            });
    }

    // Función para cargar la situación laboral
    function cargarSituacionLaboral() {
        fetch(situacionLaboralUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error en la solicitud de situación laboral: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (!data.error) {
                    document.getElementById("dependencia").textContent = data.depe_denominacion ? ? "N/A";
                    document.getElementById("cargo").textContent = data.carg_denominacion ? ? "N/A";
                    document.getElementById("tipoEmpleado").textContent = data.tiem_nombre ? ? "N/A";
                    document.getElementById("condicionLaboral").textContent = data.cola_denominacion ? ? "N/A";
                    document.getElementById("grupoOcupacional").textContent = data.gpoc_denominacion ? ? "N/A";
                }
            })
            .catch(error => {
                console.error("Error al obtener los datos de situación laboral:", error);
            });
    }

    function toggleSection(header) {
        const content = header.nextElementSibling;
        content.style.display = content.style.display === "none" ? "block" : "none";
        header.classList.toggle("collapsed");
    }

    cargarInformacionPersonal();
    cargarSituacionLaboral();

    document.querySelectorAll(".info-header").forEach(header => {
        header.addEventListener("click", function() {
            toggleSection(this);
        });
    });

    document.querySelectorAll(".info-content").forEach(content => {
        content.style.display = "none";
    });
});