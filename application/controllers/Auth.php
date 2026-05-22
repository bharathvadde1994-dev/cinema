<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller
{
    protected $google_auth_endpoint = 'https://accounts.google.com/o/oauth2/v2/auth';
    protected $google_token_endpoint = 'https://oauth2.googleapis.com/token';
    protected $google_userinfo_endpoint = 'https://openidconnect.googleapis.com/v1/userinfo';

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

                    $this->redirect_after_auth('profile');
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
        $google_profile = $this->session->userdata('signup_google_profile');

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
                    'auth_provider' => !empty($draft['auth_provider']) ? $draft['auth_provider'] : 'email',
                    'google_sub' => !empty($draft['google_sub']) ? $draft['google_sub'] : NULL,
                    'email' => $draft['email'],
                    'phone' => '+49 30 000000',
                    'billing_address' => '',
                    'billing_additional' => '',
                    'billing_city' => 'Berlin',
                    'billing_state' => '',
                    'billing_postcode' => '',
                    'billing_country' => 'Germany',
                    'password' => $draft['password'],
                ));

                if ($user) {
                    $this->session->unset_userdata('signup_draft');
                    $this->session->unset_userdata('signup_google_profile');
                    $this->sign_in_user($user);
                    $this->session->set_flashdata('success', 'Your account has been created.');
                    $this->redirect_after_auth('profile');
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
            'signup_google_profile' => is_array($google_profile) ? $google_profile : array(),
        ));
    }

    public function google()
    {
        $this->require_guest();

        $purpose = trim((string) $this->input->get('mode', TRUE)) === 'signup' ? 'signup' : 'login';
        $config = $this->get_google_oauth_config();

        if (!$config['is_ready']) {
            $this->session->set_flashdata('error', 'Google sign-in is not configured yet. Please add Google OAuth credentials.');
            redirect($purpose === 'signup' ? 'auth/signup' : 'auth/login');
            return;
        }

        $state_token = bin2hex(random_bytes(24));
        $this->session->set_userdata('google_oauth_state', array(
            'token' => $state_token,
            'mode' => $purpose,
        ));

        $query = http_build_query(array(
            'client_id' => $config['client_id'],
            'redirect_uri' => $config['redirect_uri'],
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'access_type' => 'online',
            'include_granted_scopes' => 'true',
            'prompt' => 'select_account',
            'state' => $state_token,
        ));

        redirect($this->google_auth_endpoint . '?' . $query, 'location');
    }

    public function google_callback()
    {
        $this->require_guest();

        $config = $this->get_google_oauth_config();
        $oauth_state = $this->session->userdata('google_oauth_state');
        $returned_state = trim((string) $this->input->get('state', TRUE));
        $error = trim((string) $this->input->get('error', TRUE));
        $mode = !empty($oauth_state['mode']) && $oauth_state['mode'] === 'signup' ? 'signup' : 'login';

        $this->session->unset_userdata('google_oauth_state');

        if (!$config['is_ready']) {
            $this->session->set_flashdata('error', 'Google sign-in is not configured yet.');
            redirect('auth/login');
            return;
        }

        if ($error !== '') {
            $this->session->set_flashdata('error', 'Google sign-in was cancelled.');
            redirect($mode === 'signup' ? 'auth/signup' : 'auth/login');
            return;
        }

        if (empty($oauth_state['token']) || !hash_equals($oauth_state['token'], $returned_state)) {
            $this->session->set_flashdata('error', 'Google sign-in could not be verified.');
            redirect('auth/login');
            return;
        }

        $code = trim((string) $this->input->get('code', TRUE));

        if ($code === '') {
            $this->session->set_flashdata('error', 'Google sign-in did not return an authorization code.');
            redirect('auth/login');
            return;
        }

        $token_response = $this->post_form_json($this->google_token_endpoint, array(
            'code' => $code,
            'client_id' => $config['client_id'],
            'client_secret' => $config['client_secret'],
            'redirect_uri' => $config['redirect_uri'],
            'grant_type' => 'authorization_code',
        ));

        if (empty($token_response['access_token'])) {
            $this->session->set_flashdata('error', 'Google sign-in token exchange failed.');
            redirect('auth/login');
            return;
        }

        $google_profile = $this->get_json_with_bearer($this->google_userinfo_endpoint, $token_response['access_token']);

        if (empty($google_profile['sub']) || empty($google_profile['email']) || empty($google_profile['email_verified'])) {
            $this->session->set_flashdata('error', 'Google account information is incomplete.');
            redirect('auth/login');
            return;
        }

        $user = $this->auth->find_user_by_google_sub($google_profile['sub']);

        if (!$user) {
            $user = $this->auth->find_user_by_email($google_profile['email']);

            if ($user) {
                $this->auth->link_google_identity($user['id'], $google_profile['sub']);
                $user = $this->auth->get_user_with_company($user['id']);
            }
        }

        if ($user) {
            $this->session->unset_userdata('signup_draft');
            $this->session->unset_userdata('signup_google_profile');
            $this->sign_in_user($user);
            $this->session->set_flashdata('success', 'Signed in with Google.');
            $this->redirect_after_auth('profile');
            return;
        }

        $names = $this->split_name(!empty($google_profile['name']) ? $google_profile['name'] : $google_profile['email']);
        $company_name = $names['first_name'] !== '' ? $names['first_name'] . ' Studio' : 'New Company';

        $this->session->set_userdata('signup_draft', array(
            'email' => $google_profile['email'],
            'password' => bin2hex(random_bytes(16)),
            'auth_provider' => 'google',
            'google_sub' => $google_profile['sub'],
        ));
        $this->session->set_userdata('signup_google_profile', array(
            'full_name' => !empty($google_profile['name']) ? $google_profile['name'] : trim($names['first_name'] . ' ' . $names['last_name']),
            'company_name' => $company_name,
            'email' => $google_profile['email'],
        ));

        redirect('auth/signup/details');
    }

    public function logout()
    {
        $this->session->unset_userdata('signup_draft');
        $this->session->unset_userdata('signup_google_profile');
        $this->session->unset_userdata('google_oauth_state');
        $this->sign_out_user();
        $this->session->set_flashdata('success', 'You have been logged out.');
        redirect('auth/login');
    }

    protected function get_google_oauth_config()
    {
        $this->config->load('google_auth', TRUE);

        $client_id = trim((string) getenv('GOOGLE_CLIENT_ID'));
        $client_secret = trim((string) getenv('GOOGLE_CLIENT_SECRET'));
        $configured_client_id = trim((string) $this->config->item('google_oauth_client_id', 'google_auth'));
        $configured_client_secret = trim((string) $this->config->item('google_oauth_client_secret', 'google_auth'));
        $configured_redirect_uri = trim((string) $this->config->item('google_oauth_redirect_uri', 'google_auth'));

        if ($client_id === '') {
            $client_id = $configured_client_id;
        }

        if ($client_secret === '') {
            $client_secret = $configured_client_secret;
        }

        return array(
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'redirect_uri' => $configured_redirect_uri !== '' ? $configured_redirect_uri : site_url('auth/google/callback'),
            'is_ready' => $client_id !== '' && $client_secret !== '',
        );
    }

    protected function post_form_json($url, $fields)
    {
        $payload = http_build_query($fields);

        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, array(
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_POST => TRUE,
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
                CURLOPT_TIMEOUT => 20,
            ));
            $response = curl_exec($ch);
            curl_close($ch);

            return is_string($response) ? (array) json_decode($response, TRUE) : array();
        }

        $context = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => $payload,
                'timeout' => 20,
            ),
        ));
        $response = @file_get_contents($url, FALSE, $context);

        return is_string($response) ? (array) json_decode($response, TRUE) : array();
    }

    protected function get_json_with_bearer($url, $access_token)
    {
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, array(
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $access_token,
                ),
                CURLOPT_TIMEOUT => 20,
            ));
            $response = curl_exec($ch);
            curl_close($ch);

            return is_string($response) ? (array) json_decode($response, TRUE) : array();
        }

        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => "Authorization: Bearer {$access_token}\r\n",
                'timeout' => 20,
            ),
        ));
        $response = @file_get_contents($url, FALSE, $context);

        return is_string($response) ? (array) json_decode($response, TRUE) : array();
    }

    protected function split_name($full_name)
    {
        $parts = preg_split('/\s+/', trim((string) $full_name));
        $first_name = !empty($parts[0]) ? $parts[0] : 'Google';
        array_shift($parts);
        $last_name = !empty($parts) ? implode(' ', $parts) : 'User';

        return array(
            'first_name' => $first_name,
            'last_name' => $last_name,
        );
    }

    protected function redirect_after_auth($default)
    {
        $target = $this->session->userdata('post_auth_redirect');

        if (!empty($target)) {
            $this->session->unset_userdata('post_auth_redirect');
            redirect($target);
            return;
        }

        redirect($default);
    }
}
