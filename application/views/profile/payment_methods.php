<main class="account-page">
    <section class="account-section">
        <div class="content-shell account-shell">
            <?php $this->load->view('profile/sidebar'); ?>

            <section class="account-content-card">
                <div class="account-content-head">
                    <h1>Payment Methods</h1>
                    <p>Manage your saved payment options and default payment method.</p>
                </div>

                <article class="account-payment-card">
                    <div class="account-payment-icon">S</div>
                    <div>
                        <?php if (!empty($saved_payment_method['last4'])): ?>
                            <h2>Visa ending in .... <?php echo html_escape($saved_payment_method['last4']); ?></h2>
                            <p><?php echo html_escape($saved_payment_method['provider']); ?></p>
                            <strong>Cardholder: <?php echo !empty($saved_payment_method['cardholder_name']) ? html_escape($saved_payment_method['cardholder_name']) : html_escape(trim($auth_user['first_name'] . ' ' . $auth_user['last_name'])); ?></strong>
                            <span>Expires: <?php echo html_escape($saved_payment_method['expiry_month']); ?>/<?php echo html_escape($saved_payment_method['expiry_year']); ?></span>
                        <?php else: ?>
                            <h2>No saved card yet</h2>
                            <p>Add a card during checkout</p>
                            <span>Your saved Stripe card will appear here once checkout is completed.</span>
                        <?php endif; ?>
                    </div>
                </article>

                <a class="account-secondary-button account-inline-link" href="<?php echo site_url('booking/checkout'); ?>">Change Payment Method</a>
            </section>
        </div>
    </section>
</main>
