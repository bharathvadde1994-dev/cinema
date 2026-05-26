<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'KinoBlick'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/home.css'); ?>">
</head>
<body class="<?php echo !empty($body_class) ? $body_class : ''; ?>" id="page-top">
    <?php
    $home_anchor = site_url('');
    $cart_url = !empty($has_multi_cart)
        ? site_url('booking/cart')
        : (!empty($has_checkout_draft) ? site_url('booking/checkout') : site_url('booking'));
    $cart_item_count = isset($cart_item_count) ? (int) $cart_item_count : 0;
    ?>
    <header class="site-header">
        <div class="content-shell header-shell">
            <a class="brand-mark" href="<?php echo site_url(''); ?>" aria-label="KinoBlick home">
                <img src="<?php echo base_url('assets/Images/logo.png'); ?>" alt="KinoBlick">
            </a>

            <button
                class="mobile-menu-toggle"
                type="button"
                aria-expanded="false"
                aria-controls="primary-menu-panel"
                aria-label="Open menu"
                data-mobile-menu-toggle
            >
                <span></span>
                <span></span>
                <span></span>
            </button>

            <div class="header-panel" id="primary-menu-panel" data-mobile-menu-panel>
                <nav class="main-nav" aria-label="Primary">
                    <a class="<?php echo $active_nav === 'profile' ? 'is-active' : ''; ?>" href="<?php echo site_url('profile'); ?>">My Planning</a>
                    <a class="<?php echo $active_nav === 'booking' ? 'is-active' : ''; ?>" href="<?php echo site_url('booking'); ?>">Claim A Spot</a>
                    <a href="<?php echo $home_anchor; ?>#contact">Contact Us</a>
                    <?php if (!empty($is_authenticated) && $auth_role === 'admin'): ?>
                        <a class="<?php echo $active_nav === 'admin' ? 'is-active' : ''; ?>" href="<?php echo site_url('admin/bookings'); ?>">Admin</a>
                    <?php endif; ?>
                </nav>

                <div class="header-tools">
                    <?php if (!empty($is_authenticated) && !empty($auth_user)): ?>
                        <a class="header-link" href="<?php echo site_url('profile'); ?>"><?php echo html_escape($auth_user['first_name']); ?></a>
                        <a class="header-cart" href="<?php echo $cart_url; ?>">
                            <span class="header-cart-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" focusable="false">
                                    <path d="M7 5h14l-1.4 7.1a2 2 0 0 1-2 1.6H10.2a2 2 0 0 1-2-1.5L6.2 3.8H3" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <circle cx="10.5" cy="19" r="1.5" fill="currentColor"></circle>
                                    <circle cx="17.5" cy="19" r="1.5" fill="currentColor"></circle>
                                </svg>
                            </span>
                            View Cart
                            <span class="header-cart-badge"><?php echo $cart_item_count; ?></span>
                        </a>
                    <?php else: ?>
                        <a class="header-link" href="<?php echo site_url('auth/login'); ?>">Log in</a>
                        <a class="header-cart" href="<?php echo $cart_url; ?>">
                            <span class="header-cart-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" focusable="false">
                                    <path d="M7 5h14l-1.4 7.1a2 2 0 0 1-2 1.6H10.2a2 2 0 0 1-2-1.5L6.2 3.8H3" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <circle cx="10.5" cy="19" r="1.5" fill="currentColor"></circle>
                                    <circle cx="17.5" cy="19" r="1.5" fill="currentColor"></circle>
                                </svg>
                            </span>
                            View Cart
                            <span class="header-cart-badge"><?php echo $cart_item_count; ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <?php if (!empty($flash_error) || !empty($flash_success)): ?>
        <div class="flash-stack">
            <div class="content-shell">
                <?php if (!empty($flash_error)): ?>
                    <div class="flash-message flash-error"><?php echo $flash_error; ?></div>
                <?php endif; ?>
                <?php if (!empty($flash_success)): ?>
                    <div class="flash-message flash-success"><?php echo $flash_success; ?></div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var toggle = document.querySelector('[data-mobile-menu-toggle]');
    var panel = document.querySelector('[data-mobile-menu-panel]');

    if (!toggle || !panel) {
        return;
    }

    var closeMenu = function () {
        toggle.setAttribute('aria-expanded', 'false');
        panel.classList.remove('is-open');
    };

    toggle.addEventListener('click', function () {
        var isOpen = toggle.getAttribute('aria-expanded') === 'true';
        toggle.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
        panel.classList.toggle('is-open', !isOpen);
    });

    panel.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', closeMenu);
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth > 860) {
            closeMenu();
        }
    });
});
</script>
