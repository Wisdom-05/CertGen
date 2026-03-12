<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'OCNHS CertGen'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <?php
    // Determine path prefix based on whether assets folder is in the current directory
    $path_prefix = file_exists('assets/css/style.css') ? '' : '../';
    ?>
    <link rel="stylesheet" href="<?php echo $path_prefix; ?>assets/css/style.css">

</head>
<body>
    <div class="watermark-bg"></div>

