<?php

class Offer_model extends CI_Model {

    public function offerList() {

        $query = $this->db->select("*")->from("offer_featured_list")->get();
        return ($query->num_rows() > 0) ? $query->result_array() : false;
    }
    public function update_feature_status_modal($data) {
        $this->db->where("id",$data['offer_id']);
        $this->db->update("offer_featured_list",["status"=>$data['status']]);
    }
}

?>