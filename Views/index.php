<?php include_once 'Views/template/header-principal.php'; ?>

<!-- fashion section start -->
<?php foreach ($data['categorias'] as $categoria): ?>
  <section class="fashion_section py-5" style="background-color: #fff;">
    <div class="container" id="categoria_<?php echo $categoria['id']; ?>">
      <h1 class="fashion_taital text-uppercase pb-3"><?php echo htmlspecialchars($categoria['categoria'], ENT_QUOTES); ?></h1>
      <p class="mb-4"><?php echo htmlspecialchars($categoria['descripcion'], ENT_QUOTES); ?></p>
      <div class="row g-3 <?php echo (count($categoria['productos']) > 0) ? 'multiple-items' : ''; ?>">
        <?php foreach ($categoria['productos'] as $producto): ?>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="card-prod bg-white h-100 d-flex flex-column position-relative shadow-sm">
              <div class="image-wrapper text-center">
                <img
                  data-lazy="<?php echo BASE_URL . $producto['imagen']; ?>"
                  alt="<?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES); ?>"
                  class="img-fluid product-image mx-auto"
                />
              </div>
              <div class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                <a
                  class="btn btn-success text-white"
                  href="<?php echo BASE_URL . 'principal/detail/' . $producto['id']; ?>"
                  title="Ver detalles de <?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES); ?>"
                >
                  <i class="fas fa-eye"></i>
                </a>
              </div>
              <div class="card-body d-flex flex-column justify-content-between text-center pt-3">
                <h5 class="product-name mb-2 text-truncate" title="<?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES); ?>">
                  <?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES); ?>
                </h5>
                <p class="product-price mb-0">
                  <span class="fw-bold">$<?php echo number_format($producto['precio'], 0, '', '.'); ?> <?php echo MONEDA; ?></span>
                </p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
<?php endforeach; ?>

<style>
.fashion_section { background-color: #fff; }
.card-prod {
  background-color: #fff;
  border: 1px solid #e0e0e0;
  margin: 1rem;
  overflow: hidden;
  transition: transform 0.3s ease;
}
.card-prod:hover {
  transform: scale(1.02);
}
.image-wrapper {
  height: 200px;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  padding: .75rem;
}
.product-image {
  max-height: 100%;
  object-fit: contain;
  width: auto;
}
.product-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  opacity: 0;
  transition: opacity 0.3s ease;
  z-index: 1;
}
.card-prod:hover .product-overlay {
  opacity: 1;
}
.product-name {
  font-size: 1rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.fashion_taital {
  font-size: 1.75rem;
}
</style>

<?php include_once 'Views/template/footer-principal.php'; ?>
<script src="<?php echo BASE_URL . 'assets/js/helper.js'; ?>"></script>
<script>
  $('.multiple-items').slick({
    lazyLoad: 'ondemand',
    dots: true,
    infinite: false,
    speed: 300,
    slidesToShow: 4,
    slidesToScroll: 1,
    autoplay: true,
    responsive: [
      { breakpoint: 1024, settings: { slidesToShow: 3, slidesToScroll: 1, infinite: true, dots: true } },
      { breakpoint: 600, settings: { slidesToShow: 2, slidesToScroll: 1 } },
      { breakpoint: 480, settings: { slidesToShow: 1, slidesToScroll: 1 } }
    ]
  });
</script>
