<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Subadmin_authenticate {

    public $CI;

    public function __construct() {
        $this->CI = &get_instance();
    }

    public function checkss_authenticate() {
        $current_class = $this->CI->router->class;
        //$method = $this->CI->router->method;
        $user_id = $this->CI->session->userdata("id");

        //$role_id = $this->CI->session->userdata("role_id");
        $this->CI->db->where("sub_admin_privilege.user_id", $user_id);
        $this->CI->db->where("sub_admin_privilege.status", 1);
        $this->CI->db->select("sub_admin_privilege.user_id,subadmin_controllers.controller_name,sub_admin_privilege.status")->from("subadmin_controllers");
        $this->CI->db->join("sub_admin_privilege", "sub_admin_privilege.access_controller=subadmin_controllers.id", "INNER");
        $q = $this->CI->db->get();
        if ($q->num_rows() > 0) {

            $controller = array();
            $result = $q->result_array();

            foreach ($result as $key => $value) {
                $controller[] = $value['controller_name'];
            }
            // Not Authroized
            if (in_array($current_class, $controller)) {
                exit($this->CI->load->view("unauthorized", null, true));
            }
        }

        # Restrict when admin block in between the access of admin panel
        $this->CI->db->where("id", $user_id);
        $this->CI->db->where("is_blocked", 1);
        $q = $this->CI->db->select("id,is_blocked")->from("admin")->get();
        //$row = $q->row_array();
        if($q->num_rows()>0){
            exit($this->CI->load->view("unauthorized", null, true));
        }
    }

}

?>