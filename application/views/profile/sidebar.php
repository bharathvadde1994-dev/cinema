<aside class="account-sidebar">
    <div class="account-sidebar-card">
        <h2>Hello, <?php echo html_escape($auth_user['first_name']); ?></h2>
        <p>Manage your account</p>

        <nav class="account-sidebar-nav">
            <a class="<?php echo $profile_tab === 'account' ? 'is-active' : ''; ?>" href="<?php echo site_url('profile'); ?>">
                <span class="account-sidebar-icon">P</span>
                Personal Profile
            </a>
            <a class="<?php echo $profile_tab === 'addresses' ? 'is-active' : ''; ?>" href="<?php echo site_url('profile/addresses'); ?>">
                <span class="account-sidebar-icon">A</span>
                Addresses
            </a>
            <a class="<?php echo $profile_tab === 'payment_methods' ? 'is-active' : ''; ?>" href="<?php echo site_url('profile/payment-methods'); ?>">
                <span class="account-sidebar-icon">M</span>
                Payment Methods
            </a>
            <a class="<?php echo $profile_tab === 'orders' ? 'is-active' : ''; ?>" href="<?php echo site_url('profile/orders'); ?>">
                <span class="account-sidebar-icon">O</span>
                Orders
            </a>
        </nav>

        <div class="account-sidebar-divider"></div>

        <a class="account-sidebar-logout" href="<?php echo site_url('auth/logout'); ?>">Logout</a>
    </div>
</aside>
