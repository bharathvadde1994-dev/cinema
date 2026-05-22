<aside class="account-sidebar">
    <div class="account-sidebar-card">
        <div class="account-sidebar-header">
            <h2>Hello, <?php echo html_escape($auth_user['first_name']); ?></h2>
            <p>Manage your account</p>
        </div>

        <nav class="account-sidebar-nav">
            <a class="<?php echo $profile_tab === 'account' ? 'is-active' : ''; ?>" href="<?php echo site_url('profile'); ?>">
                <span class="account-sidebar-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" focusable="false">
                        <circle cx="12" cy="8" r="3.2"></circle>
                        <path d="M5.5 19c1.2-3 3.5-4.5 6.5-4.5s5.3 1.5 6.5 4.5"></path>
                    </svg>
                </span>
                Personal Profile
            </a>
            <a class="<?php echo $profile_tab === 'addresses' ? 'is-active' : ''; ?>" href="<?php echo site_url('profile/addresses'); ?>">
                <span class="account-sidebar-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" focusable="false">
                        <path d="M12 21s6-5.4 6-10a6 6 0 1 0-12 0c0 4.6 6 10 6 10Z"></path>
                        <circle cx="12" cy="11" r="2.3"></circle>
                    </svg>
                </span>
                Addresses
            </a>
            <a class="<?php echo $profile_tab === 'payment_methods' ? 'is-active' : ''; ?>" href="<?php echo site_url('profile/payment-methods'); ?>">
                <span class="account-sidebar-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" focusable="false">
                        <rect x="4" y="6" width="16" height="12" rx="2"></rect>
                        <path d="M4 10h16"></path>
                    </svg>
                </span>
                Payment Methods
            </a>
            <a class="<?php echo $profile_tab === 'orders' ? 'is-active' : ''; ?>" href="<?php echo site_url('profile/orders'); ?>">
                <span class="account-sidebar-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" focusable="false">
                        <rect x="6" y="4" width="12" height="16" rx="2"></rect>
                        <path d="M9 9h6M9 13h6"></path>
                    </svg>
                </span>
                Orders
            </a>
        </nav>

        <div class="account-sidebar-divider"></div>

        <a class="account-sidebar-logout" href="<?php echo site_url('auth/logout'); ?>">
            <span class="account-sidebar-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" focusable="false">
                    <path d="M10 7H7a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h3"></path>
                    <path d="m13 8 4 4-4 4"></path>
                    <path d="M9 12h8"></path>
                </svg>
            </span>
            Logout
        </a>
    </div>
</aside>
