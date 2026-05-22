<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Admin'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/home.css'); ?>">
</head>
<body class="admin-body<?php echo !empty($body_class) ? ' ' . $body_class : ''; ?>">
<div class="admin-shell">
    <header class="admin-topbar">
        <div class="admin-brand-wrap">
            <a class="admin-brand" href="<?php echo site_url('admin/bookings'); ?>">
                <img src="<?php echo base_url('assets/Images/logo.png'); ?>" alt="KinoBlick">
            </a>
            <span class="admin-panel-badge">Admin Panel</span>
        </div>

        <form class="admin-topbar-search" action="<?php echo site_url('admin/bookings'); ?>" method="get">
            <input type="text" name="q" value="<?php echo html_escape(isset($admin_search_query) ? $admin_search_query : ''); ?>" placeholder="Search...">
        </form>

        <div class="admin-topbar-tools">
            <span class="admin-topbar-icon" aria-hidden="true">◔</span>
            <a class="admin-topbar-icon" href="<?php echo site_url('admin/logout'); ?>" title="Log out">⌁</a>
        </div>
    </header>

    <div class="admin-layout">
        <aside class="admin-sidebar">
            <nav class="admin-sidebar-nav">
                <a class="admin-sidebar-link<?php echo !empty($active_admin_nav) && $active_admin_nav === 'dashboard' ? ' is-active' : ''; ?>" href="<?php echo site_url('admin/dashboard'); ?>">
                    <span class="admin-sidebar-icon">□</span>
                    Dashboard
                </a>
                <a class="admin-sidebar-link<?php echo !empty($active_admin_nav) && $active_admin_nav === 'bookings' ? ' is-active' : ''; ?>" href="<?php echo site_url('admin/bookings'); ?>">
                    <span class="admin-sidebar-icon">▣</span>
                    Bookings
                </a>
            </nav>
        </aside>

        <main class="admin-main">
            <?php if (!empty($flash_error) || !empty($flash_success)): ?>
                <div class="admin-flash-stack">
                    <?php if (!empty($flash_error)): ?>
                        <div class="flash-message flash-error"><?php echo $flash_error; ?></div>
                    <?php endif; ?>
                    <?php if (!empty($flash_success)): ?>
                        <div class="flash-message flash-success"><?php echo $flash_success; ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
