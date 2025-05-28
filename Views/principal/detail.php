<?php include_once 'Views/template/header-secundario.php'; ?>

<!-- Open Content -->
<section class="bg-light">
    <div class="container pb-5">
        <div class="row">
            <div class="col-lg-5 mt-5">
                <div class="card mb-3">
                    <img class="card-img img-fluid" src="<?php echo BASE_URL . $data['producto']['imagen']; ?>" alt="Card image cap" id="product-detail">
                </div>
            </div>
            <div class="col-lg-7 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h1 class="h2"><?php echo $data['producto']['nombre']; ?></h1>
                        <p class="h3 py-2">$ <?php echo number_format($data['producto']['precio'], 0, '', '.') . ' ' . MONEDA; ?></p>

                        <ul class="list-inline">
                            <li class="list-inline-item">
                                <h6>Categoria</h6>
                            </li>
                            <li class="list-inline-item">
                                <p class="text-muted"><strong><?php echo $data['producto']['categoria']; ?></strong></p>
                            </li>
                        </ul>

                        <h6>Descripción:</h6>
                        <p><?php echo $data['producto']['descripcion']; ?></p>

                        <form action="" method="GET">
                            <input type="hidden" id="idProducto" value="<?php echo $data['producto']['id']; ?>">
                            <div class="row">
                                <div class="col-auto">
                                    <h2>Tallas Disponibles</h2>
                                    <select id="idTalla">
                                        <?php if (isset($data['tallas']) && !empty($data['tallas'])): ?>
                                            <?php foreach ($data['tallas'] as $talla): ?>
                                                <option value="<?php echo $talla['talla']; ?>"><?php echo $talla['talla']; ?></option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="">No hay tallas disponibles</option>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <div class="col-auto">
                                    <h2>Cantidad</h2>
                                    <ul class="list-inline pb-3">
                                        <li class="list-inline-item text-right">
                                            <input type="hidden" id="product-quanity" value="1">
                                        </li>
                                        <li class="list-inline-item"><span class="btn btn-success" id="btn-minus">-</span></li>
                                        <li class="list-inline-item"><span id="var-value">1</span></li>
                                        <li class="list-inline-item"><span class="btn btn-success" id="btn-plus">+</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row pb-3">
                                <div class="col d-grid">
                                    <button type="button" class="btn btn-success btn-lg" id="btnAddCart">Añadir al carrito</button>
                                </div>
                            </div>
                            <div class="row pb-3">
                                <div class="col d-grid">
                                    <button type="button" class="btn btn-success btn-lg" id="btnAddWish">Lista de deseos</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Start Article -->
<section class="py-5">
    <div class="container">
        <div class="row text-left p-2 pb-3">
            <h4 class="text-success" style="font-size: 28px; font-weight: bold;">Productos Relacionados</h4>
        </div>
        
        <!--Start Carousel Wrapper-->
        <div id="carousel-related-product">
            <?php foreach ($data['relacionados'] as $producto) { ?>
                <div class="p-2 pb-3">
                    <div class="product-wap card rounded-0">
                        <div class="card rounded-0 card-prod position-relative">
                            <img class="card-img rounded-0 img-fluid" src="<?php echo BASE_URL . $producto['imagen']; ?>">
                            <div class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                <ul class="list-unstyled">
                                    <li>
                                        <a class="eye-link" href="<?php echo BASE_URL . 'principal/detail/' . $producto['id']; ?>">
                                            <i class="fas fa-eye eye-icon"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <h4 href="<?php echo BASE_URL . 'principal/detail/' . $producto['id']; ?>" class="h3 text-decoration-none"><?php echo $producto['nombre']; ?></h4>
                            <p class="text-center mb-0">$ <?php echo number_format($producto['precio'], 0, '', '.') . ' ' . MONEDA; ?></p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<!-- CSS personalizado -->
<style>
  /* Título "Productos Relacionados" en negro */
  .text-success {
    color: #000 !important;
  }

  /* Ocultar el overlay por defecto */
  .product-overlay {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: rgba(0, 0, 0, 0); /* inicial: transparente */
    transition: background-color 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
    opacity: 0;
  }

  /* Mostrar overlay oscuro al hacer hover */
  .card-prod:hover .product-overlay {
    background-color: rgba(0, 0, 0, 0.5);
    opacity: 1;
    pointer-events: auto;
  }

  /* Estilo del ícono de ojo */
  .eye-icon {
    font-size: 30px;
    color: white;
    opacity: 0;
    transform: scale(0.9);
    transition: opacity 0.3s ease, transform 0.3s ease;
  }

  .card-prod:hover .eye-icon {
    opacity: 1;
    transform: scale(1.1);
  }

  .eye-icon:hover {
    transform: scale(1.3);
    cursor: pointer;
  }

  /* Nuevo: contenedor circular para el icono */
  .eye-link {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    width: 60px;
    height: 60px;
    text-decoration: none;
    transition: background-color 0.3s ease, transform 0.3s ease;
  }

  .eye-link:hover {
    background-color: rgba(255, 255, 255, 0.3);
    transform: scale(1.1);
  }

  /* Padding para los títulos de las tarjetas */
  .product-wap .card-body h4 {
  font-size: 16px;
  padding: 10px 0;
  line-height: 1.2;
}

  /* Otros estilos ya existentes */
  .col-auto {
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
    max-width: 300px;
    margin: 0 auto;
    margin-bottom: 20px;
  }

  .col-auto h2 {
    font-family: 'Arial', sans-serif;
    font-size: 24px;
    color: #333;
    margin-bottom: 20px;
  }

  .col-auto select {
    font-family: 'Arial', sans-serif;
    font-size: 16px;
    color: #333;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #fff;
    width: 100%;
    box-sizing: border-box;
    transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
  }

  .col-auto select:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
  }

  .product-wap img {
        max-height: 200px; /* Ajustar según sea necesario */
        object-fit: contain;
        width: 100%;
    }

    /* Establecer un tamaño fijo para el cuerpo de la tarjeta */
    .product-wap .card-body {
        height: 100px; /* Ajustar según sea necesario */
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
</style>

<?php include_once 'Views/template/footer-secundario.php'; ?>

<script src="<?php echo BASE_URL; ?>assets/js/modulos/detail.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/modulos/listaDeseo.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/slick.min.js"></script>
<script>
    $('#carousel-related-product').slick({
        infinite: true,
        arrows: false,
        slidesToShow: 4,
        slidesToScroll: 3,
        dots: true,
        responsive: [
            { breakpoint: 1024, settings: { slidesToShow: 3, slidesToScroll: 3 } },
            { breakpoint: 600, settings: { slidesToShow: 2, slidesToScroll: 3 } },
            { breakpoint: 480, settings: { slidesToShow: 2, slidesToScroll: 3 } }
        ]
    });
</script>
</body>
</html>
