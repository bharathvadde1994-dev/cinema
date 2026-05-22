<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model', 'admin_store');
    }

    public function index()
    {
        if ($this->is_admin_authenticated()) {
            redirect('admin/bookings');
            return;
        }

        $this->login();
    }

    public function login()
    {
        if ($this->is_admin_authenticated()) {
            redirect('admin/bookings');
            return;
        }

        if ($this->is_authenticated() && $this->session->userdata('auth_role') !== 'admin') {
            $this->session->set_flashdata('error', 'You do not have admin access.');
            redirect('');
            return;
        }

        if ($this->input->method(TRUE) === 'POST') {
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run()) {
                $user = $this->auth->verify_credentials(
                    $this->input->post('email', TRUE),
                    $this->input->post('password', FALSE)
                );

                if ($user && $user['role'] === 'admin') {
                    $this->sign_in_user($user);
                    $this->session->set_flashdata('success', 'Welcome back, admin.');
                    redirect('admin/bookings');
                    return;
                }

                $this->session->set_flashdata('error', 'Invalid admin email or password.');
                redirect('admin/login');
                return;
            }
        }

        $this->render_admin_auth('admin/login', array(
            'title' => 'Admin Login',
        ));
    }

    public function forgot_password()
    {
        if ($this->is_admin_authenticated()) {
            redirect('admin/bookings');
            return;
        }

        if ($this->input->method(TRUE) === 'POST') {
            $this->session->set_flashdata('success', 'Verification Link Sent');
            redirect('admin/forgot-password/sent');
            return;
        }

        $this->render_admin_auth('admin/forgot_password', array(
            'title' => 'Forgot Password',
        ));
    }

    public function forgot_password_sent()
    {
        $this->render_admin_auth('admin/forgot_password_sent', array(
            'title' => 'Verification Link Sent',
        ));
    }

    public function logout()
    {
        $this->sign_out_user();
        $this->session->set_flashdata('success', 'You have been logged out.');
        redirect('admin/login');
    }

    public function dashboard()
    {
        $this->require_admin_auth();

        $this->render_admin('admin/dashboard', array(
            'title' => 'Admin Dashboard',
            'active_admin_nav' => 'dashboard',
            'metrics' => $this->admin_store->get_metrics(),
            'bookings' => array_slice($this->admin_store->get_bookings(), 0, 5),
        ));
    }

    public function bookings()
    {
        $this->require_admin_auth();

        $filters = array(
            'q' => trim((string) $this->input->get('q', TRUE)),
            'filter' => trim((string) $this->input->get('filter', TRUE)),
        );

        $this->render_admin('admin/bookings', array(
            'title' => 'Bookings Management',
            'active_admin_nav' => 'bookings',
            'admin_search_query' => $filters['q'],
            'metrics' => $this->admin_store->get_metrics(),
            'bookings' => $this->admin_store->get_bookings($filters),
            'admin_filters' => $filters,
        ));
    }

    public function booking($id = 0)
    {
        $this->require_admin_auth();

        $booking = $this->admin_store->get_booking((int) $id);

        if (!$booking) {
            show_404();
            return;
        }

        $selected_item_id = (int) $this->input->get('item', TRUE);
        $selected_item = !empty($booking['items']) ? $booking['items'][0] : NULL;

        foreach ($booking['items'] as $item) {
            if ((int) $item['id'] === $selected_item_id) {
                $selected_item = $item;
                break;
            }
        }

        $this->render_admin('admin/booking_detail', array(
            'title' => 'Booking Details',
            'active_admin_nav' => 'bookings',
            'booking' => $booking,
            'selected_booking_item' => $selected_item,
        ));
    }

    public function delete_booking($id = 0)
    {
        $this->require_admin_auth();

        if ($this->input->method(TRUE) !== 'POST') {
            redirect('admin/bookings');
            return;
        }

        $reason = trim((string) $this->input->post('delete_reason', TRUE));

        if ($reason === '') {
            $this->session->set_flashdata('error', 'Please provide a delete reason.');
            redirect('admin/bookings');
            return;
        }

        if ($this->admin_store->delete_booking((int) $id, (int) $this->get_current_user()['id'], $reason)) {
            $this->session->set_flashdata('success', 'Booking deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Booking could not be deleted.');
        }

        redirect('admin/bookings');
    }

    public function mark_assets_reviewed($id = 0)
    {
        $this->require_admin_auth();

        if ($this->admin_store->mark_assets_reviewed((int) $id, (int) $this->get_current_user()['id'])) {
            $this->session->set_flashdata('success', 'Assets marked as reviewed.');
        } else {
            $this->session->set_flashdata('error', 'Assets could not be updated.');
        }

        redirect('admin/bookings/' . (int) $id);
    }

    public function request_reupload($id = 0)
    {
        $this->require_admin_auth();

        if ($this->admin_store->request_asset_reupload((int) $id, (int) $this->get_current_user()['id'])) {
            $this->session->set_flashdata('success', 'Re-upload requested.');
        } else {
            $this->session->set_flashdata('error', 'Assets could not be updated.');
        }

        redirect('admin/bookings/' . (int) $id);
    }

    public function document($id = 0, $type = 'invoice')
    {
        $this->require_admin_auth();

        $booking = $this->admin_store->get_booking((int) $id);

        if (!$booking) {
            show_404();
            return;
        }

        $label = $type === 'receipt' ? 'Receipt' : 'Invoice';
        $filename = strtolower($label) . '-' . $booking['booking_reference'] . '.txt';
        $content = $label . PHP_EOL
            . 'Reference: ' . $booking['booking_reference'] . PHP_EOL
            . 'Client: ' . $booking['client_name'] . PHP_EOL
            . 'Amount: ' . $booking['currency'] . ' ' . number_format((float) $booking['grand_total'], 2) . PHP_EOL
            . 'Plan: ' . $booking['subscription_type_label'] . PHP_EOL
            . 'Status: ' . $booking['payment_status_label'] . PHP_EOL;

        $this->output
            ->set_content_type('text/plain')
            ->set_header('Content-Disposition: attachment; filename="' . $filename . '"')
            ->set_output($content);
    }

    protected function require_admin_auth()
    {
        if ($this->is_admin_authenticated()) {
            return;
        }

        if ($this->is_authenticated()) {
            $this->session->set_flashdata('error', 'You do not have admin access.');
            redirect('');
            exit;
        }

        redirect('admin/login');
        exit;
    }

    protected function is_admin_authenticated()
    {
        return $this->is_authenticated() && $this->session->userdata('auth_role') === 'admin';
    }
}
