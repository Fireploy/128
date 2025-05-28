<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- <link rel="apple-touch-icon" sizes="76x76" href="?php echo BASE_URL; ?>assets/admin/img/apple-icon.png">
  <link rel="icon" type="image/png" href="?php echo BASE_URL; ?>assets/admin/img/favicon.png"> -->
  <title>
    <?php echo $data['title']; ?>
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="<?php echo BASE_URL; ?>assets/admin/css/nucleo-icons.css" rel="stylesheet" />
  <link href="<?php echo BASE_URL; ?>assets/admin/css/nucleo-svg.css" rel="stylesheet" />
  <link id="pagestyle" href="<?php echo BASE_URL; ?>assets/admin/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />

  <link rel="apple-touch-icon" sizes="57x57" href="<?php echo BASE_URL; ?>assets/favicon/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="<?php echo BASE_URL; ?>assets/favicon/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="<?php echo BASE_URL; ?>assets/favicon/apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="<?php echo BASE_URL; ?>assets/favicon/apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="<?php echo BASE_URL; ?>assets/favicon/apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="<?php echo BASE_URL; ?>assets/favicon/apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="<?php echo BASE_URL; ?>assets/favicon/apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="<?php echo BASE_URL; ?>assets/favicon/apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo BASE_URL; ?>assets/favicon/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192" href="<?php echo BASE_URL; ?>assets/favicon/android-icon-192x192.png">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo BASE_URL; ?>assets/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="<?php echo BASE_URL; ?>assets/favicon/favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo BASE_URL; ?>assets/favicon/favicon-16x16.png">
  <link rel="manifest" href="<?php echo BASE_URL; ?>assets/favicon/manifest.json">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="<?php echo BASE_URL; ?>assets/favicon/ms-icon-144x144.png">
  <meta name="theme-color" content="#ffffff">
</head>

<body class="bg-gray-200">
  <main class="main-content  mt-0">
    <div class="page-header align-items-start min-vh-100" style="background-image: url('https://images.unsplash.com/photo-1497294815431-9365093b7331?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1950&q=80');">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container my-auto">
        <div class="row">
          <div class="col-lg-4 col-md-8 col-12 mx-auto">
            <div class="card z-index-0 fadeIn3 fadeInBottom">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                  <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Iniciar Sesión</h4>
                </div>
              </div>
              <div class="card-body">
                <form role="form" class="text-start" id="formulario" autocomplete="off">
                  <div class="input-group input-group-outline my-3">
                    <label class="form-label">Correo</label>
                    <input type="email" id="email" name="email" class="form-control">
                  </div>
                  <div class="input-group input-group-outline mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" id="clave" name="clave" class="form-control">
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">Iniciar</button>
                  </div>
                  <!-- NEW!!! -->
                  <div class="text-center">
                    <a href="<?php echo BASE_URL . 'admin/recovery'; ?>">¿Olvidaste tu contraseña?</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer position-absolute bottom-2 py-2 w-100">
        <div class="container">
          <div class="row align-items-center justify-content-between">
            <div class="col-12 col-md-6 my-auto">
              <div class="copyright text-center text-sm text-white text-lg-start">
                Shalompijamas ©
                <script>
                  document.write(new Date().getFullYear())
                </script>
                TODOS LOS DERECHOS RESERVADOS
              </div>
            </div>
            <div class="col-auto">
            <div class="footer_logo">
                <img src="<?php echo BASE_URL . 'assets/images/logo_sistemas.jpg'; ?>" alt="Logo">
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>


  <!--   Core JS Files   -->
  <script src="<?php echo BASE_URL; ?>assets/admin/js/core/popper.min.js"></script>
  <script src="<?php echo BASE_URL; ?>assets/admin/js/core/bootstrap.min.js"></script>
  <script src="<?php echo BASE_URL; ?>assets/admin/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="<?php echo BASE_URL; ?>assets/admin/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="<?php echo BASE_URL; ?>assets/admin/js/material-dashboard.min.js?v=3.1.0"></script>
  <script>
    const base_url = '<?php echo BASE_URL; ?>';
  </script>
  <script src="<?php echo BASE_URL; ?>assets/js/sweetalert2.all.min.js"></script>
  <script src="<?php echo BASE_URL; ?>assets/js/modulos/login.js"></script>
</body>

</html>