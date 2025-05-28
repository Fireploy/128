//NEW!!!
const tableLista = document.querySelector("#tableListaDeseo tbody");
document.addEventListener('DOMContentLoaded', function () {
    getListaDeseo();
});

function getListaDeseo() {
    let listaDeseo = JSON.parse(localStorage.getItem('listaDeseo'));
    const url =  base_url + 'principal/listaDeseo';
    const http = new XMLHttpRequest();
    http.open('POST', url, true);
    http.send(JSON.stringify(listaDeseo));
    http.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            let html = '';
            res.productos.forEach(producto => {
                html += `<tr>
                    <td>
                    <img class="img-thumbnail" src="${base_url + producto.imagen}" alt="" width="100">
                    </td>
                    <td>${producto.nombre}</td>
                    <td>
                    <span class="text-white badge bg-warning">${producto.precio + ' ' + res.moneda}</span>        
                    </td>
                    <td>${producto.talla ? producto.talla : ''}</td>
                    <td>
                    <span class="text-white badge bg-primary">${producto.cantidad}</span>
                    </td>
                    <td>
                    <button class="btn btn-danger btnEliminarDeseo" type="button" prod="${producto.id}"><i class="fas fa-trash"></i></button>
                    <button class="btn btn-primary btnAddCart" type="button" prod="${producto.id}"><i class="fas fa-cart-plus"></i></button>
                    </td>
                </tr>`;
            });
            tableLista.innerHTML = html;
            btnEliminarDeseo();
            btnAgregarProducto();
        }
    }
}

function btnEliminarDeseo() {
    let listaEliminar = document.querySelectorAll('.btnEliminarDeseo');
    for (let i = 0; i < listaEliminar.length; i++) {
        listaEliminar[i].addEventListener('click', function () {
            let idProducto = listaEliminar[i].getAttribute('prod');
            eliminarListaDeseo(idProducto);
        })
    }
}

//eliminar productos desde la lista de deseos
function eliminarListaDeseo(idProducto, mostrarAlerta = true) {
    for (let i = 0; i < listaDeseo.length; i++) {
        if (listaDeseo[i]['idProducto'] == idProducto) {
            listaDeseo.splice(i, 1);
        }
    }
    localStorage.setItem('listaDeseo', JSON.stringify(listaDeseo));
    getListaDeseo();
    cantidadDeseo();
    if (mostrarAlerta) {
        Swal.fire(
            'Aviso?',
            'PRODUCTO ELIMINADO DE TU LISTA',
            'success'
        );
    }
}

//agregar productos desde la lista de deseos
function btnAgregarProducto() {
    let listaAgregar = document.querySelectorAll('.btnAddCart');
    let listaDeseoData = localStorage.getItem('listaDeseo') ? JSON.parse(localStorage.getItem('listaDeseo')) : [];
    for (let i = 0; i < listaAgregar.length; i++) {
        listaAgregar[i].addEventListener('click', function () {
            let idProducto = listaAgregar[i].getAttribute('prod');
            let productoDeseado = listaDeseoData.find(p => p.idProducto == idProducto);
            let tallaDeseo = productoDeseado ? productoDeseado.talla : '';
            agregarCarrito(idProducto, 1, tallaDeseo, true);
        });
    }
}