<?php

/*
  |-------------------------------------------------------------------------------
  |  All Users information get/set/save/delete
  |-------------------------------------------------------------------------------
 */

class User_model extends CI_Model {

    private $users_table = "users";
    private $array = [];

    function __construct() {
        parent::__construct();
    }

    /*
      |-------------------------------------------------------------------------------------------------------------------------------
      | This Function will insert the user info and get particular data of inserted users
      |-------------------------------------------------------------------------------------------------------------------------------
     */

    public function createAccountDataInsert($post_data = []) {
        $post_data['password'] = md5($post_data['password']);
        $post_data['created_date'] = $this->config->item("date_time");
        $post_data['access_token'] = getaccessToken();
        $post_data['login_attempt'] = 1;
        $this->db->insert($this->users_table, $post_data);

        #
        $this->db->where("id", $this->db->insert_id());
        $query = $this->db->select("id,full_name,user_image_url,phone,access_token,email,is_blocked AS is_authorized,notification_status")
                        ->from($this->users_table)->get();
        $row = $query->row_array();
        $this->array['is_authorized'] = make_boolean($row['is_authorized']);
        return array_merge($row, $this->array);
    }

    public function updatestate($data, $type) {

        $this->db->reset_query();
        $this->db->where('email', $data['email']);
        if ($type) {
            $this->db->set('login_attempt', 'login_attempt+1', FALSE);
        } else {
            $this->db->set('login_failed_count', 'login_failed_count+1', FALSE);
        }
        $this->db->update($this->users_table, ["ip_address" => (isset($data['ip_address'])) ? $data['ip_address'] : NULL, 'location' => (isset($data['location'])) ? $data['location'] : NULL]);
    }

    # This function is not working

    public function add_device_token_detail($data) {
        # Delete the old device entry  
        $this->db->where("device_id", $data['device_id']);
        $this->db->delete("device_detail");

        #==========================================================
        # insert the device detail
        $this->db->insert("device_detail", $data);
    }

    public function userLoginModel($post_data = []) {
        $this->db->where(["email" => $post_data['email'], "password" => md5($post_data['password'])]);
        $query = $this->db->select("id,full_name,user_image_url,phone,access_token,email,is_blocked AS is_authorized,notification_status")
                        ->from($this->users_table)->get();

        if ($query->num_rows() > 0) {
            $row = $query->row_array();

            // update the access token =========================================================================
            $this->db->where("email", $post_data['email']);
            $this->db->update($this->users_table, ["device_type" => $post_data['device_type'], "device_id" => $post_data['device_id'], "device_token" => $post_data['device_token']]);
            //==================================================================================================
            $this->array['is_authorized'] = ($row['is_authorized'] == 0 ) ? false : true;
            return array_merge($row, $this->array);
        } else {
            return false;
        }
    }

    public function update_profile_img_update($user_id, $img_url, $img) {
        $this->db->reset_query();
        $this->db->where('id', $user_id);
        $this->db->update($this->users_table, ["user_image_url" => $img_url, 'user_image' => $img]);
        return true;
    }

    public function check_email_existence($email) {
        $query = $this->db->get_where("users", ["email" => $email]);
        return ($query->num_rows() > 0) ? true : false;
    }

    public function update_user_password($email, $pass) {
        $this->db->reset_query();
        $this->db->where('email', base64_decode($email));
        $this->db->update($this->users_table, ["password" => md5($pass), "email_link_status" => 1]);
        return true;
    }

    public function update_profile($data) {

        $this->db->where('id', $data['user_id']);
        unset($data['user_id']);
        $this->db->update($this->users_table, $data);
        return true;
    }

    public function change_password($data) {

        $this->db->where('id', $data['user_id']);
        unset($data['user_id']);
        $access_token = getaccessToken();
        $this->db->update($this->users_table, ["password" => md5($data['password']), "access_token" => $access_token]);
        return $access_token;
    }

    public function remove_profile_img($user_id) {
        $this->db->where("id", $user_id);
        $this->db->update("users", ["user_image_url" => NULL, "user_image" => NULL]);
        return true;
    }

    public function get_old_pass($data) {
        $this->db->where(["id" => $data['user_id'], "password" => md5($data['old_password'])]);
        $query = $this->db->select("password")->from("users")->get();
        return ($query->num_rows() > 0) ? $query->row_array() : false;
    }

    public function email_verified($email) {
        $this->db->where(["email" => $email, "email_link_status" => 0]);
        $query = $this->db->select("id,DATE_ADD(forgot_email_sent_time , INTERVAL 24 HOUR) AS datetime")->from("users")->get();
        return ($query->num_rows() > 0) ? $query->row_array() : false;
    }

    public function update_email_link_status($email) {
        $this->db->where(["email" => $email]);
        $this->db->update("users", ["email_link_status" => 0, "forgot_email_sent_time" => $this->config->item("date_time")]);
    }

    public function need_help_model($data) {
        unset($data['result_key']);
        $data['created_at'] = $this->config->item("date_time");
        $this->db->insert("need_help", $data);
    }

    public function get_contact_us_model() {
        $this->db->select("email,phone")->from("contact_us");
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->row_array() : false;
    }

    public function get_about_us_model() {
        $this->db->select("about_text AS about_us")->from("about_us");
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->row() : false;
    }

    public function get_terms_condtn_model() {
        $this->db->select("text")->from("terms_conditions");
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->row() : false;
    }

    function get_user_detail_model($user_id) {
        $this->db->where("id", $user_id);
        $this->db->select("id,full_name,user_image_url,phone,access_token,email,notification_status")->from("users");
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->row() : false;
    }

    public function user_logout_model($user_id) {
        $access_token = getaccessToken();
        $this->db->where("id", $user_id);
        $this->db->update($this->users_table, ["access_token" => $access_token]);
        return $access_token;
    }

    public function add_app_issue_model($data) {
        unset($data['result_key']);
        $data['created_at'] = date("Y-m-d H:i:s");
        $this->db->insert("app_issue", $data);
    }

    public function get_all_offer_featured_model() {
        $this->db->select("id,name,status")->from("offer_featured_list");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function GetStoreRatingModal($user_id) {
        $this->db->group_by("rating_review.storage_id");
        $this->db->where("rating_review.user_id", $user_id['user_id']);
        $this->db->select("storage_list.id, rating_review.storage_id,storage_list.site_name,storage_list.site_address1,AVG(ps_rating_review.rating) AS rating,rating_review.review")->from("rating_review");
        $this->db->join("storage_list", "storage_list.id=rating_review.storage_id");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function userNotificationStatusChange_model($data) {
        $this->db->where("id", $data['user_id']);
        $this->db->update("users", ["notification_status" => (int) $data['notification_status']]);
        
        return true;
    }
    public function user_info_modal($uid) {
        $this->db->where("id",$uid);
        $data = $this->db->select("id,full_name,email")->from("users")->get();
        return $data->row_array();
    }
}

?>