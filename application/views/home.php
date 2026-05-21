<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KinoBlick Cinema</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/home.css'); ?>">
</head>
<body>
    <div class="page-shell">
        <header class="topbar">
            <div class="brand">
                <span class="brand-mark">KINO</span><span class="brand-accent">BLICK</span>
            </div>
            <nav class="nav">
                <a href="#features">Why Us</a>
                <a href="#releases">Now Showing</a>
                <a href="#business">Business</a>
                <a href="#contact">Contact</a>
            </nav>
            <a class="nav-cta" href="#book">Book Screen</a>
        </header>

        <main>
            <section class="hero">
                <div class="hero-copy">
                    <p class="eyebrow">Cinema booking and promo spaces</p>
                    <h1>We Create Your Cinema Ad. You Choose the Screen.</h1>
                    <p class="hero-text">
                        Launch film nights, private screenings, and local promotions from one polished booking flow.
                    </p>
                    <div class="hero-actions">
                        <a class="button button-light" href="#book">Start Booking</a>
                        <a class="button button-ghost" href="#releases">Browse Movies</a>
                    </div>
                </div>
                <div class="hero-badge">
                    <span>T</span>
                </div>
            </section>

            <section class="search-panel" id="book">
                <div class="search-header">
                    <p class="section-kicker">What You Need To Upload To Your Ad</p>
                    <p class="section-note">Pick your city, date, and preferred screen to start a booking request.</p>
                </div>
                <form class="search-form">
                    <label>
                        <span>City</span>
                        <input type="text" placeholder="Rome">
                    </label>
                    <label>
                        <span>Date</span>
                        <input type="date">
                    </label>
                    <label>
                        <span>Screen Type</span>
                        <select>
                            <option>Premium Hall</option>
                            <option>Classic Hall</option>
                            <option>Private Event</option>
                        </select>
                    </label>
                    <button type="submit">Search Now</button>
                </form>
            </section>

            <section class="service-grid" id="features">
                <article class="service-card service-dark">
                    <p class="pill">Upload</p>
                    <h2>Launch Your Campaign</h2>
                    <p>Submit artwork, promo video, or event copy and let the cinema screen it with your chosen slot.</p>
                </article>
                <article class="service-card">
                    <p class="pill pill-soft">Cafe &amp; Coffee Breaks</p>
                    <ul class="feature-list">
                        <li>Morning matinee packages with snack bundles</li>
                        <li>Late night screening blocks for special events</li>
                        <li>Flexible seat plans for family and student bookings</li>
                        <li>Weekly featured-screen promotions</li>
                    </ul>
                </article>
            </section>

            <section class="workflow">
                <h2>How KinoBlick Works</h2>
                <div class="workflow-grid">
                    <article>
                        <strong>1</strong>
                        <h3>Select your cinema</h3>
                        <p>Browse available venues, screen sizes, and prime-time slots.</p>
                    </article>
                    <article>
                        <strong>2</strong>
                        <h3>Choose ad or screening</h3>
                        <p>Book a promo spot, brand activation, or private movie session.</p>
                    </article>
                    <article>
                        <strong>3</strong>
                        <h3>Book &amp; relax</h3>
                        <p>Confirm your request and receive a clean booking summary instantly.</p>
                    </article>
                </div>
            </section>

            <section class="stats">
                <article>
                    <strong>1000+</strong>
                    <span>Seats across partner halls</span>
                </article>
                <article>
                    <strong>50+</strong>
                    <span>Campaign launches each month</span>
                </article>
                <article>
                    <strong>15m</strong>
                    <span>Average booking confirmation time</span>
                </article>
                <article>
                    <strong>20+</strong>
                    <span>Curated film and event formats</span>
                </article>
            </section>

            <section class="releases" id="releases">
                <div class="section-heading">
                    <p class="section-kicker">Now Showing In Cinemas</p>
                    <a href="#book">View full catalogue</a>
                </div>
                <div class="poster-strip">
                    <article class="poster poster-a">
                        <span>Shadow Run</span>
                    </article>
                    <article class="poster poster-b">
                        <span>Empire Tide</span>
                    </article>
                    <article class="poster poster-c">
                        <span>Glass Signal</span>
                    </article>
                </div>
            </section>

            <section class="business" id="business">
                <div class="business-copy">
                    <p class="section-kicker">Why Choose Us</p>
                    <h2>Your Class Is Our Advertising</h2>
                    <p>
                        Built for local businesses, event organizers, and film lovers who want a booking page that looks finished from day one.
                    </p>
                </div>
                <div class="business-grid">
                    <article>
                        <h3>Powerful analytics</h3>
                        <p>Track campaign requests, hall performance, and booking demand from one place.</p>
                    </article>
                    <article>
                        <h3>Fast screening setup</h3>
                        <p>Reusable booking components keep operations consistent across halls.</p>
                    </article>
                    <article>
                        <h3>Transparent pricing</h3>
                        <p>Display clear packages for ad slots, events, and private screenings.</p>
                    </article>
                    <article>
                        <h3>Reliable support</h3>
                        <p>Give customers a direct path to confirm dates, assets, and special requests.</p>
                    </article>
                </div>
            </section>

            <section class="cta-band">
                <p class="section-kicker">Ready to promote your business on the big screen?</p>
                <h2>Advertise your brand, host a premiere, or reserve a cinema hall in minutes.</h2>
                <div class="hero-actions">
                    <a class="button button-light" href="#book">Get Started</a>
                    <a class="button button-ghost button-ghost-light" href="#contact">Talk to sales</a>
                </div>
            </section>
        </main>

        <footer class="footer" id="contact">
            <div>
                <div class="brand footer-brand">
                    <span class="brand-mark">KINO</span><span class="brand-accent">BLICK</span>
                </div>
                <p>Smart booking flow for cinema advertising and private screenings.</p>
            </div>
            <div class="footer-links">
                <a href="#features">Features</a>
                <a href="#releases">Movies</a>
                <a href="#business">Business</a>
                <a href="#book">Booking</a>
            </div>
        </footer>
    </div>
</body>
</html>
