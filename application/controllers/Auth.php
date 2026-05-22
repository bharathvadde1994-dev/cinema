<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller
{
    public function login()
    {
        $this->require_guest();

        if ($this->input->method(TRUE) === 'POST') {
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run()) {
                $user = $this->auth->verify_credentials(
                    $this->input->post('email', TRUE),
                    $this->input->post('password', FALSE)
                );

                if ($user) {
                    $this->sign_in_user($user);
                    $this->session->set_flashdata('success', 'Welcome back.');

                    if ($user['role'] === 'admin') {
                        redirect('admin/bookings');
                    }

                    redirect('profile');
                    return;
                }

                $this->session->set_flashdata('error', 'Invalid email or password.');
                redirect('auth/login');
                return;
            }
        }

        $this->render('auth/login', array(
            'title' => 'Log In',
            'body_class' => 'auth-body',
            'hide_footer' => TRUE,
        ));
    }

    public function signup()
    {
        $this->require_guest();

        if ($this->input->method(TRUE) === 'POST') {
            $this->form_validation->set_rules('company_name', 'Company name', 'trim|required|min_length[2]');
            $this->form_validation->set_rules('first_name', 'First name', 'trim|required|min_length[2]');
            $this->form_validation->set_rules('last_name', 'Last name', 'trim|required|min_length[2]');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('phone', 'Phone', 'trim|required|min_length[6]');
            $this->form_validation->set_rules('billing_city', 'City', 'trim|required|min_length[2]');
            $this->form_validation->set_rules('billing_country', 'Country', 'trim|required|min_length[2]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
            $this->form_validation->set_rules('password_confirm', 'Password confirmation', 'required|matches[password]');

            if ($this->form_validation->run()) {
                $user = $this->auth->create_advertiser_account(array(
                    'company_name' => $this->input->post('company_name', TRUE),
                    'first_name' => $this->input->post('first_name', TRUE),
                    'last_name' => $this->input->post('last_name', TRUE),
                    'email' => $this->input->post('email', TRUE),
                    'phone' => $this->input->post('phone', TRUE),
                    'billing_address' => $this->input->post('billing_address', TRUE),
                    'billing_city' => $this->input->post('billing_city', TRUE),
                    'billing_country' => $this->input->post('billing_country', TRUE),
                    'password' => $this->input->post('password', FALSE),
                ));

                if ($user) {
                    $this->sign_in_user($user);
                    $this->session->set_flashdata('success', 'Your account has been created.');
                    redirect('profile');
                    return;
                }

                $this->session->set_flashdata('error', 'We could not create your account. Please try again.');
                redirect('auth/signup');
                return;
            }
        }

        $this->render('auth/signup', array(
            'title' => 'Create Account',
            'body_class' => 'auth-body',
            'hide_footer' => TRUE,
        ));
    }

    public function logout()
    {
        $this->sign_out_user();
        $this->session->set_flashdata('success', 'You have been logged out.');
        redirect('auth/login');
    }
}
