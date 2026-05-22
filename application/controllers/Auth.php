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

        if ($this->input->method(TRUE) === 'POST' && $this->input->post('signup_stage', TRUE) === 'account') {
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');

            if ($this->form_validation->run()) {
                $this->session->set_userdata('signup_draft', array(
                    'email' => $this->input->post('email', TRUE),
                    'password' => $this->input->post('password', FALSE),
                    'auth_provider' => 'email',
                ));

                redirect('auth/signup/details');
                return;
            }
        }

        $draft = $this->session->userdata('signup_draft');

        if (!empty($draft['email'])) {
            redirect('auth/signup/details');
            return;
        }

        $this->render('auth/signup', array(
            'title' => 'Create Account',
            'body_class' => 'auth-body auth-body-light',
            'hide_footer' => TRUE,
        ));
    }

    public function signup_details()
    {
        $this->require_guest();

        $draft = $this->session->userdata('signup_draft');

        if (empty($draft['email']) || empty($draft['password'])) {
            redirect('auth/signup');
            return;
        }

        if ($this->input->method(TRUE) === 'POST') {
            $this->form_validation->set_rules('full_name', 'Full name', 'trim|required|min_length[2]');
            $this->form_validation->set_rules('salutation', 'Salutation', 'trim|required|min_length[2]');
            $this->form_validation->set_rules('company_name', 'Company name', 'trim|required|min_length[2]');
            $this->form_validation->set_rules('business_type', 'Business type', 'trim|required|min_length[2]');

            if ($this->form_validation->run()) {
                $full_name = preg_split('/\s+/', trim((string) $this->input->post('full_name', TRUE)));
                $first_name = array_shift($full_name);
                $last_name = !empty($full_name) ? implode(' ', $full_name) : 'User';

                $user = $this->auth->create_advertiser_account(array(
                    'company_name' => $this->input->post('company_name', TRUE),
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'salutation' => $this->input->post('salutation', TRUE),
                    'business_type' => $this->input->post('business_type', TRUE),
                    'vat_number' => $this->input->post('vat_number', TRUE),
                    'email' => $draft['email'],
                    'phone' => '+49 30 000000',
                    'billing_address' => '',
                    'billing_city' => 'Berlin',
                    'billing_country' => 'Germany',
                    'password' => $draft['password'],
                ));

                if ($user) {
                    $this->session->unset_userdata('signup_draft');
                    $this->sign_in_user($user);
                    $this->session->set_flashdata('success', 'Your account has been created.');
                    redirect('profile');
                    return;
                }

                $this->session->set_flashdata('error', 'We could not create your account. Please try again.');
                redirect('auth/signup/details');
                return;
            }
        }

        $this->render('auth/signup_details', array(
            'title' => 'Tell Us About Yourself',
            'body_class' => 'auth-body auth-body-light',
            'hide_footer' => TRUE,
            'signup_email' => $draft['email'],
        ));
    }

    public function google()
    {
        $this->require_guest();

        $purpose = trim((string) $this->input->get('mode', TRUE));
        $email = 'google.user@kinoblick.test';

        if ($purpose === 'signup') {
            $this->session->set_userdata('signup_draft', array(
                'email' => $email,
                'password' => bin2hex(random_bytes(8)),
                'auth_provider' => 'google',
            ));

            redirect('auth/signup/details');
            return;
        }

        $user = $this->auth->find_or_create_google_user($email);

        if ($user) {
            $this->sign_in_user($user);
            $this->session->set_flashdata('success', 'Signed in with Google.');
            redirect('profile');
            return;
        }

        $this->session->set_flashdata('error', 'Google sign-in could not be completed.');
        redirect('auth/login');
    }

    public function logout()
    {
        $this->session->unset_userdata('signup_draft');
        $this->sign_out_user();
        $this->session->set_flashdata('success', 'You have been logged out.');
        redirect('auth/login');
    }
}
