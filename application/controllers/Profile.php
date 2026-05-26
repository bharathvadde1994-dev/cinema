<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->require_advertiser_auth();
    }

    public function index()
    {
        $this->render('profile/account', $this->profile_payload(array(
            'title' => 'My Profile',
            'profile_tab' => 'account',
        )));
    }

    public function update()
    {
        $this->require_advertiser_auth();

        $this->form_validation->set_rules('full_name', 'Full name', 'trim|required|min_length[2]');
        $this->form_validation->set_rules('salutation', 'Salutation', 'trim|required|min_length[2]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('company_name', 'Company name', 'trim|required|min_length[2]');

        if (!$this->form_validation->run()) {
            $this->render('profile/account', $this->profile_payload(array(
                'title' => 'My Profile',
                'profile_tab' => 'account',
            )));
            return;
        }

        $current = $this->get_current_user();
        $new_email = $this->input->post('email', TRUE);
        $full_name = preg_split('/\s+/', trim((string) $this->input->post('full_name', TRUE)));
        $first_name = array_shift($full_name);
        $last_name = !empty($full_name) ? implode(' ', $full_name) : 'User';

        if ($new_email !== $current['email']) {
            $existing = $this->auth->find_user_by_email($new_email);

            if ($existing) {
                $this->session->set_flashdata('error', 'That email address is already in use.');
                redirect('profile');
                return;
            }
        }

        $updated = $this->auth->update_profile($current['id'], array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'salutation' => $this->input->post('salutation', TRUE),
            'email' => $new_email,
            'phone' => $this->input->post('phone', TRUE),
        ), array(
            'name' => $this->input->post('company_name', TRUE),
            'contact_email' => $new_email,
            'contact_phone' => $this->input->post('phone', TRUE),
            'website' => $this->input->post('website', TRUE),
            'vat_number' => $this->input->post('vat_number', TRUE),
        ));

        if ($updated) {
            $this->session->set_userdata('auth_name', trim($first_name . ' ' . $last_name));
            $this->session->set_flashdata('success', 'Profile updated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Profile update failed.');
        }

        redirect('profile');
    }

    public function addresses()
    {
        if ($this->input->method(TRUE) === 'POST') {
            $same = (bool) $this->input->post('same_as_delivery', TRUE);
            $this->form_validation->set_rules('billing_full_name', 'Billing full name', 'trim|required|min_length[2]');
            $this->form_validation->set_rules('billing_address', 'Billing address', 'trim|required|min_length[3]');
            $this->form_validation->set_rules('billing_city', 'Billing city', 'trim|required|min_length[2]');
            $this->form_validation->set_rules('billing_postcode', 'Billing postal code', 'trim|required|min_length[2]');
            $this->form_validation->set_rules('billing_country', 'Billing country', 'trim|required|min_length[2]');

            if (!$same) {
                $this->form_validation->set_rules('delivery_address', 'Delivery address', 'trim|required|min_length[3]');
                $this->form_validation->set_rules('delivery_city', 'Delivery city', 'trim|required|min_length[2]');
                $this->form_validation->set_rules('delivery_postcode', 'Delivery postal code', 'trim|required|min_length[2]');
                $this->form_validation->set_rules('delivery_country', 'Delivery country', 'trim|required|min_length[2]');
            }

            if ($this->form_validation->run()) {
                $delivery_address = $same ? $this->input->post('billing_address', TRUE) : $this->input->post('delivery_address', TRUE);
                $delivery_additional = $same ? $this->input->post('billing_additional', TRUE) : $this->input->post('delivery_additional', TRUE);
                $delivery_city = $same ? $this->input->post('billing_city', TRUE) : $this->input->post('delivery_city', TRUE);
                $delivery_state = $same ? $this->input->post('billing_state', TRUE) : $this->input->post('delivery_state', TRUE);
                $delivery_postcode = $same ? $this->input->post('billing_postcode', TRUE) : $this->input->post('delivery_postcode', TRUE);
                $delivery_country = $same ? $this->input->post('billing_country', TRUE) : $this->input->post('delivery_country', TRUE);
                $billing_name = preg_split('/\s+/', trim((string) $this->input->post('billing_full_name', TRUE)));
                $billing_first_name = array_shift($billing_name);
                $billing_last_name = !empty($billing_name) ? implode(' ', $billing_name) : 'User';

                $updated = $this->auth->update_addresses($this->get_current_user()['id'], array(
                    'billing_address' => $this->input->post('billing_address', TRUE),
                    'billing_additional' => $this->input->post('billing_additional', TRUE),
                    'billing_city' => $this->input->post('billing_city', TRUE),
                    'billing_state' => $this->input->post('billing_state', TRUE),
                    'billing_postcode' => $this->input->post('billing_postcode', TRUE),
                    'billing_country' => $this->input->post('billing_country', TRUE),
                    'delivery_address' => $delivery_address,
                    'delivery_additional' => $delivery_additional,
                    'delivery_city' => $delivery_city,
                    'delivery_state' => $delivery_state,
                    'delivery_postcode' => $delivery_postcode,
                    'delivery_country' => $delivery_country,
                )) && $this->auth->update_profile($this->get_current_user()['id'], array(
                    'first_name' => $billing_first_name,
                    'last_name' => $billing_last_name,
                ), array(
                    'name' => $this->input->post('billing_company', TRUE),
                ));

                if ($updated) {
                    $this->session->set_userdata('auth_name', trim($billing_first_name . ' ' . $billing_last_name));
                    $this->session->set_flashdata('success', 'Addresses updated successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Address update failed.');
                }

                redirect('profile/addresses');
                return;
            }
        }

        $this->render('profile/addresses', $this->profile_payload(array(
            'title' => 'Addresses',
            'profile_tab' => 'addresses',
        )));
    }

    public function payment_methods()
    {
        $this->render('profile/payment_methods', $this->profile_payload(array(
            'title' => 'Payment Methods',
            'profile_tab' => 'payment_methods',
        )));
    }

    public function orders()
    {
        $this->render('profile/orders', $this->profile_payload(array(
            'title' => 'Orders',
            'profile_tab' => 'orders',
        )));
    }

    protected function profile_payload($data = array())
    {
        $user = $this->get_current_user();
        $bookings = $this->auth->get_user_bookings($user['id']);
        $checkout_draft = $this->session->userdata('checkout_draft');

        foreach ($bookings as &$booking) {
            $booking['amount'] = $booking['currency'] . ' ' . number_format((float) $booking['grand_total'], 2);
            $booking['term'] = $booking['term_months'] . ' months';
        }

        $same_as_delivery = !empty($user['billing_address']) && $user['billing_address'] === $user['delivery_address']
            && (string) $user['billing_additional'] === (string) $user['delivery_additional']
            && $user['billing_city'] === $user['delivery_city']
            && (string) $user['billing_state'] === (string) $user['delivery_state']
            && (string) $user['billing_postcode'] === (string) $user['delivery_postcode']
            && $user['billing_country'] === $user['delivery_country'];

        return array_merge(array(
            'active_nav' => 'profile',
            'auth_user' => $user,
            'bookings' => $bookings,
            'saved_payment_method' => !empty($checkout_draft['payment_details']['last4']) ? $checkout_draft['payment_details'] : NULL,
            'same_as_delivery' => $same_as_delivery,
        ), $data);
    }
}
