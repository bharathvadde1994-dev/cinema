<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public $demo;
    public $auth;
    protected $current_user = FALSE;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('DemoData_model', 'demo');
        $this->load->model('Auth_model', 'auth');
    }

    protected function get_current_user()
    {
        if ($this->current_user !== FALSE) {
            return $this->current_user;
        }

        $user_id = (int) $this->session->userdata('auth_user_id');

        if ($user_id <= 0) {
            $this->current_user = NULL;

            return NULL;
        }

        $user = $this->auth->get_user_with_company($user_id);

        if (!$user) {
            $this->session->unset_userdata(array(
                'auth_user_id',
                'auth_role',
                'auth_name',
            ));

            $this->current_user = NULL;

            return NULL;
        }

        $this->current_user = $user;

        return $this->current_user;
    }

    protected function is_authenticated()
    {
        return $this->get_current_user() !== NULL;
    }

    protected function get_authenticated_role()
    {
        return (string) $this->session->userdata('auth_role');
    }

    protected function is_admin_user()
    {
        return $this->is_authenticated() && $this->get_authenticated_role() === 'admin';
    }

    protected function is_advertiser_user()
    {
        return $this->is_authenticated() && $this->get_authenticated_role() === 'advertiser';
    }

    protected function require_auth()
    {
        if (!$this->is_authenticated()) {
            $this->session->set_flashdata('error', 'Please log in to continue.');
            redirect('auth/login');
            exit;
        }
    }

    protected function require_advertiser_auth()
    {
        if ($this->is_advertiser_user()) {
            return;
        }

        if ($this->is_admin_user()) {
            $this->session->set_flashdata('error', 'Admin accounts must use the admin portal.');
            redirect('admin/bookings');
            exit;
        }

        $this->session->set_flashdata('error', 'Please log in to continue.');
        redirect('auth/login');
        exit;
    }

    protected function require_guest()
    {
        if ($this->is_authenticated()) {
            redirect($this->is_admin_user() ? 'admin/bookings' : 'profile');
            exit;
        }
    }

    protected function redirect_admin_to_portal()
    {
        if ($this->is_admin_user()) {
            redirect('admin/bookings');
            exit;
        }
    }

    protected function sign_in_user($user)
    {
        $this->session->set_userdata(array(
            'auth_user_id' => (int) $user['id'],
            'auth_role' => $user['role'],
            'auth_name' => trim($user['first_name'] . ' ' . $user['last_name']),
        ));

        $this->current_user = $user;
    }

    protected function sign_out_user()
    {
        $this->session->unset_userdata(array(
            'auth_user_id',
            'auth_role',
            'auth_name',
        ));

        $this->current_user = NULL;
    }

    protected function render($view, $data = array())
    {
        $multi_cart = $this->session->userdata('multi_booking_cart');
        $checkout_draft = $this->session->userdata('checkout_draft');
        $has_multi_cart = is_array($multi_cart)
            && !empty($multi_cart['items'])
            && is_array($multi_cart['items']);
        $has_checkout_draft = is_array($checkout_draft) && !empty($checkout_draft['cinema_slug']);
        $cart_item_count = $has_multi_cart ? count($multi_cart['items']) : ($has_checkout_draft ? 1 : 0);

        $defaults = array(
            'title' => 'KinoBlick',
            'active_nav' => '',
            'auth_user' => $this->get_current_user(),
            'is_authenticated' => $this->is_authenticated(),
            'auth_role' => $this->session->userdata('auth_role'),
            'has_checkout_draft' => $has_checkout_draft,
            'has_multi_cart' => $has_multi_cart,
            'cart_item_count' => $cart_item_count,
            'flash_error' => $this->session->flashdata('error'),
            'flash_success' => $this->session->flashdata('success'),
            'hide_footer' => FALSE,
            'body_class' => '',
        );

        $data = array_merge($defaults, $data);

        $this->load->view('layout/header', $data);
        $this->load->view($view, $data);

        if (empty($data['hide_footer'])) {
            $this->load->view('layout/footer', $data);
        }
    }

    protected function render_admin($view, $data = array())
    {
        $defaults = array(
            'title' => 'Admin Panel',
            'auth_user' => $this->get_current_user(),
            'is_authenticated' => $this->is_authenticated(),
            'auth_role' => $this->session->userdata('auth_role'),
            'flash_error' => $this->session->flashdata('error'),
            'flash_success' => $this->session->flashdata('success'),
            'active_admin_nav' => '',
            'admin_search_query' => '',
            'body_class' => '',
        );

        $data = array_merge($defaults, $data);

        $this->load->view('layout/admin_header', $data);
        $this->load->view($view, $data);
        $this->load->view('layout/admin_footer', $data);
    }

    protected function render_admin_auth($view, $data = array())
    {
        $defaults = array(
            'title' => 'Admin Access',
            'flash_error' => $this->session->flashdata('error'),
            'flash_success' => $this->session->flashdata('success'),
            'body_class' => '',
        );

        $data = array_merge($defaults, $data);

        $this->load->view('layout/admin_auth_header', $data);
        $this->load->view($view, $data);
        $this->load->view('layout/admin_auth_footer', $data);
    }
}
