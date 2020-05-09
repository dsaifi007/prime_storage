<?php

class User_model extends CI_Model {

    protected $table = "users";

    function __construct() {
        parent::__construct();
    }

    public function getAllUserList() {
        $this->db->order_by("id","DESC");
        $this->db->select("id,full_name,email,phone,is_blocked")->from("users");
        $q = $this->db->get();
        return ($q->num_rows() > 0) ? $q->result() : false;
    }

    public function update_user_status_model($data) {
        $this->db->where("id", $data['user_id']);
        $this->db->update("users", ["is_blocked" => $data['status']]);
        return true;
    }

    public function get_old_pass($data) {
        $this->db->where(["id" => $this->session->userdata("id"), "password" => md5($data['current_pass'])]);
        $query = $this->db->select("password")->from("admin")->get();
        //echo $this->db->last_query();die;
        return ($query->num_rows() > 0) ? $query->row_array() : false;
    }
    public function update_old_password($data) {
        $this->db->where("id", $this->session->userdata("id"));
        $this->db->update("admin", ["password" => md5($data['password'])]);
        return true;
    }
    public function user_queries() {
        $this->db->select("id,name,email,phone,query")->from("need_help");
        $q = $this->db->get();
        return ($q->num_rows() > 0) ? $q->result() : false;
    }
    public function userDetailModel($id) {    
        $this->db->where("users.id",$id);
        $this->db->order_by("reservation_history.id","DESC");
        $this->db->select("reservation_history.id,users.phone,reservation_history.payment_status,users.id AS user_id,users.is_blocked,users.phone,users.email,reservation_history.start_date,reservation_history.address,reservation_history.size,reservation_history.created_date,reservation_history.price,users.full_name,user_image_url")->from("users");
        $this->db->join("reservation_history", "users.id=reservation_history.user_id","LEFT");
        $q = $this->db->get();
        //echo $this->db->last_query();die;
        return ($q->num_rows() > 0) ? $q->result_array() : false;
    }
}

?>