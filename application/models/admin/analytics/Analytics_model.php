<?php

class Analytics_model extends CI_Model {

    protected $table = "users";

    function __construct() {
        parent::__construct();
    }

    public function getAllanalyticsList() {
        $this->db->order_by("id","DESC");
        $this->db->select("id,full_name,email,login_attempt AS success,login_failed_count AS  failed,ip_address,location,login_attempt")->from("users");   
        $q = $this->db->get();
        return ($q->num_rows() > 0) ? $q->result() : false;
    }
}

?>