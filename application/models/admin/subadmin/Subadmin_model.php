<?php

class Subadmin_model extends CI_Model {

    protected $table = "subadmin_controllers";

    function __construct() {
        parent::__construct();
    }

    public function getlist() {
        $this->db->where("role_id", 2);
        $this->db->select("id,name,email,contact,is_blocked")->from("admin");
        $q = $this->db->get();
        
        return ($q->num_rows() > 0) ? $q->result() : false;
    }

    public function update_user_status_model($data) {
        $this->db->where("id", $data['user_id']);
        $this->db->update("admin", ["is_blocked" => $data['status']]);
        return true;
    }

    public function update_edit_info_model($data) {
        if (!$data['access_controller']) {
            $this->db->where("id", $data['user_id']);
            $this->db->update("admin", ["is_blocked" => $data['status']]);
        } else {
            $this->db->where("user_id", $data['user_id']);
            $this->db->where("access_controller", $data['access_controller']);
            $this->db->update("sub_admin_privilege", $data);
            
        }
        return true;
    }

    public function addSubAdmin($data) {
        $r = $this->db->insert("admin", ["name" => $data['full_name'], "contact" => $data['phone'], "email" => $data['email'], "password" => md5($data['password'])]);
        $last_id = $this->db->insert_id();
        $array = array();
        //if ($data['control_id']) {
            for ($i = 0; $i <= 7; $i++) {
                if (in_array(($i + 1), $data['control_id'])) {
                    $array[] = [
                        "user_id" => $last_id,
                        "access_controller" => ($i + 1),
                        "status" => 0,
                    ];
                } else {
                    $array[] = [
                        "user_id" => $last_id,
                        "access_controller" => ($i + 1),
                        "status" => 1,
                    ];
                }
            }
        //}
        $this->db->insert_batch("sub_admin_privilege", $array);
    }

    public function getModule() {
        $this->db->select("id,module_name")->from("subadmin_controllers");
        $q = $this->db->get();
        return ($q->num_rows() > 0) ? $q->result() : false;
    }

    public function getAdminPrivilege($id) {
        $this->db->where("sub_admin_privilege.user_id", $id);
        $this->db->select("sub_admin_privilege.user_id,sub_admin_privilege.access_controller,sub_admin_privilege.status,admin.name,admin.contact,admin.email,admin.is_blocked")->from("sub_admin_privilege");
        $this->db->join("admin", "admin.id=sub_admin_privilege.user_id", "INNER");
        $q = $this->db->get();
        return ($q->num_rows() > 0) ? $q->result_array() : false;
    }

}

?>