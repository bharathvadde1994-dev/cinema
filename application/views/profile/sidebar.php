<aside class="profile-sidebar">
    <div class="profile-sidebar-card">
        <span class="profile-kicker">Hello, <?php echo html_escape($auth_user['first_name']); ?></span>
        <h2><?php echo html_escape($auth_user['company_name'] ? $auth_user['company_name'] : 'Your Company'); ?></h2>
        <nav class="profile-nav">
            <a class="<?php echo $profile_tab === 'account' ? 'is-active' : ''; ?>" href="<?php echo site_url('profile'); ?>">Personal Profile</a>
            <a class="<?php echo $profile_tab === 'addresses' ? 'is-active' : ''; ?>" href="<?php echo site_url('profile/addresses'); ?>">Addresses</a>
            <a href="<?php echo site_url('booking'); ?>">Create Booking</a>
            <a href="<?php echo site_url('auth/logout'); ?>">Logout</a>
        </nav>
    </div>
</aside>
