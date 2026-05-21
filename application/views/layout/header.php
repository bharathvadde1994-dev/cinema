<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'KinoBlick'; ?></title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/home.css'); ?>">
</head>
<body>
    <header class="site-header">
        <div class="content-shell header-shell">
            <div class="logo">
                <span class="logo-kino">KINO</span><span class="logo-blick">BLICK</span>
            </div>
            <nav class="main-nav">
                <a class="<?php echo $active_nav === 'home' ? 'is-active' : ''; ?>" href="<?php echo site_url(''); ?>">Home</a>
                <a class="<?php echo $active_nav === 'cinemas' ? 'is-active' : ''; ?>" href="<?php echo site_url('cinemas'); ?>">Cinemas</a>
                <a class="<?php echo $active_nav === 'booking' ? 'is-active' : ''; ?>" href="<?php echo site_url('booking'); ?>">Book Slots</a>
                <a class="<?php echo $active_nav === 'profile' ? 'is-active' : ''; ?>" href="<?php echo site_url('profile'); ?>">My Profile</a>
                <a class="<?php echo $active_nav === 'admin' ? 'is-active' : ''; ?>" href="<?php echo site_url('admin/bookings'); ?>">Admin</a>
            </nav>
            <div class="header-tools">
                <a class="header-link" href="<?php echo site_url('profile'); ?>">Log in</a>
                <a class="header-button" href="<?php echo site_url('booking'); ?>">Start booking</a>
            </div>
        </div>
    </header>
