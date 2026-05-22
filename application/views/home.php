<main class="homepage">
    <?php $home_anchor = site_url(''); ?>
    <section class="home-hero" id="hero" style="background-image: linear-gradient(180deg, rgba(7, 10, 17, 0.28), rgba(7, 10, 17, 0.82)), url('<?php echo base_url('assets/Images/Home_banner.png'); ?>');">
        <div class="hero-backdrop"></div>
        <div class="content-shell home-hero-shell">
            <div class="hero-copy-block">
                <h1><?php echo html_escape($hero['title']); ?></h1>
                <p><?php echo html_escape($hero['copy']); ?></p>
            </div>
        </div>

        <a class="back-to-top-fab" href="#page-top" aria-label="Back to top">
            <span>T</span>
        </a>
    </section>

    <section class="hero-search-overlap">
        <div class="content-shell hero-search-shell">
            <form class="slot-search-card" action="<?php echo site_url('cinemas'); ?>" method="get" id="planning">
                <div class="slot-search-field">
                    <label for="hero-location">Location</label>
                    <input id="hero-location" name="location" list="hero-locations" type="text" placeholder="e.g. Berlin">
                    <datalist id="hero-locations">
                        <?php foreach ($cities as $city): ?>
                            <option value="<?php echo html_escape($city); ?>">
                        <?php endforeach; ?>
                    </datalist>
                </div>

                <div class="slot-search-field">
                    <label for="hero-cinema">Cinema</label>
                    <select id="hero-cinema" name="cinema">
                        <option value="">Select Cinema</option>
                        <?php foreach ($cinema_names as $cinema_name): ?>
                            <option value="<?php echo html_escape($cinema_name); ?>"><?php echo html_escape($cinema_name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="slot-search-field">
                    <label for="hero-state">State</label>
                    <select id="hero-state" name="state">
                        <option value="">Select State</option>
                        <?php foreach ($states as $state): ?>
                            <option value="<?php echo html_escape($state); ?>"><?php echo html_escape($state); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button class="slot-search-button" type="submit">
                    <span aria-hidden="true">
                        <svg viewBox="0 0 24 24" focusable="false">
                            <circle cx="11" cy="11" r="6.2" fill="none" stroke="currentColor" stroke-width="1.9"></circle>
                            <path d="m16 16 4.5 4.5" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"></path>
                        </svg>
                    </span>
                    Show Slots
                </button>
            </form>
        </div>
    </section>

    <section class="business-guide-section" id="upload-guide">
        <div class="content-shell">
            <div class="section-intro center-intro">
                <h2>What You Need to Upload for Your Ad</h2>
                <p>Choose your business type to see specific requirements. We make the process simple and guide you every step of the way.</p>
            </div>

            <div class="business-tabs" role="tablist" aria-label="Business types">
                <?php foreach ($business_types as $business_type): ?>
                    <button
                        class="business-tab<?php echo $business_type['slug'] === $default_business_type ? ' is-active' : ''; ?>"
                        type="button"
                        role="tab"
                        data-target="<?php echo html_escape($business_type['slug']); ?>"
                        aria-selected="<?php echo $business_type['slug'] === $default_business_type ? 'true' : 'false'; ?>"
                    >
                        <?php echo html_escape($business_type['label']); ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <?php foreach ($business_types as $business_type): ?>
                <div
                    class="business-panel<?php echo $business_type['slug'] === $default_business_type ? ' is-active' : ''; ?>"
                    id="panel-<?php echo html_escape($business_type['slug']); ?>"
                    data-panel="<?php echo html_escape($business_type['slug']); ?>"
                    <?php echo $business_type['slug'] === $default_business_type ? '' : 'hidden'; ?>
                >
                    <div class="business-preview-card">
                        <div class="business-preview-icon">
                            <svg viewBox="0 0 24 24" focusable="false">
                                <rect x="4" y="5" width="16" height="14" rx="3" fill="none" stroke="currentColor" stroke-width="1.8"></rect>
                                <circle cx="9" cy="10" r="1.8" fill="currentColor"></circle>
                                <path d="m7 16 3.4-3.4a1.2 1.2 0 0 1 1.7 0L17 17" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                        <h3><?php echo html_escape($business_type['preview_title']); ?></h3>
                        <p><?php echo html_escape($business_type['preview_copy']); ?></p>
                    </div>

                    <div class="business-detail">
                        <h3><?php echo html_escape($business_type['title']); ?></h3>
                        <p class="business-detail-copy"><?php echo html_escape($business_type['description']); ?></p>

                        <div class="requirement-list">
                            <?php foreach ($business_type['requirements'] as $requirement): ?>
                                <article class="requirement-card">
                                    <div class="requirement-icon">
                                        <?php if ($requirement['icon'] === 'photo'): ?>
                                            <svg viewBox="0 0 24 24" focusable="false">
                                                <rect x="4" y="5" width="16" height="14" rx="3" fill="none" stroke="currentColor" stroke-width="1.7"></rect>
                                                <circle cx="9" cy="10" r="1.8" fill="none" stroke="currentColor" stroke-width="1.7"></circle>
                                                <path d="m7 16 3.3-3.3a1.2 1.2 0 0 1 1.7 0L17 17" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        <?php elseif ($requirement['icon'] === 'text'): ?>
                                            <svg viewBox="0 0 24 24" focusable="false">
                                                <path d="M7 4h7l4 4v12H7z" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"></path>
                                                <path d="M14 4v4h4M9 12h6M9 16h6" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"></path>
                                            </svg>
                                        <?php elseif ($requirement['icon'] === 'clock'): ?>
                                            <svg viewBox="0 0 24 24" focusable="false">
                                                <circle cx="12" cy="12" r="8" fill="none" stroke="currentColor" stroke-width="1.7"></circle>
                                                <path d="M12 8v5l3 2" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        <?php else: ?>
                                            <svg viewBox="0 0 24 24" focusable="false">
                                                <path d="M12 5.5a6.5 6.5 0 1 0 6.5 6.5A6.5 6.5 0 0 0 12 5.5Zm0 0V3m0 18v-2.5M5.5 12H3m18 0h-2.5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"></path>
                                                <circle cx="12" cy="12" r="2.3" fill="none" stroke="currentColor" stroke-width="1.7"></circle>
                                            </svg>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <h4><?php echo html_escape($requirement['title']); ?></h4>
                                        <p><?php echo html_escape($requirement['description']); ?></p>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>

                        <a class="business-cta" href="<?php echo site_url('booking') . '?business=' . rawurlencode($business_type['slug']); ?>">
                            <?php echo html_escape($business_type['cta']); ?>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="process-section" id="process">
        <div class="content-shell">
            <div class="section-intro center-intro">
                <span class="section-kicker">Simple Process</span>
                <h2>How Kinoblick Works</h2>
                <p>Booking local cinema advertising used to be complicated. We fixed that.</p>
            </div>

            <div class="process-track">
                <?php foreach ($steps as $step): ?>
                    <article class="process-step">
                        <div class="process-icon-shell">
                            <span class="process-number"><?php echo html_escape($step['number']); ?></span>
                            <div class="process-icon">
                                <?php if ($step['icon'] === 'location'): ?>
                                    <svg viewBox="0 0 24 24" focusable="false">
                                        <path d="M12 21s6-5.4 6-11a6 6 0 1 0-12 0c0 5.6 6 11 6 11Z" fill="none" stroke="currentColor" stroke-width="1.8"></path>
                                        <circle cx="12" cy="10" r="2.4" fill="none" stroke="currentColor" stroke-width="1.8"></circle>
                                    </svg>
                                <?php elseif ($step['icon'] === 'calendar'): ?>
                                    <svg viewBox="0 0 24 24" focusable="false">
                                        <rect x="4" y="6" width="16" height="14" rx="2.5" fill="none" stroke="currentColor" stroke-width="1.8"></rect>
                                        <path d="M8 4v4M16 4v4M4 10h16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                                    </svg>
                                <?php else: ?>
                                    <svg viewBox="0 0 24 24" focusable="false">
                                        <rect x="3" y="5" width="18" height="12" rx="2.5" fill="none" stroke="currentColor" stroke-width="1.8"></rect>
                                        <path d="M8 19h8M10 17v2M14 17v2" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                                        <path d="m10 9 5 2.8-5 2.8z" fill="currentColor"></path>
                                    </svg>
                                <?php endif; ?>
                            </div>
                        </div>
                        <h3><?php echo html_escape($step['title']); ?></h3>
                        <p><?php echo html_escape($step['description']); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>

            <div class="stats-grid">
                <?php foreach ($stats as $stat): ?>
                    <article class="metric-card">
                        <div class="metric-icon">
                            <svg viewBox="0 0 24 24" focusable="false">
                                <path d="M5 16.5 10 11l3 3 6-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M14 8h5v5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                            </svg>
                        </div>
                        <strong><?php echo html_escape($stat['value']); ?></strong>
                        <span><?php echo html_escape($stat['label']); ?></span>
                        <p><?php echo html_escape($stat['detail']); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="showcase-section" id="showcase">
        <div class="content-shell">
            <div class="section-intro showcase-intro">
                <div>
                    <h2><?php echo html_escape($showcase['title']); ?></h2>
                    <p><?php echo html_escape($showcase['copy']); ?></p>
                </div>
                <a class="inline-link" href="<?php echo site_url('cinemas'); ?>"><?php echo html_escape($showcase['cta']); ?></a>
            </div>

            <div class="showcase-slider" data-showcase-slider>
                <button class="showcase-nav showcase-nav-prev" type="button" aria-label="Previous slide" data-slider-prev>
                    <span>&lsaquo;</span>
                </button>
                <div class="showcase-viewport" data-slider-viewport>
                    <div class="showcase-stage">
                        <?php foreach ($showcase['cards'] as $card): ?>
                            <article class="showcase-card showcase-card-<?php echo html_escape($card['variant']); ?>">
                                <?php if (!empty($card['image'])): ?>
                                    <img src="<?php echo base_url($card['image']); ?>" alt="<?php echo html_escape($card['title']); ?>">
                                    <div class="showcase-play">
                                        <svg viewBox="0 0 24 24" focusable="false">
                                            <circle cx="12" cy="12" r="11" fill="none" stroke="currentColor" stroke-width="1.5"></circle>
                                            <path d="m10 8.8 6 3.2-6 3.2z" fill="currentColor"></path>
                                        </svg>
                                    </div>
                                <?php else: ?>
                                    <span><?php echo html_escape($card['title']); ?></span>
                                <?php endif; ?>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button class="showcase-nav showcase-nav-next" type="button" aria-label="Next slide" data-slider-next>
                    <span>&rsaquo;</span>
                </button>
            </div>
        </div>
    </section>

    <section class="why-us-section" id="why-us">
        <div class="content-shell why-us-shell">
            <div class="why-us-copy">
                <h2>Why Choose Us<br><span>for Local Advertising?</span></h2>
                <p>Cinema advertising guarantees a captive audience. When the lights go down, phones go away, and all eyes are on the big screen. We make accessing this premium inventory easy and affordable for local businesses.</p>
                <div class="why-us-actions">
                    <a class="dark-button" href="<?php echo site_url('booking'); ?>">View Pricing</a>
                    <a class="light-button" href="<?php echo $home_anchor; ?>#showcase">Read Case Studies</a>
                </div>
            </div>

            <div class="benefit-grid">
                <?php foreach ($benefits as $benefit): ?>
                    <article class="benefit-card">
                        <div class="benefit-icon">
                            <?php if ($benefit['icon'] === 'target'): ?>
                                <svg viewBox="0 0 24 24" focusable="false">
                                    <circle cx="12" cy="12" r="6.5" fill="none" stroke="currentColor" stroke-width="1.7"></circle>
                                    <circle cx="12" cy="12" r="2.4" fill="none" stroke="currentColor" stroke-width="1.7"></circle>
                                </svg>
                            <?php elseif ($benefit['icon'] === 'check'): ?>
                                <svg viewBox="0 0 24 24" focusable="false">
                                    <circle cx="12" cy="12" r="8" fill="none" stroke="currentColor" stroke-width="1.7"></circle>
                                    <path d="m8.5 12.4 2.2 2.2 4.8-5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            <?php elseif ($benefit['icon'] === 'flash'): ?>
                                <svg viewBox="0 0 24 24" focusable="false">
                                    <path d="M13.4 3 6 13h4l-1 8 7.4-10H12l1.4-8Z" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"></path>
                                </svg>
                            <?php else: ?>
                                <svg viewBox="0 0 24 24" focusable="false">
                                    <path d="m12 4 2.3 4.4 4.9.7-3.5 3.4.8 4.8-4.5-2.3-4.5 2.3.8-4.8L4.8 9.1l4.9-.7Z" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"></path>
                                </svg>
                            <?php endif; ?>
                        </div>
                        <h3><?php echo html_escape($benefit['title']); ?></h3>
                        <p><?php echo html_escape($benefit['description']); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="home-cta-section">
        <div class="content-shell home-cta-shell">
            <h2>Ready to promote your business on the big screen?</h2>
            <p>Join thousands of local businesses capturing attention with cinema advertising. Find your nearest cinema and book your slot in minutes.</p>
            <div class="home-cta-actions">
                <a class="home-cta-primary" href="<?php echo site_url('booking'); ?>">Book Your Slot Now</a>
                <a class="home-cta-secondary" href="mailto:sales@kinoblick.de">Talk to Sales</a>
            </div>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var tabs = document.querySelectorAll('.business-tab');
    var panels = document.querySelectorAll('.business-panel');

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            var target = tab.getAttribute('data-target');

            tabs.forEach(function (item) {
                item.classList.toggle('is-active', item === tab);
                item.setAttribute('aria-selected', item === tab ? 'true' : 'false');
            });

            panels.forEach(function (panel) {
                var shouldShow = panel.getAttribute('data-panel') === target;
                panel.classList.toggle('is-active', shouldShow);
                panel.hidden = !shouldShow;
            });
        });
    });

    var slider = document.querySelector('[data-showcase-slider]');

    if (slider) {
        var viewport = slider.querySelector('[data-slider-viewport]');
        var prev = slider.querySelector('[data-slider-prev]');
        var next = slider.querySelector('[data-slider-next]');
        var scrollAmount = function () {
            return Math.max(280, Math.floor(viewport.clientWidth * 0.72));
        };
        var updateButtons = function () {
            var maxScroll = viewport.scrollWidth - viewport.clientWidth - 4;
            prev.disabled = viewport.scrollLeft <= 4;
            next.disabled = viewport.scrollLeft >= maxScroll;
        };

        prev.addEventListener('click', function () {
            viewport.scrollBy({ left: -scrollAmount(), behavior: 'smooth' });
        });

        next.addEventListener('click', function () {
            viewport.scrollBy({ left: scrollAmount(), behavior: 'smooth' });
        });

        viewport.addEventListener('scroll', updateButtons, { passive: true });
        window.addEventListener('resize', updateButtons);
        updateButtons();
    }
});
</script>
