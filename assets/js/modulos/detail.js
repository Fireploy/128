const btnAddCart =  document.querySelector('#btnAddCart');
const cantidad =  document.querySelector('#product-quanity');
const idProducto =  document.querySelector('#idProducto');
const talla =  document.querySelector('#idTalla');
//NEW!!!
const btnAddWish = document.querySelector('#btnAddWish');

document.addEventListener("DOMContentLoaded", function () {
    //Botón para el carrito
    btnAddCart.addEventListener('click', function () {
        agregarCarrito(idProducto.value, cantidad.value, talla.value);
    });
});
document.addEventListener("DOMContentLoaded", function () {
    //Botón para la lista de deseos
    btnAddWish.addEventListener('click', function () {
        agregarDeseo(idProducto.value, talla.value);
    });
});