const tableLista = document.querySelector("#tableListaProductos tbody");
const tblPendientes = document.querySelector('#tblPendientes');
const btnFinalizarPago = document.querySelector("#btnFinalizarPago");
let productosjson = [];
const estadoEnviado = document.querySelector('#estadoEnviado');
const estadoProceso = document.querySelector('#estadoProceso');
const estadoCompletado = document.querySelector('#estadoCompletado');
document.addEventListener("DOMContentLoaded", function() {
    if (tableLista) {
        getListaProductos();
    }
    //cargar datos pendientes con DataTables
    $('#tblPendientes').DataTable({
        ajax: {
            url: base_url + 'clientes/listarPendientes',
            dataSrc: ''
        },
        columns: [
            { data: 'id_transaccion' },
            { data: 'monto' },
            { data: 'fecha' },
            { data: 'accion' }
        ],
        language,
        dom,
        buttons

    });
});

function generarMensajeCarrito() {
    const listaCarrito = JSON.parse(localStorage.getItem('listaCarrito')) || [];
    if (listaCarrito.length === 0) {
        Swal.fire("Aviso", "El carrito está vacío.", "warning");
        return;
    }

    const url = base_url + 'principal/listaProductos';
    const http = new XMLHttpRequest();
    http.open('POST', url, true);
    http.setRequestHeader('Content-Type', 'application/json');
    
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            let mensaje = "Hola! 👋\n\n";
            mensaje += "Quiero confirmar mi pedido:\n\n";

            for (let i = 0; i < res.productos.length; i++) {
                const producto = res.productos[i];
                mensaje += `${i + 1}. ${producto.nombre}\n`;
                mensaje += `Cantidad: ${producto.cantidad}\n`;
                mensaje += `Talla: ${listaCarrito[i].talla}\n`;
                mensaje += `Precio: ${producto.precio} ${res.moneda}\n\n`;
            }
            mensaje += `Total a pagar: ${res.total} ${res.moneda}\n\n`;
            mensaje += "¿Cuál es el siguiente paso? Por favor, indícame qué métodos de pago aceptan. ¡Gracias! 😊";
            
            // Reemplazar saltos de línea con %0A para WhatsApp
            mensaje = mensaje.replace(/\n/g, '%0A');

            // Eliminar cualquier espacio en el número de teléfono
            let telefono = "+573115588268".replace(/\s+/g, '');
            
            // Probar primero con el protocolo de aplicación (más confiable en móviles)
            let urlWhatsApp = `whatsapp://send/?phone=${telefono}&text=${mensaje}`;
            
            // Como alternativa, también podemos intentar con la URL web
            let urlWhatsAppWeb = `https://web.whatsapp.com/send?phone=${telefono}&text=${mensaje}`;
            
            // En dispositivos móviles, preferir la aplicación; en desktop, preferir la web
            if (/Android|iPhone|iPad|iPod|Mobile/i.test(navigator.userAgent)) {
                window.location.href = urlWhatsApp;
            } else {
                window.open(urlWhatsAppWeb, '_blank');
            }
        } else if (this.readyState == 4) {
            // Manejar error en la solicitud
            Swal.fire("Error", "No se pudo generar el mensaje del carrito", "error");
        }
    };
    
    // Enviar la solicitud después de configurar el callback
    http.send(JSON.stringify(listaCarrito));
}

btnFinalizarPago.addEventListener('click', function() {
    // Obtener el carrito actual directamente desde localStorage
    const carrito = localStorage.getItem('listaCarrito') ? JSON.parse(localStorage.getItem('listaCarrito')) : [];
    if (carrito.length === 0) {
        Swal.fire('Aviso?', 'El carrito está vacío. Agrega al menos un producto antes de finalizar tu pedido.', 'warning');
        return;
    }

    Swal.fire({
        title: '¿Estás seguro?',
        text: "Este proceso es irreversible",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, confirmar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            generarMensajeCarrito();
            registrarPedido();
        }
    });
});

