<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Cinema_model', 'cinema');
        $this->load->model('Booking_model', 'booking_store');
    }

    public function index()
    {
        $selected_business = trim((string) $this->input->get('business', TRUE));
        $selected_cinema = trim((string) $this->input->get('cinema', TRUE));
        $selected_cinema_slugs = array_values(array_filter(array_map('trim', explode(',', (string) $this->input->get('cinemas', TRUE)))));

        if (count($selected_cinema_slugs) > 1) {
            $selected_cinema_records = $this->cinema->get_cinemas_by_slugs($selected_cinema_slugs);
            $booking_cinemas = array();

            foreach ($selected_cinema_records as $cinema) {
                $booking_cinema = $this->cinema->get_booking_cinema($cinema['slug']);
                if ($booking_cinema) {
                    $booking_cinemas[] = $booking_cinema;
                }
            }

            $this->render('booking_multi', $this->build_multi_booking_view_data($booking_cinemas, NULL));
            return;
        }

        $selected_business_label = '';
        foreach ($this->demo->get_business_types() as $business_type) {
            if ($business_type['slug'] === $selected_business) {
                $selected_business_label = $business_type['label'];
                break;
            }
        }

        $booking_cinema = NULL;
        if (!empty($selected_cinema_slugs)) {
            $booking_cinema = $this->cinema->get_booking_cinema($selected_cinema_slugs[0]);
        } elseif ($selected_cinema !== '') {
            $booking_cinema = $this->cinema->get_booking_cinema($selected_cinema);
        }

        $this->render('booking', array(
            'title' => !empty($booking_cinema) ? $booking_cinema['name'] : 'Build Your Booking',
            'active_nav' => 'booking',
            'selected_business' => $selected_business_label,
            'selected_cinema' => $selected_cinema,
            'selected_cinemas' => !empty($booking_cinema) ? array($booking_cinema['name']) : array(),
            'booking_cinema' => $booking_cinema,
            'booking_duration_options' => $this->get_booking_duration_options(),
            'booking_spot_options' => $this->get_booking_spot_options(),
            'booking_month_options' => $this->get_booking_month_options(),
            'booking_month_custom_options' => $this->get_booking_month_custom_options(),
        ));
    }

    public function edit($slug = '')
    {
        $cart = $this->get_multi_cart();

        if (empty($cart['items'][$slug])) {
            $this->session->set_flashdata('error', 'That cart item could not be found.');
            redirect('booking/cart');
            return;
        }

        $selected_cinemas = array();
        foreach ($cart['items'] as $item) {
            $selected_cinemas[] = $this->cinema->get_booking_cinema($item['slug']);
        }

        $this->render('booking_multi', $this->build_multi_booking_view_data($selected_cinemas, $cart['items'][$slug]));
    }

    public function cart()
    {
        $cart = $this->get_multi_cart();

        if (empty($cart['items'])) {
            $this->session->set_flashdata('error', 'Your cart is empty.');
            redirect('cinemas');
            return;
        }

        $summary = $this->build_multi_checkout_summary($cart);

        $this->render('booking_cart', array(
            'title' => 'Your Cart',
            'active_nav' => 'booking',
            'cart_items' => array_values($cart['items']),
            'cart_summary' => $summary,
        ));
    }

    public function remove($slug = '')
    {
        $cart = $this->get_multi_cart();

        if (!empty($cart['items'][$slug])) {
            unset($cart['items'][$slug]);
            $this->save_multi_cart($cart);
            $this->session->set_flashdata('success', 'Cinema removed from cart.');
        }

        if (empty($cart['items'])) {
            $this->clear_multi_cart();
            redirect('cinemas');
            return;
        }

        redirect('booking/cart');
    }

    public function add_to_cart()
    {
        $cinema_slug = trim((string) $this->input->post('cinema_slug', TRUE));
        $cinema_slugs_raw = trim((string) $this->input->post('cinema_slugs', TRUE));
        $cinema_slugs = array_values(array_filter(array_map('trim', explode(',', $cinema_slugs_raw))));
        $edit_slug = trim((string) $this->input->post('edit_slug', TRUE));
        $hall_count = (int) $this->input->post('hall_count', TRUE);
        $duration = trim((string) $this->input->post('duration', TRUE));
        $spot_length = trim((string) $this->input->post('spot_length', TRUE));
        $start_month = trim((string) $this->input->post('start_month', TRUE));

        if ($cinema_slug !== '' && empty($cinema_slugs)) {
            $cinema_slugs = array($cinema_slug);
        }

        if (empty($cinema_slugs)) {
            $this->session->set_flashdata('error', 'Please select at least one cinema.');
            redirect('cinemas');
            return;
        }

        if ($duration === 'custom' || $spot_length === 'custom') {
            $this->session->set_flashdata('error', 'Custom pricing requests should be handled by sales.');
            redirect('booking?cinemas=' . rawurlencode(implode(',', $cinema_slugs)));
            return;
        }

        if ($hall_count <= 0) {
            $this->session->set_flashdata('error', 'Please select at least one hall.');
            redirect('booking?cinemas=' . rawurlencode(implode(',', $cinema_slugs)));
            return;
        }

        $duration_months = (int) $duration;
        $spot_seconds = (int) $spot_length;

        if ($duration_months <= 0 || $spot_seconds <= 0) {
            $this->session->set_flashdata('error', 'Please complete the booking options.');
            redirect('booking?cinemas=' . rawurlencode(implode(',', $cinema_slugs)));
            return;
        }

        if (count($cinema_slugs) === 1 && $edit_slug === '') {
            return $this->add_single_cinema_to_checkout($cinema_slugs[0], $hall_count, $duration_months, $spot_seconds, $start_month);
        }

        $cart = $this->get_multi_cart();
        if ($edit_slug === '') {
            $cart['items'] = array();
        }

        foreach ($cinema_slugs as $slug) {
            $cinema = $this->cinema->get_booking_cinema($slug);

            if (!$cinema) {
                continue;
            }

            if ($hall_count > (int) $cinema['hall_count']) {
                $this->session->set_flashdata('error', 'Selected halls exceed the available halls for ' . $cinema['name'] . '.');
                redirect('booking?cinemas=' . rawurlencode(implode(',', $cinema_slugs)));
                return;
            }

            $estimated_total = $this->calculate_estimated_total($cinema, $hall_count, $duration_months, $spot_seconds, $start_month);
            $cart['items'][$slug] = array(
                'cinema_id' => (int) $cinema['id'],
                'slug' => $cinema['slug'],
                'name' => $cinema['name'],
                'location_label' => $cinema['location_label'],
                'weekly_reach_label' => $cinema['weekly_reach_label'],
                'hall_count_available' => (int) $cinema['hall_count'],
                'hall_count_selected' => $hall_count,
                'duration_months' => $duration_months,
                'spot_length' => $spot_seconds,
                'start_month' => $start_month,
                'currency' => !empty($cinema['currency']) ? $cinema['currency'] : 'EUR',
                'estimated_total' => $estimated_total,
            );
        }

        $this->save_multi_cart($cart);
        $this->session->set_flashdata('success', $edit_slug !== '' ? 'Booking updated.' : 'Selected cinemas added to cart.');
        redirect('booking/cart');
    }

    public function checkout()
    {
        $cart = $this->get_multi_cart();

        if (!empty($cart['items'])) {
            return $this->checkout_multi($cart);
        }

        return $this->checkout_single();
    }

    public function place_order()
    {
        if (!$this->is_authenticated()) {
            $this->session->set_userdata('post_auth_redirect', site_url('booking/checkout'));
            $this->session->set_flashdata('error', 'Please log in to complete your booking.');
            redirect('auth/login');
            return;
        }

        $cart = $this->get_multi_cart();

        if (!empty($cart['items'])) {
            return $this->place_multi_order($cart);
        }

        return $this->place_single_order();
    }

    public function details($reference = '')
    {
        $this->require_auth();

        $booking = $this->booking_store->get_booking_by_reference($reference, $this->get_current_user()['id']);

        if (!$booking) {
            show_404();
            return;
        }

        $assets_map = $this->booking_store->get_booking_item_assets_map($booking['id']);

        $this->render('booking_success', array(
            'title' => 'Booking Details',
            'active_nav' => 'booking',
            'booking_record' => $booking,
            'booking_media_assets' => $this->map_media_assets($this->booking_store->get_booking_media_assets($booking['id'])),
            'booking_item_assets_map' => $this->map_nested_item_assets($assets_map),
            'show_success_modal' => $this->input->get('welcome', TRUE) === '1',
            'bank_account_details' => array(
                'account_holder' => 'Kinoblick GmbH',
                'iban' => 'DE89 3704 0044 0532 0130 00',
                'bic' => 'COBADEFFXXX',
                'bank' => 'Commerzbank AG',
                'reference' => $booking['booking_reference'],
            ),
        ));
    }

    public function upload($reference = '')
    {
        $this->require_auth();

        $booking = $this->booking_store->get_booking_by_reference($reference, $this->get_current_user()['id']);

        if (!$booking) {
            show_404();
            return;
        }

        $selected_item_slug = trim((string) $this->input->get('item', TRUE));
        $selected_item = $this->resolve_selected_booking_item($booking, $selected_item_slug);

        if (!$selected_item) {
            show_404();
            return;
        }

        if ($this->input->method(TRUE) === 'POST') {
            $current_item_id = (int) $this->input->post('current_item_id', TRUE);
            $selected_item = $this->resolve_selected_booking_item_by_id($booking, $current_item_id);

            if (!$selected_item) {
                $this->session->set_flashdata('error', 'The selected cinema could not be found.');
                redirect('booking/upload/' . rawurlencode($booking['booking_reference']));
                return;
            }

            $uploaded_any = FALSE;
            $slots = array(
                'photo_video' => array('field' => 'photo_video_file', 'types' => 'jpg|jpeg|png|mp4|mov|avi', 'slot' => 'photo_video'),
                'logo' => array('field' => 'logo_file', 'types' => 'jpg|jpeg|png|svg|pdf', 'slot' => 'logo'),
                'text_content' => array('field' => 'text_content_file', 'types' => 'pdf|doc|docx|txt', 'slot' => 'text_content'),
            );

            foreach ($slots as $slot) {
                if (!empty($_FILES[$slot['field']]['name'])) {
                    $upload = $this->perform_media_upload($booking, $slot['field'], $slot['types']);

                    if (isset($upload['error'])) {
                        $this->session->set_flashdata('error', $upload['error']);
                        redirect('booking/upload/' . rawurlencode($booking['booking_reference']) . '?item=' . rawurlencode($selected_item['cinema_slug']));
                        return;
                    }

                    $file_type = $this->map_upload_file_type($upload['data']['file_ext']);
                    $this->booking_store->upsert_booking_item_asset(
                        $booking,
                        (int) $selected_item['id'],
                        $this->get_current_user(),
                        $slot['slot'],
                        $file_type,
                        $upload['data']
                    );
                    $uploaded_any = TRUE;
                }
            }

            $copy_targets = array_map('intval', (array) $this->input->post('copy_to_items'));
            $copy_targets = array_values(array_filter($copy_targets, function ($item_id) use ($selected_item) {
                return $item_id > 0 && $item_id !== (int) $selected_item['id'];
            }));

            if (!empty($copy_targets)) {
                $this->booking_store->copy_booking_item_assets($booking['id'], (int) $selected_item['id'], $copy_targets, (int) $this->get_current_user()['id']);
                $uploaded_any = TRUE;
            }

            if (!$uploaded_any) {
                $this->session->set_flashdata('error', 'Please choose at least one file or select cinemas to copy assets to.');
                redirect('booking/upload/' . rawurlencode($booking['booking_reference']) . '?item=' . rawurlencode($selected_item['cinema_slug']));
                return;
            }

            $status = $this->booking_store->sync_booking_media_status($booking['id']);
            $this->session->set_flashdata('success', $status === 'uploaded'
                ? 'All media assets uploaded successfully.'
                : 'Media assets saved. You can complete the remaining cinemas later.');
            redirect('booking/upload/' . rawurlencode($booking['booking_reference']) . '?item=' . rawurlencode($selected_item['cinema_slug']) . '&submitted=1');
            return;
        }

        $assets_map = $this->map_nested_item_assets($this->booking_store->get_booking_item_assets_map($booking['id']));

        $this->render('booking_upload', array(
            'title' => 'Upload Media Assets',
            'active_nav' => 'booking',
            'booking_record' => $booking,
            'booking_media_assets' => isset($assets_map[$selected_item['id']]) ? $assets_map[$selected_item['id']] : array(),
            'booking_item_assets_map' => $assets_map,
            'booking_selected_item' => $selected_item,
            'show_upload_success_modal' => $this->input->get('submitted', TRUE) === '1' && $booking['media_status'] === 'uploaded',
        ));
    }

    protected function add_single_cinema_to_checkout($slug, $hall_count, $duration_months, $spot_seconds, $start_month)
    {
        $cinema = $this->cinema->get_booking_cinema($slug);

        if (!$cinema) {
            $this->session->set_flashdata('error', 'Please select a valid cinema.');
            redirect('cinemas');
            return;
        }

        if ($hall_count > (int) $cinema['hall_count']) {
            $this->session->set_flashdata('error', 'Please select a valid number of halls.');
            redirect('booking?cinemas=' . rawurlencode($cinema['slug']));
            return;
        }

        $estimated_total = $this->calculate_estimated_total($cinema, $hall_count, $duration_months, $spot_seconds, $start_month);
        $existing = $this->get_checkout_draft();
        $draft = array(
            'cinema_slug' => $cinema['slug'],
            'cinema_name' => $cinema['name'],
            'location_label' => $cinema['location_label'],
            'weekly_reach_label' => $cinema['weekly_reach_label'],
            'hall_count_available' => (int) $cinema['hall_count'],
            'hall_count_selected' => $hall_count,
            'duration_months' => $duration_months,
            'spot_length' => $spot_seconds,
            'start_month' => $start_month,
            'currency' => !empty($cinema['currency']) ? $cinema['currency'] : 'EUR',
            'estimated_total' => $estimated_total,
            'same_as_billing' => TRUE,
            'payment_method' => 'simulated_checkout',
            'payment_plan' => isset($existing['payment_plan']) ? $existing['payment_plan'] : 'one_time',
            'coupon_code' => '',
            'billing_address' => isset($existing['billing_address']) ? $existing['billing_address'] : $this->get_default_billing_address(),
            'delivery_address' => isset($existing['delivery_address']) ? $existing['delivery_address'] : $this->get_default_delivery_address(),
            'payment_details' => isset($existing['payment_details']) ? $existing['payment_details'] : array(),
            'same_as_billing' => isset($existing['same_as_billing']) ? $existing['same_as_billing'] : $this->is_default_delivery_same_as_billing(),
        );

        $this->save_checkout_draft($draft);
        redirect('booking/checkout');
    }

    protected function checkout_single()
    {
        $draft = $this->get_checkout_draft();

        if (empty($draft) || empty($draft['cinema_slug'])) {
            $this->session->set_flashdata('error', 'Your cart is empty. Please choose a cinema first.');
            redirect('cinemas');
            return;
        }

        if ($this->input->method(TRUE) === 'POST') {
            $draft = $this->apply_checkout_post($draft);
            $this->save_checkout_draft($draft);
            redirect('booking/checkout');
            return;
        }

        $summary = $this->build_checkout_summary($draft);

        $this->render('booking_checkout', array(
            'title' => 'Checkout',
            'active_nav' => 'booking',
            'checkout_draft' => $draft,
            'checkout_summary' => $summary,
            'checkout_plan_options' => $this->build_payment_plan_options($summary, $draft),
        ));
    }

    protected function checkout_multi($cart)
    {
        $draft = $this->get_checkout_draft();
        if (empty($draft)) {
            $draft = $this->get_default_checkout_meta();
        }

        if ($this->input->method(TRUE) === 'POST') {
            $draft = $this->apply_checkout_post($draft);
            $this->save_checkout_draft($draft);
            redirect('booking/checkout');
            return;
        }

        $summary = $this->build_multi_checkout_summary($cart, $draft);

        $this->render('booking_checkout_multi', array(
            'title' => 'Checkout',
            'active_nav' => 'booking',
            'checkout_draft' => $draft,
            'checkout_summary' => $summary,
            'checkout_plan_options' => $this->build_payment_plan_options($summary, array(
                'duration_months' => $summary['max_duration_months'],
            )),
            'cart_items' => array_values($cart['items']),
        ));
    }

    protected function place_single_order()
    {
        $draft = $this->get_checkout_draft();

        if (empty($draft) || empty($draft['cinema_slug'])) {
            $this->session->set_flashdata('error', 'Your cart is empty.');
            redirect('cinemas');
            return;
        }

        if ($this->input->post('accept_terms', TRUE) !== '1') {
            $this->session->set_flashdata('error', 'Please agree to the terms before ordering.');
            redirect('booking/checkout');
            return;
        }

        $cinema = $this->cinema->get_booking_cinema($draft['cinema_slug']);

        if (!$cinema) {
            $this->session->set_flashdata('error', 'The selected cinema is no longer available.');
            redirect('cinemas');
            return;
        }

        if (!$this->has_billing_address($draft)) {
            $this->session->set_flashdata('error', 'Please add a billing address before ordering.');
            redirect('booking/checkout');
            return;
        }

        $summary = $this->build_checkout_summary($draft);
        $selected_plan = trim((string) $this->input->post('payment_plan', TRUE));
        $plan_options = $this->build_payment_plan_options($summary, $draft);
        $plan_key = $selected_plan === 'monthly' ? 'monthly' : 'one_time';
        $draft['payment_plan'] = $plan_key;
        $this->save_checkout_draft($draft);

        $booking = $this->booking_store->create_booking_order(
            $this->get_current_user(),
            $cinema,
            $draft,
            $summary,
            $plan_options[$plan_key]
        );

        if (!$booking) {
            $this->session->set_flashdata('error', 'We could not create your booking. Please try again.');
            redirect('booking/checkout');
            return;
        }

        $this->session->unset_userdata('checkout_draft');
        redirect('booking/details/' . rawurlencode($booking['booking_reference']) . '?welcome=1');
    }

    protected function place_multi_order($cart)
    {
        $draft = $this->get_checkout_draft();
        if (empty($draft)) {
            $draft = $this->get_default_checkout_meta();
        }

        if ($this->input->post('accept_terms', TRUE) !== '1') {
            $this->session->set_flashdata('error', 'Please agree to the terms before ordering.');
            redirect('booking/checkout');
            return;
        }

        if (!$this->has_billing_address($draft)) {
            $this->session->set_flashdata('error', 'Please add a billing address before ordering.');
            redirect('booking/checkout');
            return;
        }

        $summary = $this->build_multi_checkout_summary($cart, $draft);
        $selected_plan = trim((string) $this->input->post('payment_plan', TRUE));
        $plan_options = $this->build_payment_plan_options($summary, array(
            'duration_months' => $summary['max_duration_months'],
        ));
        $plan_key = $selected_plan === 'monthly' ? 'monthly' : 'one_time';
        $draft['payment_plan'] = $plan_key;
        $this->save_checkout_draft($draft);

        $booking = $this->booking_store->create_multi_booking_order(
            $this->get_current_user(),
            $cart,
            $draft,
            $summary,
            $plan_options[$plan_key]
        );

        if (!$booking) {
            $this->session->set_flashdata('error', 'We could not create your booking. Please try again.');
            redirect('booking/checkout');
            return;
        }

        $this->clear_multi_cart();
        $this->session->unset_userdata('checkout_draft');
        redirect('booking/details/' . rawurlencode($booking['booking_reference']) . '?welcome=1');
    }

    protected function build_multi_booking_view_data($booking_cinemas, $edit_item)
    {
        $booking_cinemas = array_values(array_filter($booking_cinemas));
        $summary_cinemas = $booking_cinemas;

        if ($edit_item && !empty($edit_item['slug'])) {
            $summary_cinemas = array_values(array_filter($booking_cinemas, function ($cinema) use ($edit_item) {
                return !empty($cinema['slug']) && $cinema['slug'] === $edit_item['slug'];
            }));
        }

        $base_item = $edit_item ? $edit_item : array(
            'hall_count_selected' => 1,
            'duration_months' => 6,
            'spot_length' => 10,
            'start_month' => 'Oct',
        );
        $summary = $this->build_multi_planner_summary($summary_cinemas, $base_item);

        return array(
            'title' => $edit_item ? 'Edit Booking' : 'Plan ' . count($booking_cinemas) . ' Cinemas',
            'active_nav' => 'booking',
            'booking_cinemas' => $booking_cinemas,
            'booking_edit_item' => $edit_item,
            'booking_summary_cinemas' => $summary_cinemas,
            'booking_target_slugs' => $edit_item && !empty($edit_item['slug'])
                ? array($edit_item['slug'])
                : array_values(array_map(function ($cinema) {
                    return $cinema['slug'];
                }, $booking_cinemas)),
            'booking_multi_summary' => $summary,
            'booking_duration_options' => $this->get_booking_duration_options(),
            'booking_spot_options' => $this->get_booking_spot_options(),
            'booking_month_options' => $this->get_booking_month_options(),
            'booking_month_custom_options' => $this->get_booking_month_custom_options(),
        );
    }

    protected function build_multi_planner_summary($booking_cinemas, $item)
    {
        $total = 0;
        foreach ($booking_cinemas as $cinema) {
            $total += $this->calculate_estimated_total(
                $cinema,
                (int) $item['hall_count_selected'],
                (int) $item['duration_months'],
                (int) $item['spot_length'],
                $item['start_month']
            );
        }

        return array(
            'cinema_count' => count($booking_cinemas),
            'total' => $total,
            'halls' => (int) $item['hall_count_selected'],
            'duration_months' => (int) $item['duration_months'],
            'spot_length' => (int) $item['spot_length'],
            'start_month' => $item['start_month'],
        );
    }

    protected function build_multi_checkout_summary($cart, $draft = array())
    {
        $base_rate = 0.0;
        $total_halls = 0;
        $max_duration = 0;
        $spot_lengths = array();

        foreach ((array) $cart['items'] as $item) {
            $base_rate += (float) $item['estimated_total'];
            $total_halls += (int) $item['hall_count_selected'];
            $max_duration = max($max_duration, (int) $item['duration_months']);
            $spot_lengths[] = (int) $item['spot_length'];
        }

        $processing_fee = round($base_rate * 0.05, 2);
        $custom_start_fee = 250.00;
        $coupon_discount = 0.00;

        if (!empty($draft['coupon_code'])) {
            $coupon_discount = strtoupper($draft['coupon_code']) === 'SAVE100'
                ? 100.00
                : min(150.00, round($base_rate * 0.03, 2));
        }

        $subtotal = max(0, $base_rate + $processing_fee + $custom_start_fee - $coupon_discount);
        $vat = round($subtotal * 0.19, 2);
        $grand_total = $subtotal + $vat;

        return array(
            'currency' => 'EUR',
            'base_rate' => $base_rate,
            'processing_fee' => $processing_fee,
            'custom_start_fee' => $custom_start_fee,
            'coupon_discount' => $coupon_discount,
            'subtotal' => $subtotal,
            'vat' => $vat,
            'grand_total' => $grand_total,
            'cinema_count' => count($cart['items']),
            'total_halls' => $total_halls,
            'max_duration_months' => $max_duration,
            'spot_length_label' => count(array_unique($spot_lengths)) === 1 ? $spot_lengths[0] . ' sec' : 'Mixed',
        );
    }

    protected function apply_checkout_post($draft)
    {
        $action = trim((string) $this->input->post('form_action', TRUE));

        if ($action === 'billing') {
            $draft['billing_address'] = array(
                'company' => trim((string) $this->input->post('company', TRUE)),
                'salutation' => trim((string) $this->input->post('salutation', TRUE)),
                'first_name' => trim((string) $this->input->post('first_name', TRUE)),
                'last_name' => trim((string) $this->input->post('last_name', TRUE)),
                'street' => trim((string) $this->input->post('street', TRUE)),
                'additional' => trim((string) $this->input->post('additional', TRUE)),
                'postcode' => trim((string) $this->input->post('postcode', TRUE)),
                'city' => trim((string) $this->input->post('city', TRUE)),
                'country' => trim((string) $this->input->post('country', TRUE)),
                'phone' => trim((string) $this->input->post('phone', TRUE)),
            );
            $this->session->set_flashdata('success', 'Billing address saved.');
        } elseif ($action === 'delivery') {
            $draft['same_as_billing'] = (bool) $this->input->post('same_as_billing', TRUE);
            if ($draft['same_as_billing']) {
                $draft['delivery_address'] = array();
                $this->session->set_flashdata('success', 'Delivery address matched to billing address.');
            } else {
                $draft['delivery_address'] = array(
                    'street' => trim((string) $this->input->post('delivery_street', TRUE)),
                    'additional' => trim((string) $this->input->post('delivery_additional', TRUE)),
                    'postcode' => trim((string) $this->input->post('delivery_postcode', TRUE)),
                    'city' => trim((string) $this->input->post('delivery_city', TRUE)),
                    'country' => trim((string) $this->input->post('delivery_country', TRUE)),
                );
                $this->session->set_flashdata('success', 'Delivery address saved.');
            }
        } elseif ($action === 'coupon') {
            $draft['coupon_code'] = strtoupper(trim((string) $this->input->post('coupon_code', TRUE)));
            $this->session->set_flashdata('success', $draft['coupon_code'] !== '' ? 'Coupon code applied.' : 'Coupon code cleared.');
        } elseif ($action === 'payment') {
            $card_number = preg_replace('/\D+/', '', (string) $this->input->post('card_number', TRUE));
            $draft['payment_details'] = array(
                'cardholder_name' => trim((string) $this->input->post('cardholder_name', TRUE)),
                'last4' => $card_number !== '' ? substr($card_number, -4) : '',
                'expiry_month' => trim((string) $this->input->post('expiry_month', TRUE)),
                'expiry_year' => trim((string) $this->input->post('expiry_year', TRUE)),
                'provider' => 'Stripe',
            );
            $draft['payment_method'] = 'stripe_card';
            $this->session->set_flashdata('success', 'Stripe card details saved.');
        } elseif ($action === 'plan') {
            $plan = trim((string) $this->input->post('payment_plan', TRUE));
            $draft['payment_plan'] = $plan === 'monthly' ? 'monthly' : 'one_time';
            $this->session->set_flashdata('success', 'Payment plan updated.');
        }

        return $draft;
    }

    protected function get_default_checkout_meta()
    {
        return array(
            'same_as_billing' => $this->is_default_delivery_same_as_billing(),
            'payment_method' => 'stripe_card',
            'payment_plan' => 'one_time',
            'coupon_code' => '',
            'billing_address' => $this->get_default_billing_address(),
            'delivery_address' => $this->get_default_delivery_address(),
            'payment_details' => array(),
        );
    }

    protected function has_billing_address($draft)
    {
        return !empty($draft['billing_address']['first_name'])
            && !empty($draft['billing_address']['last_name'])
            && !empty($draft['billing_address']['street']);
    }

    protected function get_checkout_draft()
    {
        $draft = $this->session->userdata('checkout_draft');
        return is_array($draft) ? $draft : array();
    }

    protected function save_checkout_draft($draft)
    {
        $this->session->set_userdata('checkout_draft', $draft);
    }

    protected function get_multi_cart()
    {
        $cart = $this->session->userdata('multi_booking_cart');
        if (!is_array($cart)) {
            $cart = array('items' => array());
        }
        if (!isset($cart['items']) || !is_array($cart['items'])) {
            $cart['items'] = array();
        }
        return $cart;
    }

    protected function save_multi_cart($cart)
    {
        $this->session->set_userdata('multi_booking_cart', $cart);
    }

    protected function clear_multi_cart()
    {
        $this->session->unset_userdata('multi_booking_cart');
    }

    protected function get_default_billing_address()
    {
        $user = $this->get_current_user();
        if (!$user) {
            return array();
        }

        return array(
            'company' => !empty($user['company_name']) ? $user['company_name'] : '',
            'salutation' => !empty($user['salutation']) ? $user['salutation'] : 'Mr',
            'first_name' => !empty($user['first_name']) ? $user['first_name'] : '',
            'last_name' => !empty($user['last_name']) ? $user['last_name'] : '',
            'street' => !empty($user['billing_address']) ? $user['billing_address'] : '',
            'additional' => !empty($user['billing_additional']) ? $user['billing_additional'] : '',
            'postcode' => !empty($user['billing_postcode']) ? $user['billing_postcode'] : '',
            'city' => !empty($user['billing_city']) ? $user['billing_city'] : '',
            'country' => !empty($user['billing_country']) ? $user['billing_country'] : 'Germany',
            'phone' => !empty($user['phone']) ? $user['phone'] : '',
        );
    }

    protected function get_default_delivery_address()
    {
        $user = $this->get_current_user();
        if (!$user) {
            return array();
        }

        return array(
            'street' => !empty($user['delivery_address']) ? $user['delivery_address'] : '',
            'additional' => !empty($user['delivery_additional']) ? $user['delivery_additional'] : '',
            'postcode' => !empty($user['delivery_postcode']) ? $user['delivery_postcode'] : '',
            'city' => !empty($user['delivery_city']) ? $user['delivery_city'] : '',
            'country' => !empty($user['delivery_country']) ? $user['delivery_country'] : 'Germany',
        );
    }

    protected function is_default_delivery_same_as_billing()
    {
        $user = $this->get_current_user();
        if (!$user) {
            return TRUE;
        }

        return (string) $user['billing_address'] === (string) $user['delivery_address']
            && (string) $user['billing_additional'] === (string) $user['delivery_additional']
            && (string) $user['billing_city'] === (string) $user['delivery_city']
            && (string) $user['billing_postcode'] === (string) $user['delivery_postcode']
            && (string) $user['billing_country'] === (string) $user['delivery_country'];
    }

    protected function build_checkout_summary($draft)
    {
        $currency = !empty($draft['currency']) ? $draft['currency'] : 'EUR';
        $base_rate = (float) $draft['estimated_total'];
        $processing_fee = 150.00;
        $custom_start_fee = strtolower((string) $draft['start_month']) === 'custom' ? 50.00 : 0.00;
        $coupon_discount = 0.00;

        if (!empty($draft['coupon_code'])) {
            $coupon_discount = strtoupper($draft['coupon_code']) === 'SAVE100'
                ? 100.00
                : min(75.00, round($base_rate * 0.03, 2));
        }

        $subtotal = max(0, $base_rate + $processing_fee + $custom_start_fee - $coupon_discount);
        $vat = round($subtotal * 0.19, 2);
        $grand_total = $subtotal + $vat;

        return array(
            'currency' => $currency,
            'base_rate' => $base_rate,
            'processing_fee' => $processing_fee,
            'custom_start_fee' => $custom_start_fee,
            'coupon_discount' => $coupon_discount,
            'subtotal' => $subtotal,
            'vat' => $vat,
            'grand_total' => $grand_total,
        );
    }

    protected function build_payment_plan_options($summary, $draft)
    {
        $months = max(3, (int) $draft['duration_months']);
        $monthly_amount = round($summary['grand_total'] / $months, 2);
        $amount_paid_now = $monthly_amount;
        $amount_left = max(0, round($summary['grand_total'] - $amount_paid_now, 2));

        return array(
            'one_time' => array(
                'type' => 'one_time',
                'label' => 'One-Time Payment',
                'amount_paid_now' => $summary['grand_total'],
                'amount_left' => 0.00,
                'monthly_amount' => 0.00,
                'months' => 1,
            ),
            'monthly' => array(
                'type' => 'monthly',
                'label' => 'Monthly Payment',
                'amount_paid_now' => $amount_paid_now,
                'amount_left' => $amount_left,
                'monthly_amount' => $monthly_amount,
                'months' => $months,
            ),
        );
    }

    protected function calculate_estimated_total($cinema, $hall_count, $duration_months, $spot_seconds, $start_month)
    {
        $base_price = !empty($cinema['starting_price']) ? (float) $cinema['starting_price'] : 1800.00;
        $spot_multiplier = 1.0;
        if ($spot_seconds >= 30) {
            $spot_multiplier = 1.42;
        } elseif ($spot_seconds >= 20) {
            $spot_multiplier = 1.18;
        }

        $duration_discount = 1.0;
        if ($duration_months >= 12) {
            $duration_discount = 0.92;
        } elseif ($duration_months >= 6) {
            $duration_discount = 0.97;
        }

        $month_surcharge = strtolower((string) $start_month) === 'custom' ? 50.0 : 0.0;
        return round($base_price * $hall_count * $duration_months * $spot_multiplier * $duration_discount + $month_surcharge, 2);
    }

    protected function perform_media_upload($booking, $field, $allowed_types)
    {
        $folder = FCPATH . 'uploads/booking_media/' . $booking['booking_reference'] . '/';
        if (!is_dir($folder)) {
            mkdir($folder, 0777, TRUE);
        }

        $config = array(
            'upload_path' => $folder,
            'allowed_types' => $allowed_types,
            'max_size' => 102400,
            'file_ext_tolower' => TRUE,
            'remove_spaces' => TRUE,
            'encrypt_name' => TRUE,
        );

        $this->load->library('upload', $config, 'booking_upload');

        if (!$this->booking_upload->do_upload($field)) {
            return array('error' => strip_tags($this->booking_upload->display_errors('', '')));
        }

        $data = $this->booking_upload->data();
        $data['client_name'] = isset($data['client_name']) ? $data['client_name'] : $data['orig_name'];
        $data['storage_path'] = 'uploads/booking_media/' . $booking['booking_reference'] . '/' . $data['file_name'];
        return array('data' => $data);
    }

    protected function map_upload_file_type($extension)
    {
        $extension = strtolower(ltrim((string) $extension, '.'));
        if (in_array($extension, array('mp4', 'mov', 'avi'), TRUE)) {
            return 'video';
        }
        if (in_array($extension, array('jpg', 'jpeg', 'png', 'svg'), TRUE)) {
            return 'image';
        }
        return 'document';
    }

    protected function map_media_assets($assets)
    {
        $mapped = array();
        foreach ($assets as $asset) {
            $slot = !empty($asset['asset_slot']) ? $asset['asset_slot'] : 'other';
            $asset['public_url'] = base_url($asset['storage_path']);
            $mapped[$slot] = $asset;
        }
        return $mapped;
    }

    protected function map_nested_item_assets($item_assets_map)
    {
        $mapped = array();
        foreach ((array) $item_assets_map as $item_id => $assets) {
            $mapped[$item_id] = $this->map_media_assets($assets);
        }
        return $mapped;
    }

    protected function resolve_selected_booking_item($booking, $slug)
    {
        foreach ((array) $booking['items'] as $item) {
            if ($slug !== '' && $item['cinema_slug'] === $slug) {
                return $item;
            }
        }
        return !empty($booking['items']) ? $booking['items'][0] : NULL;
    }

    protected function resolve_selected_booking_item_by_id($booking, $item_id)
    {
        foreach ((array) $booking['items'] as $item) {
            if ((int) $item['id'] === (int) $item_id) {
                return $item;
            }
        }
        return NULL;
    }

    protected function get_booking_duration_options()
    {
        return array(
            array('value' => '6', 'label' => '6 Months'),
            array('value' => '12', 'label' => '12 Months'),
            array('value' => 'custom', 'label' => 'Custom'),
        );
    }

    protected function get_booking_spot_options()
    {
        return array(
            array('value' => '10', 'label' => '10 sec'),
            array('value' => '20', 'label' => '20 sec'),
            array('value' => '30', 'label' => '30 sec'),
            array('value' => 'custom', 'label' => 'Custom'),
        );
    }

    protected function get_booking_month_options()
    {
        return array('Aug', 'Oct', 'Feb', 'May', 'Custom');
    }

    protected function get_booking_month_custom_options()
    {
        return array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
    }
}
