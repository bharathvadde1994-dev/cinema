<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->require_auth();
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
        $this->require_auth();

        $this->form_validation->set_rules('first_name', 'First name', 'trim|required|min_length[2]');
        $this->form_validation->set_rules('last_name', 'Last name', 'trim|required|min_length[2]');
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

        if ($new_email !== $current['email']) {
            $existing = $this->auth->find_user_by_email($new_email);

            if ($existing) {
                $this->session->set_flashdata('error', 'That email address is already in use.');
                redirect('profile');
                return;
            }
        }

        $updated = $this->auth->update_profile($current['id'], array(
            'first_name' => $this->input->post('first_name', TRUE),
            'last_name' => $this->input->post('last_name', TRUE),
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
            $this->session->set_userdata('auth_name', trim($this->input->post('first_name', TRUE) . ' ' . $this->input->post('last_name', TRUE)));
            $this->session->set_flashdata('success', 'Profile updated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Profile update failed.');
        }

        redirect('profile');
    }

    public function addresses()
    {
        if ($this->input->method(TRUE) === 'POST') {
            $this->form_validation->set_rules('billing_address', 'Billing address', 'trim|required|min_length[3]');
            $this->form_validation->set_rules('billing_city', 'Billing city', 'trim|required|min_length[2]');
            $this->form_validation->set_rules('billing_country', 'Billing country', 'trim|required|min_length[2]');
            $this->form_validation->set_rules('delivery_address', 'Delivery address', 'trim|required|min_length[3]');
            $this->form_validation->set_rules('delivery_city', 'Delivery city', 'trim|required|min_length[2]');
            $this->form_validation->set_rules('delivery_country', 'Delivery country', 'trim|required|min_length[2]');

            if ($this->form_validation->run()) {
                $updated = $this->auth->update_addresses($this->get_current_user()['id'], array(
                    'billing_address' => $this->input->post('billing_address', TRUE),
                    'billing_city' => $this->input->post('billing_city', TRUE),
                    'billing_country' => $this->input->post('billing_country', TRUE),
                    'delivery_address' => $this->input->post('delivery_address', TRUE),
                    'delivery_city' => $this->input->post('delivery_city', TRUE),
                    'delivery_country' => $this->input->post('delivery_country', TRUE),
                ));

                if ($updated) {
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

    protected function profile_payload($data = array())
    {
        $user = $this->get_current_user();
        $bookings = $this->auth->get_user_bookings($user['id']);

        foreach ($bookings as &$booking) {
            $booking['amount'] = $booking['currency'] . ' ' . number_format((float) $booking['grand_total'], 2);
            $booking['term'] = $booking['term_months'] . ' months';
        }

        return array_merge(array(
            'active_nav' => 'profile',
            'auth_user' => $user,
            'bookings' => $bookings,
        ), $data);
    }
}
