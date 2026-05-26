    <?php $home_anchor = site_url(''); ?>
    <footer class="site-footer" id="contact">
        <div class="content-shell footer-shell">
            <div class="footer-branding">
                <a class="brand-mark footer-brand-mark" href="<?php echo site_url(''); ?>" aria-label="KinoBlick home">
                    <img src="<?php echo base_url('assets/Images/logo_white.png'); ?>" alt="KinoBlick">
                </a>
                <p>
                    The premier platform for local businesses to book cinema advertising slots instantly across Germany.
                </p>
                <div class="footer-socials">
                    <a href="mailto:info@kinoblick.de" aria-label="Email KinoBlick">
                        <svg viewBox="0 0 24 24" focusable="false">
                            <path d="M4 6h16v12H4z" fill="none" stroke="currentColor" stroke-width="1.7"></path>
                            <path d="m5 7 7 6 7-6" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </a>
                    <a href="<?php echo $home_anchor; ?>#showcase" aria-label="Movie showcase">
                        <svg viewBox="0 0 24 24" focusable="false">
                            <rect x="3" y="5" width="18" height="14" rx="3" fill="none" stroke="currentColor" stroke-width="1.7"></rect>
                            <path d="m10 9 5 3-5 3z" fill="currentColor"></path>
                        </svg>
                    </a>
                    <a href="<?php echo site_url('cinemas'); ?>" aria-label="Cinema directory">
                        <svg viewBox="0 0 24 24" focusable="false">
                            <path d="M5 19V7l7-3 7 3v12" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"></path>
                            <path d="M9 19v-5h6v5" fill="none" stroke="currentColor" stroke-width="1.7"></path>
                        </svg>
                    </a>
                    <a href="<?php echo site_url('profile'); ?>" aria-label="My planning">
                        <svg viewBox="0 0 24 24" focusable="false">
                            <circle cx="12" cy="8" r="3.2" fill="none" stroke="currentColor" stroke-width="1.7"></circle>
                            <path d="M5.5 19c1.2-3 3.5-4.5 6.5-4.5s5.3 1.5 6.5 4.5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="footer-column">
                <h3>Product</h3>
                <a href="<?php echo site_url('booking'); ?>">Pricing</a>
                <a href="<?php echo site_url('cinemas'); ?>">Cinemas Directory</a>
                <a href="<?php echo $home_anchor; ?>#showcase">Case Studies</a>
                <a href="<?php echo $home_anchor; ?>#upload-guide">Guidelines</a>
            </div>

            <div class="footer-column">
                <h3>Company</h3>
                <a href="<?php echo $home_anchor; ?>#why-us">About</a>
                <a href="mailto:careers@kinoblick.de">Careers</a>
                <a href="<?php echo $home_anchor; ?>#process">Blog</a>
                <a href="mailto:sales@kinoblick.de">Contact</a>
            </div>

            <div class="footer-column" id="footer-legal">
                <h3>Legal</h3>
                <a href="<?php echo $home_anchor; ?>#footer-legal">Privacy Policy</a>
                <a href="<?php echo $home_anchor; ?>#footer-legal">Terms of Service</a>
                <a href="<?php echo $home_anchor; ?>#footer-legal">Cookie Policy</a>
            </div>
        </div>

        <div class="content-shell footer-bottom">
            <p>&copy; 2026 Kinoblick GmbH. All rights reserved.</p>
            <p>Made with <span aria-hidden="true">&#10084;</span> in Berlin</p>
        </div>
    </footer>
</body>
</html>
