<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    public function get_user_with_company($user_id)
    {
        return $this->db
            ->select('users.*, companies.name AS company_name, companies.contact_email AS company_email, companies.contact_phone AS company_phone, companies.website, companies.billing_address, companies.billing_additional, companies.billing_city, companies.billing_state, companies.billing_postcode, companies.billing_country, companies.delivery_address, companies.delivery_additional, companies.delivery_city, companies.delivery_state, companies.delivery_postcode, companies.delivery_country, companies.vat_number')
            ->from('users')
            ->join('companies', 'companies.id = users.company_id', 'left')
            ->where('users.id', (int) $user_id)
            ->get()
            ->row_array();
    }

    public function find_user_by_email($email)
    {
        return $this->db
            ->select('users.*, companies.name AS company_name, companies.contact_email AS company_email, companies.contact_phone AS company_phone')
            ->from('users')
            ->join('companies', 'companies.id = users.company_id', 'left')
            ->where('users.email', $email)
            ->limit(1)
            ->get()
            ->row_array();
    }

    public function find_user_by_google_sub($google_sub)
    {
        return $this->db
            ->select('users.*, companies.name AS company_name, companies.contact_email AS company_email, companies.contact_phone AS company_phone')
            ->from('users')
            ->join('companies', 'companies.id = users.company_id', 'left')
            ->where('users.google_sub', $google_sub)
            ->limit(1)
            ->get()
            ->row_array();
    }

    public function verify_credentials($email, $password)
    {
        $user = $this->find_user_by_email($email);

        if (!$user || $user['status'] !== 'active') {
            return NULL;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return NULL;
        }

        $this->db->where('id', (int) $user['id'])->update('users', array(
            'last_login_at' => date('Y-m-d H:i:s'),
        ));

        return $this->get_user_with_company($user['id']);
    }

    public function create_advertiser_account($data)
    {
        $this->db->trans_start();

        $company = array(
            'name' => $data['company_name'],
            'contact_email' => $data['email'],
            'contact_phone' => $data['phone'],
            'billing_address' => $data['billing_address'],
            'billing_additional' => isset($data['billing_additional']) ? $data['billing_additional'] : NULL,
            'billing_city' => $data['billing_city'],
            'billing_state' => isset($data['billing_state']) ? $data['billing_state'] : NULL,
            'billing_postcode' => isset($data['billing_postcode']) ? $data['billing_postcode'] : NULL,
            'billing_country' => $data['billing_country'],
            'delivery_address' => $data['billing_address'],
            'delivery_additional' => isset($data['billing_additional']) ? $data['billing_additional'] : NULL,
            'delivery_city' => $data['billing_city'],
            'delivery_state' => isset($data['billing_state']) ? $data['billing_state'] : NULL,
            'delivery_postcode' => isset($data['billing_postcode']) ? $data['billing_postcode'] : NULL,
            'delivery_country' => $data['billing_country'],
            'vat_number' => isset($data['vat_number']) ? $data['vat_number'] : NULL,
        );

        $this->db->insert('companies', $company);
        $company_id = (int) $this->db->insert_id();

        $user = array(
            'company_id' => $company_id,
            'role' => 'advertiser',
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'salutation' => isset($data['salutation']) ? $data['salutation'] : 'Mr',
            'business_type' => isset($data['business_type']) ? $data['business_type'] : NULL,
            'auth_provider' => isset($data['auth_provider']) ? $data['auth_provider'] : 'email',
            'google_sub' => isset($data['google_sub']) ? $data['google_sub'] : NULL,
            'email' => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'phone' => $data['phone'],
            'status' => 'active',
        );

        $this->db->insert('users', $user);
        $user_id = (int) $this->db->insert_id();

        $this->db->trans_complete();

        if (!$this->db->trans_status()) {
            return NULL;
        }

        return $this->get_user_with_company($user_id);
    }

    public function get_user_bookings($user_id)
    {
        return $this->db
            ->select('booking_reference AS reference, campaign_name AS campaign, status, start_date, grand_total, currency, term_months')
            ->from('bookings')
            ->where('user_id', (int) $user_id)
            ->order_by('id', 'DESC')
            ->get()
            ->result_array();
    }

    public function update_profile($user_id, $user_data, $company_data)
    {
        $user = $this->get_user_with_company($user_id);

        if (!$user) {
            return FALSE;
        }

        $this->db->trans_start();

        $this->db->where('id', (int) $user_id)->update('users', $user_data);

        if (!empty($user['company_id'])) {
            $this->db->where('id', (int) $user['company_id'])->update('companies', $company_data);
        }

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function update_addresses($user_id, $company_data)
    {
        $user = $this->get_user_with_company($user_id);

        if (!$user || empty($user['company_id'])) {
            return FALSE;
        }

        return $this->db
            ->where('id', (int) $user['company_id'])
            ->update('companies', $company_data);
    }

    public function link_google_identity($user_id, $google_sub)
    {
        return $this->db
            ->where('id', (int) $user_id)
            ->update('users', array(
                'auth_provider' => 'google',
                'google_sub' => $google_sub,
            ));
    }

    public function find_or_create_google_user($email)
    {
        $existing = $this->find_user_by_email($email);

        if ($existing) {
            return $this->get_user_with_company($existing['id']);
        }

        $local = explode('@', $email);
        $base = preg_replace('/[^a-z0-9]+/i', ' ', $local[0]);
        $base = trim($base) !== '' ? trim($base) : 'Google User';
        $parts = preg_split('/\s+/', ucwords($base));
        $first_name = isset($parts[0]) ? $parts[0] : 'Google';
        $last_name = isset($parts[1]) ? $parts[1] : 'User';

        return $this->create_advertiser_account(array(
            'company_name' => $first_name . ' Studio',
            'first_name' => $first_name,
            'last_name' => $last_name,
            'salutation' => 'Mr',
            'business_type' => 'Local Business',
            'email' => $email,
            'phone' => '+49 30 000000',
            'billing_address' => '',
            'billing_city' => 'Berlin',
            'billing_country' => 'Germany',
            'vat_number' => '',
            'password' => bin2hex(random_bytes(8)),
        ));
    }
}
