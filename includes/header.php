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

    <?php if (isset($_SESSION['user_id'])): ?>
    <nav class="top-nav">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="<?php echo $path_prefix; ?>index.php">📑 CertGen</a>
            </div>
            <div class="nav-links">
                <div class="user-greeting">
                    <span>Welcome, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong></span>
                </div>
                <a href="<?php echo $path_prefix; ?>settings.php" class="nav-item">Settings</a>
                <a href="<?php echo $path_prefix; ?>logout.php" class="nav-item logout">Logout</a>
            </div>
        </div>
    </nav>

    <style>
        .top-nav {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0.75rem 2rem;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .nav-logo a {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            text-decoration: none;
            background: linear-gradient(to right, #4f46e5, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        .user-greeting {
            color: #94a3b8;
            font-size: 0.9rem;
            padding-right: 1.5rem;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }
        .nav-item {
            color: #cbd5e1;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.3s;
        }
        .nav-item:hover {
            color: white;
        }
        .nav-item.logout {
            background: rgba(239, 68, 68, 0.1);
            color: #fca5a5;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
        }
        .nav-item.logout:hover {
            background: #ef4444;
            color: white;
        }
        
        /* Adjust body padding for fixed nav */
        body {
            padding-top: 0; /* Sticky nav handles this */
        }
    </style>
    <?php endif; ?>
