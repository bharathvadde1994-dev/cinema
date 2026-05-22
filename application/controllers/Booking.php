<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Cinema_model', 'cinema');
    }

    public function index()
    {
        $selected_business = trim((string) $this->input->get('business', TRUE));
        $selected_cinema = trim((string) $this->input->get('cinema', TRUE));
        $selected_cinema_slugs = array_filter(array_map('trim', explode(',', (string) $this->input->get('cinemas', TRUE))));

        $selected_business_label = '';
        $selected_cinemas = array();
        $booking_cinema = NULL;

        foreach ($this->demo->get_business_types() as $business_type) {
            if ($business_type['slug'] === $selected_business) {
                $selected_business_label = $business_type['label'];
                break;
            }
        }

        if (!empty($selected_cinema_slugs)) {
            foreach ($this->cinema->get_cinemas_by_slugs($selected_cinema_slugs) as $cinema) {
                $selected_cinemas[] = $cinema['name'];
            }
        }

        if ($selected_cinema !== '') {
            $selected_cinemas[] = $selected_cinema;
        }

        $selected_cinemas = array_values(array_unique($selected_cinemas));

        if (count($selected_cinema_slugs) > 1 || count($selected_cinemas) > 1) {
            $this->session->set_flashdata('error', 'Multi-cinema booking is not enabled yet. Please select one cinema.');
            redirect('cinemas');
            return;
        }

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
            'selected_cinemas' => $selected_cinemas,
            'booking_cinema' => $booking_cinema,
            'booking_duration_options' => array(
                array('value' => '6', 'label' => '6 Months'),
                array('value' => '12', 'label' => '12 Months'),
                array('value' => 'custom', 'label' => 'Custom'),
            ),
            'booking_spot_options' => array(
                array('value' => '10', 'label' => '10 sec'),
                array('value' => '20', 'label' => '20 sec'),
                array('value' => '30', 'label' => '30 sec'),
                array('value' => 'custom', 'label' => 'Custom'),
            ),
            'booking_month_options' => array('Aug', 'Oct', 'Feb', 'May', 'Custom'),
            'booking_month_custom_options' => array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'),
        ));
    }

    public function add_to_cart()
    {
        $cinema_slug = trim((string) $this->input->post('cinema_slug', TRUE));
        $hall_count = (int) $this->input->post('hall_count', TRUE);
        $duration = trim((string) $this->input->post('duration', TRUE));
        $spot_length = trim((string) $this->input->post('spot_length', TRUE));
        $start_month = trim((string) $this->input->post('start_month', TRUE));

        $cinema = $this->cinema->get_booking_cinema($cinema_slug);

        if (!$cinema) {
            $this->session->set_flashdata('error', 'Please select a valid cinema.');
            redirect('cinemas');
            return;
        }

        if ($hall_count <= 0 || $hall_count > (int) $cinema['hall_count']) {
            $this->session->set_flashdata('error', 'Please select a valid number of halls.');
            redirect('booking?cinemas=' . rawurlencode($cinema['slug']));
            return;
        }

        if ($duration === 'custom' || $spot_length === 'custom') {
            $this->session->set_flashdata('error', 'Custom pricing requests should be handled by sales.');
            redirect('booking?cinemas=' . rawurlencode($cinema['slug']));
            return;
        }

        $duration_months = (int) $duration;
        $spot_seconds = (int) $spot_length;

        if ($duration_months <= 0 || $spot_seconds <= 0) {
            $this->session->set_flashdata('error', 'Please complete the booking options.');
            redirect('booking?cinemas=' . rawurlencode($cinema['slug']));
            return;
        }

        $estimated_total = $this->calculate_estimated_total($cinema, $hall_count, $duration_months, $spot_seconds, $start_month);
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
            'payment_method' => 'stripe_card',
            'coupon_code' => '',
        );

        $existing = $this->get_checkout_draft();
        $draft['billing_address'] = isset($existing['billing_address']) ? $existing['billing_address'] : $this->get_default_billing_address();
        $draft['delivery_address'] = isset($existing['delivery_address']) ? $existing['delivery_address'] : $this->get_default_delivery_address();
        $draft['payment_details'] = isset($existing['payment_details']) ? $existing['payment_details'] : array();
        $draft['same_as_billing'] = isset($existing['same_as_billing']) ? $existing['same_as_billing'] : $this->is_default_delivery_same_as_billing();

        $this->save_checkout_draft($draft);

        redirect('booking/checkout');
    }

    public function checkout()
    {
        $draft = $this->get_checkout_draft();

        if (empty($draft) || empty($draft['cinema_slug'])) {
            $this->session->set_flashdata('error', 'Your cart is empty. Please choose a cinema first.');
            redirect('cinemas');
            return;
        }

        if ($this->input->method(TRUE) === 'POST') {
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
            }

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
        ));
    }

    public function place_order()
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

        if (empty($draft['billing_address']['first_name']) || empty($draft['billing_address']['last_name']) || empty($draft['billing_address']['street'])) {
            $this->session->set_flashdata('error', 'Please add a billing address before ordering.');
            redirect('booking/checkout');
            return;
        }

        if (empty($draft['payment_details']['last4'])) {
            $this->session->set_flashdata('error', 'Please add your Stripe card details before ordering.');
            redirect('booking/checkout');
            return;
        }

        $summary = $this->build_checkout_summary($draft);
        $reference = 'KB-DRAFT-' . mt_rand(10000, 99999);

        $this->session->unset_userdata('checkout_draft');
        $this->session->set_flashdata('success', 'Stripe checkout placeholder complete. Draft order ' . $reference . ' created for ' . $summary['currency'] . ' ' . number_format($summary['grand_total'], 2) . '.');
        redirect('cinemas');
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

        $month_surcharge = strtolower($start_month) === 'custom' ? 50.0 : 0.0;

        return round($base_price * $hall_count * $duration_months * $spot_multiplier * $duration_discount + $month_surcharge, 2);
    }
}
