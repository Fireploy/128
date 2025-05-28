const btnAddcarrito = document.querySelectorAll(".btnAddcarrito");
const btnCarrito = document.querySelector("#btnCantidadCarrito");
const verCarrito = document.querySelector('#verCarrito');
const tableListaCarrito = document.querySelector('#tableListaCarrito tbody');

const btnAddDeseo = document.querySelectorAll(".btnAddDeseo");
const btnDeseo = document.querySelector('#btnCantidadDeseo');

let listaDeseo, listaCarrito;
document.addEventListener("DOMContentLoaded", function () {
    //NEW!!!
    if (localStorage.getItem('listaDeseo') != null) {
        listaDeseo = JSON.parse(localStorage.getItem('listaDeseo'));
    }
    if (localStorage.getItem("listaCarrito") != null) {
        listaCarrito = JSON.parse(localStorage.getItem("listaCarrito"));
    }
    //NEW!!!
    for (let i = 0; i < btnAddDeseo.length; i++) {
        btnAddDeseo[i].addEventListener('click', function () {
            let idProducto = btnAddDeseo[i].getAttribute('prod');
            agregarDeseo(idProducto);
        })        
    }
    for (let i = 0; i < btnAddcarrito.length; i++) {
        btnAddcarrito[i].addEventListener("click", function (e) {
            e.preventDefault();
            let idProducto = btnAddcarrito[i].getAttribute("prod");
            agregarCarrito(idProducto, 1);
        });
    }
    cantidadDeseo();
    cantidadCarrito();

    verCarrito.addEventListener('click', function () {
        getListaCarrito();
        $('#modalCarrito').modal('show')
    })
});

//NEW!!!
//agregar productos a la lista de deseos
function agregarDeseo(idProducto, talla) {
    if (localStorage.getItem('listaDeseo') == null) {
        listaDeseo = [];
    } else {
        let listaExiste = JSON.parse(localStorage.getItem('listaDeseo'));
        for (let i = 0; i < listaExiste.length; i++) {
            if (listaExiste[i]['idProducto'] == idProducto) {
                Swal.fire(
                    'Aviso?',
                    'EL PRODUCTO YA ESTÁ EN TU LISTA DE DESEO',
                    'warning'
                );
                return;
            }            
        }
        listaDeseo.concat(localStorage.getItem('listaDeseo'));
    }

    listaDeseo.push({
        idProducto: idProducto,
        cantidad: 1,
        talla: talla,
    });
    localStorage.setItem('listaDeseo', JSON.stringify(listaDeseo));
    Swal.fire('Aviso?', 'PRODUCTO AGREGADO A LA LISTA DE DESEOS', 'success');
    cantidadDeseo();
}

function cantidadDeseo() {
    let listas = JSON.parse(localStorage.getItem("listaDeseo"));
    if (listas != null) {
        btnDeseo.textContent = listas.length;
    } else {
        btnDeseo.textContent = 0;
    }
}

//NEW!!!
//agregar productos al carrito
function agregarCarrito(idProducto, cantidad, talla, accion = false) {
    if (localStorage.getItem("listaCarrito") == null) {
        listaCarrito = [];
    } else {
        let listaExiste = JSON.parse(localStorage.getItem("listaCarrito"));
        for (let i = 0; i < listaExiste.length; i++) {
            if (listaExiste[i]["idProducto"] == idProducto && listaExiste[i]["talla"] == talla) {
                alertaPerzanalizada("EL PRODUCTO YA ESTA AGREGADO CON ESTA TALLA", "warning");
                return;
            }
        }
        listaCarrito.concat(localStorage.getItem("listaCarrito"));
    }    
    listaCarrito.push({
        idProducto: idProducto,
        cantidad: cantidad,
        talla: talla,
    });

    localStorage.setItem("listaCarrito", JSON.stringify(listaCarrito));
    alertaPerzanalizada("PRODUCTO AGREGADO AL CARRITO", "success");
    
    if (accion) {
        eliminarListaDeseo(idProducto, false);
    }
    
    cantidadCarrito();
}

function cantidadCarrito() {
    let listas = JSON.parse(localStorage.getItem("listaCarrito"));
    if (listas != null) {
        btnCarrito.textContent = listas.length;
    } else {
        btnCarrito.textContent = 0;
    }
}

//ver carrito
function getListaCarrito() {
    const miTalla = JSON.parse(localStorage.getItem('listaCarrito'));
    const url = base_url + 'principal/listaProductos';
    const http = new XMLHttpRequest();
    http.open('POST', url, true);
    http.send(JSON.stringify(listaCarrito));
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            let html = '';

            // Reemplazar el forEach con un bucle for
            for (let i = 0; i < res.productos.length; i++) {
                const producto = res.productos[i];
                html += `<tr>
                    <td>
                    <img class="img-thumbnail" src="${base_url + producto.imagen}" alt="" width="100">
                    </td>
                    <td>${producto.nombre}</td>
                    <td><span class="badge bg-warning">${res.moneda + ' ' + producto.precio}</span></td>
                    <td>${miTalla[i].talla}</td>
                    <td width="100">
                    <input type="number" min="0" class="form-control agregarCantidad" id="${producto.id}" value="${producto.cantidad}">
                    </td>
                    <td>${producto.subTotal}</td>
                    <td>
                    <button class="btn btn-danger btnDeletecart" type="button" prod="${producto.id}"><i class="fas fa-times-circle"></i></button>
                    </td>
                </tr>`;
            }

            tableListaCarrito.innerHTML = html;
            document.querySelector('#totalGeneral').textContent = 'Total: ' + res.total + ' ' + res.moneda;
            btnEliminarCarrito();
            cambiarCantidad();
        }
    }
}

function btnEliminarCarrito() {
    let listaEliminar = document.querySelectorAll('.btnDeletecart');
    for (let i = 0; i < listaEliminar.length; i++) {
        listaEliminar[i].addEventListener('click', function () {
            let idProducto = listaEliminar[i].getAttribute('prod');
            eliminarListaCarrito(idProducto);
        })
    }
}

function eliminarListaCarrito(idProducto) {
    for (let i = 0; i < listaCarrito.length; i++) {
        if (listaCarrito[i]['idProducto'] == idProducto) {
            listaCarrito.splice(i, 1);
        }
    }
    localStorage.setItem('listaCarrito', JSON.stringify(listaCarrito));
    getListaCarrito();
    cantidadCarrito();
    alertaPerzanalizada("PRODUCTO ELIMINADO DEL CARRITO", "success")
}
//cambiar la cantidad
function cambiarCantidad() {
    let listaCantidad = document.querySelectorAll('.agregarCantidad');
    for (let i = 0; i < listaCantidad.length; i++) {
        listaCantidad[i].addEventListener('change', function () {
            let idProducto = listaCantidad[i].id;
            let cantidad = listaCantidad[i].value
            incrementarCantidad(idProducto, cantidad);
        })
    }
}

function incrementarCantidad(idProducto, cantidad) {
    for (let i = 0; i < listaCarrito.length; i++) {
        if (listaCarrito[i]['idProducto'] == idProducto) {
            listaCarrito[i].cantidad = cantidad;
        }
    }
    localStorage.setItem('listaCarrito', JSON.stringify(listaCarrito));
}