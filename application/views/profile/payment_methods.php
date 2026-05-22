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
                            <h2>Stripe Card</h2>
                            <p>Default payment method</p>
                            <span>Ending in <?php echo html_escape($saved_payment_method['last4']); ?> via <?php echo html_escape($saved_payment_method['provider']); ?></span>
                        <?php else: ?>
                            <h2>Stripe Card</h2>
                            <p>No saved card yet</p>
                            <span>Add a card during checkout and it will appear here.</span>
                        <?php endif; ?>
                    </div>
                </article>

                <a class="account-secondary-button account-inline-link" href="<?php echo site_url('booking/checkout'); ?>">Manage in Checkout</a>
            </section>
        </div>
    </section>
</main>
