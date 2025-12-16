<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? APP_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/principal.css">
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/style.css">
    <script src="<?php echo ASSETS_URL; ?>/js/sweetalert2.min.js"></script>
    <script src="<?php echo ASSETS_URL; ?>/js/chart.min.js"></script>
</head>
<body>
    <?php if (Auth::check()): ?>
        <?php require_once __DIR__ . '/navbar.php'; ?>
    <?php endif; ?>
