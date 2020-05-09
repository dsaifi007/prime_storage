<?php

/*
  |-------------------------------------------------------------------------------
  |  All Users information get/set/save/delete
  |-------------------------------------------------------------------------------
 */

class Sitelink_model extends CI_Model {

    private $table = "storage_list";

    function __construct() {
        parent::__construct();
    }

    function checkStorage($site) {
        $this->db->where("site_id", $site);
        $this->db->select("site_id")->from("storage_list");
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        return ($query->num_rows() > 0) ? true : false;
    }

    public function insertStorage($data) {
        if ($data)
            $this->db->insert_batch($this->table, $data);
        return true;
    }

    public function getStorageLocationBasedOnLatLong($data) {
        $index = ($data['index'] == 0) ? 0 : $data['index'];
        //$this->db->limit(2, $index);
        $this->db->group_by("storage_list.id");
        $this->db->having("distance <= 25");
        $this->db->select("storage_list.id,storage_list.site_id,storage_list.latitude,storage_list.longitude,storage_list.location_code,storage_list.site_name,storage_list.site_address1,storage_list.city,storage_list.postal_code,storage_list.country,SUBSTR(AVG(ps_rating_review.rating),1,4) AS avg_rating,(
        3956 * 2 * ASIN(
            SQRT(
              POWER(
                SIN(
                  (" . $data['latitude'] . " - `latitude`) * PI() / 180 / 2),
                  2
                ) + COS(" . $data['latitude'] . " * PI() / 180) * COS(`latitude` * PI() / 180) * POWER(
                  SIN(
                    (" . $data['longitude'] . " - `longitude`) * PI() / 180 / 2),
                    2
                  )
                ))) AS distance");
        $this->db->from("storage_list");
        $this->db->join("rating_review","rating_review.storage_id=storage_list.id","LEFT");
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        return ($query->num_rows() > 0) ? $query->result_array() : false;
    }

    public function get_search_by_city($city) {
        $this->db->like("storage_list.city", $city, "after");
        $this->db->select("storage_list.id,storage_list.site_id,storage_list.location_code,storage_list.site_name,storage_list.site_address1,storage_list.city,storage_list.postal_code,storage_list.country,storage_list.latitude,storage_list.longitude,SUBSTR(AVG(ps_rating_review.rating),1,4) AS avg_rating");
        $this->db->from("storage_list");
        $this->db->join("rating_review","rating_review.storage_id=storage_list.id","LEFT");
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->result_array() : false;
    }

    public function update_payment_status($id) {
        $this->db->where("id", $id);
        $this->db->update("reservation_history", ['payment_status' => "payment_done"]);
        return true;
    }

    public function add_storage_rating($data) {
        unset($data['result_key']);
        $data['created_at'] = date("Y-m-d H:i:s");
        $this->db->insert("rating_review", $data);
    }

    public function get_user_reservation_history($user_id) {
        $this->db->order_by("id","DESC");
        $this->db->where("user_id", $user_id);
        $this->db->select("id,user_id,storage_id,start_date,end_date,address,zip AS postal_code,size,payment_status AS status");
        $this->db->from("reservation_history");
        $q = $this->db->get();
        return $q->result_array();
    }

  public function get_reservation_detail($id) {
        $this->db->group_by("rating_review.reservation_id");
        $this->db->where("reservation_history.id", $id);
        $this->db->select("reservation_history.id,storage_list.latitude,storage_list.longitude,reservation_history.user_id,reservation_history.start_date,reservation_history.end_date,reservation_history.address,reservation_history.zip AS postal_code,reservation_history.size,reservation_history.payment_status AS status,reservation_history.price,AVG(ps_rating_review.rating) AS store_rating");
        $this->db->join("users", "users.id = reservation_history.user_id", "INNER");
        $this->db->join("storage_list", "storage_list.id = reservation_history.storage_id", "INNER");
        $this->db->join("rating_review", "rating_review.reservation_id = reservation_history.id", "LEFT");
        $this->db->from("reservation_history");
        $q = $this->db->get();
        
        return $q->row_array();
    }

    public function getTanentData($id) {
        $this->db->where("reservation_history.id", $id);
        $this->db->select("users.device_token,reservation_history.id,reservation_history.site_id,reservation_history.lo_code,reservation_history.unit_id,reservation_history.tenant_id,reservation_history.access_code,reservation_history.start_date,reservation_history.sitelink_formated_start_date,reservation_history.sitelink_formated_end_date,reservation_history.end_date,reservation_history.price")->from("reservation_history");
        $this->db->join("users","users.id = reservation_history.user_id","INNER");
        $q = $this->db->get();
        return $q->row_array();
    }

    public function addTanentInsert($data) {
        $this->db->where("id", 7);
        $this->db->select("id,status");
        $q = $this->db->from("offer_featured_list")->get();
        $status = $q->row();
        if ($status->status == 1) {
            // Tantative booking
            $data['payment_status'] = "payment_tentative";
        }else{
            $data['payment_status'] = "payment_not_done";
        }
        $this->db->insert("reservation_history", $data);
        return $this->db->insert_id();
    }

// end
}

?>