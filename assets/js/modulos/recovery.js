///NEW!!!
const frmRecovery = document.querySelector("#frmRecovery");
const emailRecovery = document.querySelector("#email");
document.addEventListener("DOMContentLoaded", function() {
    // Verifica que el formulario exista en la vista actual
    
        frmRecovery.addEventListener("submit", function(e) {
            e.preventDefault(); 
            if (emailRecovery.value == "") {
                alertas("El correo es requerido", "warning");
            } else {
                let data = new FormData(this);
                const url = base_url + "admin/sendRecovery";
                const http = new XMLHttpRequest();
                http.open("POST", url, true);
                http.send(data);
                http.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(this.responseText);
                        const res = JSON.parse(this.responseText);
                        if (res.icono === "success") {
                            setTimeout(() => {
                                window.location = base_url + "admin";
                            }, 2000);
                        }
                        alertas(res.msg, res.icono);
                    }
                }
            }
        });
    
});

// Función para mostrar alertas con SweetAlert
function alertas(msg, icono) {
    Swal.fire("Aviso?", msg.toUpperCase(), icono);
}