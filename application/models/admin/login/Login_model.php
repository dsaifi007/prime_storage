<?php

class Login_model extends CI_Model {

    public function checkLoginCredential($data) {
        if (isset($data['password'])) {
            $this->db->where(["email" => $data['email'], "password" => md5($data['password'])]);
        } else {
            $this->db->where(["email" => $data['email']]);
        }
        $q = $this->db->select("id,role_id,name,email,is_blocked")->from("admin")->get();
        return ($q->num_rows() > 0) ? $q->row_array() : false;
    }

    public function updateEmailInfo($email) {
        $this->db->where(["email" => $email]);
        $this->db->update("admin", ["forgot_email_sent_time" => $this->config->item("date_time"),"is_link_expire"=>0]);
    }

    public function get_email_data($email) {
        $this->db->where(["email" => $email,"is_link_expire"=>0]);
        $query = $this->db->select("id,DATE_ADD(forgot_email_sent_time , INTERVAL 24 HOUR) AS datetime")->from("admin")->get();
        return ($query->num_rows() > 0) ? $query->row_array() : false;
    }
    public function update_user_password($email, $pass) {
        $this->db->reset_query();
        $this->db->where('email', base64_decode($email));
        $this->db->update("admin", ["password" => md5($pass), "is_link_expire" => 1]);
        return true;
    }
}

?>