function getListaProductos() {
    const miTalla = JSON.parse(localStorage.getItem('listaCarrito'));
    let html = '';
    const url = base_url + 'principal/listaProductos';
    const http = new XMLHttpRequest();
    http.open('POST', url, true);
    http.send(JSON.stringify(listaCarrito));
    http.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            if (res.total > 0) {
                // Reemplazar el forEach con un bucle for
                for (let i = 0; i < res.productos.length; i++) {
                    const producto = res.productos[i];
                    html += `<tr>
                        <td>
                            <img class="img-thumbnail rounded-circle" src="${producto.imagen}" alt="" width="100">
                        </td>
                        <td>${producto.nombre}</td>
                        <td><span class="badge bg-warning">${res.moneda + ' ' + producto.precio}</span></td>
                        <td>${miTalla[i].talla}</td>
                        <td><span class="badge bg-primary"><h3>${producto.cantidad}</h3></span></td>
                        <td>${producto.subTotal}</td>
                    </tr>`;
                    
                }
                
                tableLista.innerHTML = html;
                document.querySelector('#totalProducto').textContent = 'TOTAL A PAGAR: ' + res.total + ' ' + res.moneda;
            } else {
                tableLista.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center">CARRITO VACIO</td>
                </tr>
                `;
            }
        }
    }
}



function registrarPedido() {
    const url = base_url + 'clientes/registrarPedido';
    const http = new XMLHttpRequest();
    http.open('POST', url, true);

    // Obtener la lista de productos y el total
    const listaCarrito = JSON.parse(localStorage.getItem('listaCarrito')) || [];
    const httpListaProductos = new XMLHttpRequest();
    httpListaProductos.open('POST', base_url + 'principal/listaProductos', true);
    httpListaProductos.setRequestHeader('Content-Type', 'application/json');
    httpListaProductos.send(JSON.stringify(listaCarrito));

    httpListaProductos.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            http.send(JSON.stringify({
                pedidos: {
                    total: res.total
                },
                productos: listaCarrito
            }));

            http.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);
                    const res = JSON.parse(this.responseText);
                    Swal.fire("Aviso?", res.msg, res.icono);
                    if (res.icono == 'success') {
                        localStorage.removeItem('listaCarrito');
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    }
                }
            }
        }
    }
}



function verPedido(idPedido) {
    estadoEnviado.classList.remove('bg-info');
    estadoProceso.classList.remove('bg-info');
    estadoCompletado.classList.remove('bg-info');
    const mPedido = new bootstrap.Modal(document.getElementById('modalPedido'));
    const url = base_url + 'clientes/verPedido/' + idPedido;
    const http = new XMLHttpRequest();
    http.open('GET', url, true);
    http.send();
    http.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            let html = '';
            if (res.pedido.proceso == 1) {
                estadoEnviado.classList.add('bg-info');
            } else if (res.pedido.proceso == 2) {
                estadoProceso.classList.add('bg-info');
            } else {
                estadoCompletado.classList.add('bg-info');
            }
            res.productos.forEach(row => {
                let subTotal = parseFloat(row.precio) * parseInt(row.cantidad);
                let precioFormato = (parseFloat(row.precio));
                let subTotalFormato = (subTotal);
                html += `<tr>
                    <td>${row.producto}</td>
                    <td><span class="badge bg-warning">${res.moneda + ' ' + precioFormato}</span></td>
                    <td><span class="badge bg-primary">${row.cantidad}</span></td>
                    <td>${subTotalFormato}</td>
                </tr>`;
            });
            document.querySelector('#tablePedidos tbody').innerHTML = html;
            mPedido.show();
        }
    }

}

//     const listaCarrito = JSON.parse(localStorage.getItem('listaCarrito')) || [];
//     let total = 0;

//     listaCarrito.forEach(item => {
//         const subtotal = item.cantidad * item.precio;
//         total += subtotal;
//     });

//     return total;

    

//     // const url = base_url + 'principal/listaProductos';
//     // const http = new XMLHttpRequest();
//     // http.open('POST', url, true);
//     // http.setRequestHeader('Content-Type', 'application/json');
//     // http.send(JSON.stringify(listaCarrito));

//     // http.onreadystatechange = function () {
//     //     if (this.readyState == 4 && this.status == 200) {
//     //         const res = JSON.parse(this.responseText);
//     //         mensaje += res.total;
//     //     }
//     // }

// }


// sb-j6jdb7896999@personal.example.com
// e8O2lR-I


//sb-y3jfn7901325@business.example.com
//Amqes3]/