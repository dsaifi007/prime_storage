<?php

if (!function_exists('display_message_info')) :

    function display_message_info($message = []) {
        $html = '';
        foreach ($message as $k => $v) :
            if (!$v) : continue;
            endif;
            $alertCls = ($k == '1') ? 'alert alert-info fade in' : 'alert-danger';
            $html .= '<div class="alert ' . $alertCls . '">';
            //$html .= '<button class="close" data-close="alert"></button>';
            $html .= '<span class="message">' . $v . '</span>';
            $html .= '</div>';
        endforeach;
        return $html;
    }

endif;
if (!function_exists('display_left_navigation')) :

    function display_left_navigation() {
        $CI = &get_instance();
        $user_id = $CI->session->userdata("id");
        $CI->db->where("sub_admin_privilege.user_id", $user_id);
        //$CI->db->where("sub_admin_privilege.status", 0);
        $CI->db->select("sub_admin_privilege.user_id,subadmin_controllers.module_name,sub_admin_privilege.status")->from("subadmin_controllers");
        $CI->db->join("sub_admin_privilege", "sub_admin_privilege.access_controller=subadmin_controllers.id", "INNER");
        $q = $CI->db->get();
        return ($q->num_rows() > 0) ? $q->result_array() : array();
    }

endif;
?>