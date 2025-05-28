<?php
define('BASE_URL', 'https://'.getenv('FIREPLOY_HOST').'/');
define('HOST', getenv('DB_HOST') ?: 'localhost');
define('USER', getenv('DB_USER') ?: 'root');
define('PASS', getenv('DB_PASSWORD') ?: '');
define('PORT', getenv('DB_PORT') ?: '');
define('DB', getenv('DB_DATABASE') ?: 'tienda-online');
define('CHARSET', (getenv('DB_CHARSET') ?: 'utf8'));
define('TITLE', getenv('APP_TITLE') ?: 'Shalom Pijamas');
define('MONEDA', getenv('CURRENCY') ?: 'USD');
define('CLIENT_ID', getenv('CLIENT_ID') ?: 'AQHEaKqx...');

define('USER_SMTP', getenv('USER_SMTP') ?: 'shalom.pijamas.notificaciones@gmail.com');
define('PASS_SMTP', getenv('PASS_SMTP') ?: 'ramqcpwnfexdwtec');
define('PUERTO_SMTP', getenv('PUERTO_SMTP') ?: 465);
define('HOST_SMTP', getenv('HOST_SMTP') ?: 'smtp.gmail.com');
?>
