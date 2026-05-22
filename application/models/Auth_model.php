<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    public function get_user_with_company($user_id)
    {
        return $this->db
            ->select('users.*, companies.name AS company_name, companies.contact_email AS company_email, companies.contact_phone AS company_phone, companies.website, companies.billing_address, companies.billing_city, companies.billing_country, companies.vat_number, companies.delivery_address, companies.delivery_city, companies.delivery_country')
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
            'billing_city' => $data['billing_city'],
            'billing_country' => $data['billing_country'],
            'delivery_address' => $data['billing_address'],
            'delivery_city' => $data['billing_city'],
            'delivery_country' => $data['billing_country'],
        );

        $this->db->insert('companies', $company);
        $company_id = (int) $this->db->insert_id();

        $user = array(
            'company_id' => $company_id,
            'role' => 'advertiser',
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
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
}
