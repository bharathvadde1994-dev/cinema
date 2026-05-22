<main class="booking-upload-page booking-upload-page-multi">
    <?php $item_assets = $booking_media_assets; ?>
    <section class="booking-upload-section">
        <div class="content-shell booking-upload-shell booking-upload-shell-wide">
            <div class="booking-upload-head booking-upload-head-wide">
                <a class="booking-upload-back" href="<?php echo site_url('booking/details/' . rawurlencode($booking_record['booking_reference'])); ?>">&larr;</a>
                <div>
                    <h1>Upload Your Media Assets</h1>
                    <p>Provide your content and we'll create a cinema-ready advertisement for your campaign.</p>
                </div>
                <button type="button" class="booking-upload-guidelines-button" data-modal-open="guidelines-modal">View Guidelines</button>
            </div>

            <?php echo form_open_multipart(site_url('booking/upload/' . rawurlencode($booking_record['booking_reference']) . '?item=' . rawurlencode($booking_selected_item['cinema_slug'])), array('class' => 'booking-upload-layout')); ?>
                <input type="hidden" name="current_item_id" value="<?php echo (int) $booking_selected_item['id']; ?>">

                <aside class="booking-upload-cinema-sidebar">
                    <h2>Cinemas</h2>
                    <div class="booking-upload-cinema-sidebar-list">
                        <?php foreach ($booking_record['items'] as $item): ?>
                            <a class="booking-upload-cinema-link<?php echo (int) $item['id'] === (int) $booking_selected_item['id'] ? ' is-active' : ''; ?>" href="<?php echo site_url('booking/upload/' . rawurlencode($booking_record['booking_reference']) . '?item=' . rawurlencode($item['cinema_slug'])); ?>">
                                <strong><?php echo html_escape($item['cinema_name']); ?></strong>
                                <span><?php echo html_escape($item['city_label']); ?></span>
                                <small><?php echo !empty($booking_item_assets_map[$item['id']]) && count($booking_item_assets_map[$item['id']]) >= 3 ? 'Uploaded' : 'Pending'; ?></small>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </aside>

                <div class="booking-upload-main">
                    <div class="booking-upload-cinema booking-upload-cinema-bar">
                        <div>
                            <h2><?php echo html_escape($booking_selected_item['cinema_name']); ?></h2>
                            <p>Upload media assets for advertisement</p>
                        </div>
                        <button type="submit" class="dark-button">Save Progress</button>
                    </div>

                    <section class="booking-upload-card booking-upload-card-rich">
                        <h3>Photos / Videos</h3>
                        <p>Upload high-quality images or video content</p>
                        <small>Accepted: JPG, PNG, MP4, MOV</small>
                        <label class="booking-upload-dropzone booking-upload-dropzone-rich">
                            <input type="file" name="photo_video_file" accept=".jpg,.jpeg,.png,.mp4,.mov,.avi">
                            <?php if (!empty($item_assets['photo_video'])): ?>
                                <div class="booking-upload-file-chip"><strong><?php echo html_escape($item_assets['photo_video']['original_name']); ?></strong><span><?php echo number_format(max(0.01, $item_assets['photo_video']['file_size_bytes'] / 1048576), 2); ?> MB</span></div>
                            <?php else: ?>
                                <span>Drop your file here or browse</span>
                            <?php endif; ?>
                        </label>
                    </section>

                    <section class="booking-upload-card booking-upload-card-rich">
                        <h3>Logo</h3>
                        <p>Upload your brand logo with transparent background</p>
                        <small>Accepted: SVG, PNG</small>
                        <label class="booking-upload-dropzone booking-upload-dropzone-rich">
                            <input type="file" name="logo_file" accept=".jpg,.jpeg,.png,.svg,.pdf">
                            <?php if (!empty($item_assets['logo'])): ?>
                                <div class="booking-upload-file-chip"><strong><?php echo html_escape($item_assets['logo']['original_name']); ?></strong><span><?php echo number_format(max(0.01, $item_assets['logo']['file_size_bytes'] / 1048576), 2); ?> MB</span></div>
                            <?php else: ?>
                                <span>Drop your file here or browse</span>
                            <?php endif; ?>
                        </label>
                    </section>

                    <section class="booking-upload-card booking-upload-card-rich">
                        <h3>Text / Content</h3>
                        <p>Upload campaign messaging and brand details</p>
                        <small>Accepted: PDF</small>
                        <label class="booking-upload-dropzone booking-upload-dropzone-rich">
                            <input type="file" name="text_content_file" accept=".pdf,.doc,.docx,.txt">
                            <?php if (!empty($item_assets['text_content'])): ?>
                                <div class="booking-upload-file-chip"><strong><?php echo html_escape($item_assets['text_content']['original_name']); ?></strong><span><?php echo number_format(max(0.01, $item_assets['text_content']['file_size_bytes'] / 1048576), 2); ?> MB</span></div>
                            <?php else: ?>
                                <span>Drop your file here or browse</span>
                            <?php endif; ?>
                        </label>
                    </section>

                    <?php if (count($booking_record['items']) > 1): ?>
                        <button type="button" class="light-button booking-upload-copy-trigger" data-modal-open="copy-assets-modal">Copy Assets To Other Cinemas</button>
                    <?php endif; ?>
                </div>
            <?php echo form_close(); ?>
        </div>
    </section>

    <div class="checkout-modal" id="copy-assets-modal" hidden>
        <div class="checkout-modal-backdrop" data-modal-close></div>
        <div class="checkout-modal-dialog booking-copy-modal">
            <div class="checkout-modal-head">
                <div>
                    <h2>Copy Assets to Other Cinemas</h2>
                    <p>Reuse the same campaign assets across multiple selected cinemas to save time.</p>
                </div>
                <button type="button" data-modal-close>&times;</button>
            </div>
            <?php echo form_open(site_url('booking/upload/' . rawurlencode($booking_record['booking_reference']) . '?item=' . rawurlencode($booking_selected_item['cinema_slug']))); ?>
                <input type="hidden" name="current_item_id" value="<?php echo (int) $booking_selected_item['id']; ?>">
                <div class="booking-copy-list">
                    <?php foreach ($booking_record['items'] as $item): ?>
                        <?php if ((int) $item['id'] === (int) $booking_selected_item['id']) { continue; } ?>
                        <label class="booking-copy-option">
                            <input type="checkbox" name="copy_to_items[]" value="<?php echo (int) $item['id']; ?>">
                            <span><strong><?php echo html_escape($item['cinema_name']); ?></strong><small><?php echo html_escape($item['city_label']); ?></small></span>
                        </label>
                    <?php endforeach; ?>
                </div>
                <div class="booking-copy-assets-preview">
                    <strong>The following assets will be copied:</strong>
                    <span>Photos / Videos</span>
                    <span>Logos</span>
                    <span>Text / Content</span>
                </div>
                <div class="checkout-modal-actions">
                    <button type="button" class="light-button" data-modal-close>Skip</button>
                    <button type="submit" class="header-button">Copy Assets</button>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>

    <div class="checkout-modal" id="guidelines-modal" hidden>
        <div class="checkout-modal-backdrop" data-modal-close></div>
        <div class="checkout-modal-dialog booking-guidelines-modal">
            <div class="checkout-modal-head">
                <div>
                    <h2>GUIDELINES SECTION</h2>
                    <p>Provide the required assets for your advertisement.</p>
                </div>
                <button type="button" data-modal-close>&times;</button>
            </div>
            <div class="booking-upload-guideline"><strong>Photos or Videos</strong><span>Upload high-quality images in JPG or PNG format. Recommended resolution 1920 x 1080 or higher.</span></div>
            <div class="booking-upload-guideline"><strong>Video (Optional)</strong><span>Upload your video in MP4 or MOV format. Recommended resolution: Full HD (1920 x 1080) or 4K.</span></div>
            <div class="booking-upload-guideline"><strong>Logo</strong><span>Upload your logo in SVG or PNG format. Ensure high resolution and transparent background.</span></div>
            <div class="booking-upload-guideline"><strong>Text / Content</strong><span>Provide your content in PDF format. Include key messaging, offers, and any voiceover preferences.</span></div>
            <button type="button" class="header-button full-button" data-modal-close>Understood</button>
        </div>
    </div>

    <?php if (!empty($show_upload_success_modal)): ?>
        <div class="checkout-modal booking-success-modal-overlay">
            <div class="checkout-modal-backdrop"></div>
            <div class="checkout-modal-dialog booking-success-modal booking-success-modal-uploaded">
                <div class="booking-success-check">&#10003;</div>
                <h2>Assets Submitted Successfully</h2>
                <p>Your media assets have been received and our team will begin preparing your cinema advertisement.</p>
                <div class="booking-success-modal-summary">
                    <div><span>Campaign Reference</span><strong><?php echo html_escape($booking_record['booking_reference']); ?></strong></div>
                    <div><span>A confirmation email has been sent to your registered email address with all booking details.</span></div>
                </div>
                <a class="header-button full-button" href="<?php echo site_url(''); ?>">Go Back To Home</a>
                <a class="light-button full-button" href="<?php echo site_url('booking/details/' . rawurlencode($booking_record['booking_reference'])); ?>">View Booking Details</a>
            </div>
        </div>
    <?php endif; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var modalButtons = document.querySelectorAll('[data-modal-open]');
        var closeButtons = document.querySelectorAll('[data-modal-close]');
        modalButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var modal = document.getElementById(button.getAttribute('data-modal-open'));
                if (modal) {
                    modal.hidden = false;
                    document.body.classList.add('checkout-modal-open');
                }
            });
        });
        closeButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var modal = button.closest('.checkout-modal');
                if (modal) {
                    modal.hidden = true;
                }
                document.body.classList.remove('checkout-modal-open');
            });
        });
    });
    </script>
</main>
