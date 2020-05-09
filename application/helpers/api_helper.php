<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------------
  | This Function will remove the existing image in case of update
  |--------------------------------------------------------------------------------
 */
if (!function_exists("remove_existing_img")) {

    function remove_existing_img($user_id, $tbl_name, $tbl_field, $file_path) {
        $CI = &get_instance();
        $CI->db->select($tbl_field)
                ->from($tbl_name)
                ->where("id", $user_id);
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            $image = $query->row_array();

            if ($image[$tbl_field] != '' && !empty($image[$tbl_field])) {
                if (file_exists($file_path . '/' . $image[$tbl_field])) {
                    unlink($file_path . '/' . $image[$tbl_field]);
                }
            }
        }
        return false;
    }

}

/*
  |--------------------------------------------------------------------------------
  | This Function will user to get new access token
  |--------------------------------------------------------------------------------
 */
if (!function_exists('getaccessToken')) {

    function getaccessToken($string = 1) {
        return md5($string . time());
    }

}


/*
  |--------------------------------------------------------------------------------
  | This Function will user for checking keys existance
  |--------------------------------------------------------------------------------
 */
if (!function_exists('keys_existance_check')) {

    function keys_existance_check($user_data, $table) {
        $CI = &get_instance();
        $fields = $CI->db->list_fields($table);
        $result = array_intersect_key($user_data, array_flip($fields));
        return count($result) > 0 ? array_filter($result) : false;
    }

}

/*
  |--------------------------------------------------------------------------------
  | This Function will use for checking the content type of the headers
  |--------------------------------------------------------------------------------
 */
if (!function_exists('content_type')) {

    function content_type($val) {
//        if (isset($val['Content-Type'])) {
//            if ($val['Content-Type'] == "application/json" || $_SERVER['CONTENT_TYPE'] == $val['Content-Type']) {
//                return true;
//            } else {
//                echo json_encode(["messaged" => "Required parameter is missing"]);
//                die;
//            }
//        } else {
//            echo json_encode(["message" => "Requdired parameter is missing"]);
//            die;
//        }
    }

}

if (!function_exists('display_api_response')) {

    function display_api_response($data = array(), $status = false, $message = null, $is_auth = "no_auth",$result_key="payload") {

        $response = array();
        $response = [
            "status" => $status,
            "message" => $message,
            "payload" => (object) $data
        ];
        if ($is_auth != 'no_auth') {         
            $response['authrizedStatus'] = is_user_authorization_check($is_auth);
        }
        return $response;
    }

}

if (!function_exists('display_api_res')) {

    function display_api_res($data = array(), $status = false, $message = null, $is_auth = "no_auth",$result_key="payload") {

        $response = array();
        $response = [
            "status" => $status,
            "message" => $message,
            $result_key => $data
        ];
        if ($is_auth != 'no_auth') {         
            $response['authrizedStatus'] = is_user_authorization_check($is_auth);
        }
        return $response;
    }

}

/*
  |--------------------------------------------------------------------------------
  | Check the existance of User Token or Id in our Database
  |--------------------------------------------------------------------------------
 */
if (!function_exists('check_acces_token')) {

    function check_acces_token($header, $table = "users") {
        if ($header['Authorization'] != '' && $header['Authorization'] != null) {
            $CI = &get_instance();
            $where_cond = ['access_token' => $header['Authorization']];
            $query = $CI->db->get_where($table, $where_cond);
            if ($query->num_rows() == 0) {
                //echo json_encode(["message" => "access token not found in database","is_token_expire" => true]);
                $msg = "Access token not found in our Database";
                $data = display_api_response(array(), false, $msg);
                $data['responseCode'] = 1001;
                echo json_encode($data);
                die;
            }
        } else {
            $msg = "Access token can not empty";
            $data = display_api_response(array(), false, $msg);
            $data['responseCode'] = 1001;
            echo json_encode($data);
            //echo json_encode(["message" => "", "status" => false]);
            die;
        }
    }

}
if (!function_exists('is_user_authorization_check')) {

    function is_user_authorization_check($header, $table = 'users') {
        $CI = &get_instance();
        $where_cond = ['access_token' => $header['Authorization']];
        $query = $CI->db->get_where($table, $where_cond);
        $row = $query->row_array(); 
        return ($row['is_blocked'])? 0 : 1;
    }

}
if (!function_exists('make_boolean')) {

    function make_boolean($data) {
        return ($data) ? true : false;
    }

}
/*
  |--------------------------------------------------------------------------------
  | This function Used for the language change
  |--------------------------------------------------------------------------------
 */
if (!function_exists('change_languge')) {

    function change_languge($header, $lang = []) {
        $CI = &get_instance();     
        if (@$header['Accept-Language'] == "spn" && isset($header['Accept-Language'])) {
            //$CI->lang->load($lang[1], 'spanish');
            $CI->lang->load("api_messages", 'spanish');
        } else {
           
            $CI->lang->load("api_messages");
            //$CI->lang->load($lang[0]);
        }
    }

}

/*
  |-----------------------------------------------------------------------------------------------
  | This function will check keys existance in form array ,if key not exist in array then return false other true
  |------------------------------------------------------------------------------------------------
 */
if (!function_exists('check_form_array_keys_existance')) {

    function check_form_array_keys_existance($form_array = [], $field_name = []) {
        if (!empty($field_name)) {
            foreach ($field_name as $key => $value) {
                if (!array_key_exists(trim($value), $form_array)) {
                    return false;
                }
            }
            return true;
        }
    }

}

/*
  |-----------------------------------------------------------------------------------------------
  | This function will check value of user input values
  |------------------------------------------------------------------------------------------------
 */
if (!function_exists('check_user_input_values')) {

    function check_user_input_values($user_input = []) {
        if (!empty($user_input)) {
            foreach ($user_input as $key => $value) {
                if (is_array($value) && count($value) > 0) {
                    foreach ($value as $k => $v) {
                        if (trim($value[$k]) == "") {
                            return false;
                        }
                    }
                } else {
                    if ($user_input[$key] == "") {
                        return false;
                    }
                }
            }
            return true;
        }
    }

}
/*
  |-----------------------------------------------------------------------------------------------
  | This function will remove illagel space from array
  |------------------------------------------------------------------------------------------------
 */
if (!function_exists('form_input_filter')) {

    function form_input_filter($form_input = []) {
        if (!empty($form_input)) {
            return array_map('trim', $form_input);
        }
    }

}

/*
  |-----------------------------------------------------------------------------------------------
  | This function will find the key with match another array
  |------------------------------------------------------------------------------------------------
 */
if (!function_exists('form_array_key_inersection')) {

    function form_array_key_inersection($form_input, $fields_name) {
        return array_intersect_key($form_input, array_flip($fields_name));
    }

}

/*
  |-----------------------------------------------------------------------------------------------
  | Get All Appointment upcoming/recent/future of doctor
  |------------------------------------------------------------------------------------------------
 */
// if (!function_exists('get_all_appointment')) {
//     function get_all_appointment($condition,$status) {
//         $CI = &get_instance();
//         $CI->db->select(
//        "a.id, 
//          GROUP_CONCAT(c.name SEPARATOR ',') AS symptoms, 
//          Concat(d.first_name, ' ', d.last_name) AS name, 
//          Floor(Datediff(Curdate(), d.date_of_birth) / 365.25) AS age, 
//          d.gender, 
//          a.patient_availability_date_and_time,  
//          e.title AS type, 
//          a.status"
//         );
//         $CI->db->from("appointment as a");
//         $CI->db->join("appointment_symptom as b","a.id = b.appointment_id","INNER");
//         $CI->db->join("symptom AS c","c.id = b.symptom_id","INNER");
//         $CI->db->join("patient_info AS d","a.patient_id = d.id","LEFT");
//         $CI->db->join("provider_plan AS e","e.id = a.treatment_provider_plan_id","INNER");
//         $this->db->where($condition);
//         $this->db->where_in($status);
//         $this->db->group_by('b.appointment_id'); 
//         $query = $this->db->get()
//     }
// }

/*
  |-----------------------------------------------------------------------------------------------
  | Get All Appointment upcoming/recent/future of doctor
  |------------------------------------------------------------------------------------------------
 */
if (!function_exists('get_all_appointment')) {

    function get_all_appointment($condition, $status, $limit = null, $prescritpion = null, $language = null) {
        $CI = &get_instance();
        $medicantion_name = '';
        $cond = '';
        //echo $prescritpion;die;
        $CI->db->where("all_appointment.doctor_id IS NOT NULL");
        $CI->db->where($condition);
        $CI->db->where_in("all_appointment.status", $status);
        $CI->db->order_by("all_appointment.appointment_id", "DESC");
        if ($limit == null) {
            $CI->db->limit('3');
        }
        if ($prescritpion != null) {
            //$CI->db->where("all_appointment.status",6);
            $CI->db->group_by("all_appointment.appointment_id");
            $medicantion_name = ($language == "spn") ? "medication.sp_name" : "medication.name";

//          $cond= "GROUP_CONCAT(
//          medication_info.id,'|',
//          medication_info.quantity,'|',
//          medication_info.dosage,'|',
//          medication_info.refill,'|',
//          medication_info.medication_instruction SEPARATOR '||||') as prescription";
//        }
            $cond = "GROUP_CONCAT(
         $medicantion_name SEPARATOR '||||') as medications";
        }
        $CI->db->select(
                "all_appointment.appointment_id as id,all_appointment.patient_med_id AS med_id,all_appointment.symptoms,all_appointment.name,all_appointment.age,all_appointment.gender,
         all_appointment.patient_availability_date_and_time,all_appointment.type,CONCAT(all_appointment.status,'|',all_appointment.appointment_status) as appointment_status,$cond"
        );


        $CI->db->from("all_appointment");
        if ($prescritpion != null) {
            $CI->db->join("prescriptions", "prescriptions.appointment_id=all_appointment.appointment_id", "LEFT");
            $CI->db->join("prescription_medication", "prescriptions.prescription_id = prescription_medication.prescription_id", "LEFT");
            $CI->db->join("medication_info", "medication_info.id = prescription_medication.medication_info_id", "LEFT");
            $CI->db->join("medication", "medication.id = medication_info.medication_id", "LEFT");
        }

        $query = $CI->db->get();
        //echo $CI->db->last_query();die;
        //dd($query->result_array());
        return (count($query->result_array()) > 0) ? $query->result_array() : [];
    }

}


if (!function_exists('get_user_appointment')) {

    function get_user_appointment($condition, $status, $limit = null, $prescritpion = null, $language = null, $extra = null) {
        $CI = &get_instance();
        $medicantion_name = '';
        $data = '';
        $CI->db->where($condition);
        //if($extra != null){
        $CI->db->where("a.doctor_id IS NOT NULL");
        //}
        $CI->db->where_in("a.status", $status);
        $CI->db->order_by("a.appointment_id", "DESC");
        if ($limit == null) {
            $CI->db->limit('3');
        }
        if ($prescritpion != null) {
            //$CI->db->where("all_appointment.status",6);
            $CI->db->group_by("a.appointment_id");
            $medicantion_name = ($language == "spn") ? "medication.sp_name" : "medication.name";
            $data = "GROUP_CONCAT(
             $medicantion_name SEPARATOR '||||') as medications";
        }
        $CI->db->select(
                "a.appointment_id as id,a.med_id,a.symptoms,a.doctor as name,a.doctor_age as age,a.doctor_gender as gender,
         a.patient_availability_date_and_time,a.type,CONCAT(status,'|',appointment_status) as appointment_status,$data"
        );
        //$q = "SELECT appointment_id,symptoms,name,age,gender,patient_availability_date,patient_availability_time,type FROM up_cancel_appointment";
        //$query =$CI->db->query($q);
        $CI->db->from("all_appointment as a");
        $CI->db->join("patient_info AS b", "b.id = a.patient_id", "INNER");
        if ($prescritpion != null) {
            $CI->db->join("prescriptions", "prescriptions.appointment_id=a.appointment_id", "LEFT");
            $CI->db->join("prescription_medication", "prescriptions.prescription_id = prescription_medication.prescription_id", "LEFT");
            $CI->db->join("medication_info", "medication_info.id = prescription_medication.medication_info_id", "LEFT");
            $CI->db->join("medication", "medication.id = medication_info.medication_id", "LEFT");
        }

        $query = $CI->db->get();
        // echo $CI->db->last_query();die;
        //dd($query->result_array());
        return (count($query->result_array()) > 0) ? $query->result_array() : [];
    }

}

/*
  |-----------------------------------------------------------------------------------------------
  | Get date from days
  |------------------------------------------------------------------------------------------------
 */
if (!function_exists('get_date')) {

    function get_date($days) {
        $CI = &get_instance();
        $alldate = [];
        $endDate = strtotime(date('Y-m-d', strtotime("+30 days")));

        $CI->db->select("id,date_available")->from("date_availability_list");
        //$CI->db->where("date_available", date('Y-m-d', $i));
        $CI->db->where("date_available", $days);
        $query = $CI->db->get();
        if ($query->num_rows() == 0) {
            $CI->db->insert("date_availability_list", ['date_available' => $days]);
            //$alldate[]['date_available'] = date('Y-m-d', $i);
            $alldata[] = $CI->db->insert_id();
        } else {
            $id = $query->row_array();
            $alldata[] = $id['id'];
        }

        return $alldata;
    }

}
/*
  |-----------------------------------------------------------------------------------------------
  | Make a get weekly slot
  |------------------------------------------------------------------------------------------------
 */
if (!function_exists('get_weekly_slot')) {

    function get_weekly_slot($day, $doctor_id, $date_id) {
        $slot_id = array();
        $final_array = [];
        foreach ($day['time_list'] as $k => $v) {
            $slots = get_slot_id($v['from'], $v['to'], $slot_id);
            foreach ($slots as $key => $value) {
                $final_array[] = [
                    "doctor_id" => $doctor_id,
                    "date_id" => $date_id[0],
                    "slot_id" => $value
                ];
            }
        }
        return $final_array;
    }

}
/*
  |-----------------------------------------------------------------------------------------------
  | get slot id if yes then get id otherwise insert id
  |------------------------------------------------------------------------------------------------
 */

if (!function_exists('get_slot_id')) {

    function get_slot_id($from_time, $to_time, $slot) {
        //$starttime = '12:00';  // your start time
        //$endtime = '16:00';  // End time
        $CI = &get_instance();

        $interval = 1800; // Interval in seconds

        $date_first = $from_time;
        $date_second = $to_time;

        $time_first = strtotime($date_first);
        $time_second = strtotime($date_second);

        for ($i = $time_first; $i < $time_second; $i += $interval) {
            $start = date('H:i', $i);
            $end = date('H:i', ($i + $interval));

            $CI->db->select("id")->from("hour_list");
            $CI->db->where("start_time", $start);
            $CI->db->where("end_time", $end);
            $query = $CI->db->get();
            $daily_slot_id = $query->row_array();

            if ((Int) $daily_slot_id['id'] > 0) {
                $slot[] = $daily_slot_id['id'];
            } else {
                $data = array();
                $data['start_time'] = $start;
                $data['end_time'] = $end;
                $CI->db->insert("hour_list", $data);
                $slot[] = $CI->db->insert_id();
            }
        }

        return $slot;
    }

}
/*
  |-----------------------------------------------------------------------------------------------
  | Make a symptoms array
  |------------------------------------------------------------------------------------------------
 */
if (!function_exists('appointment_array')) {

    function appointment_array($data) {
        //dd($data);
        if (count($data) > 0) {
            foreach ($data as $k => $v) {

                $data[$k]['symptoms'] = explode(",", $v['symptoms']);
                $data[$k]['appointment_status'] = array_combine(["id", "status"], explode("|", $v['appointment_status']));
                if (isset($v['medications']) && $v['medications'] != '') {
                    $data[$k]['medications'] = explode("||||", $v['medications']);
                }
            }
            return $data;
        } else {
            return [];
        }
    }

// when we need prescritpion detail of appointment
    /*
     *     function appointment_array($data) {
      dd($data);
      if (count($data) > 0) {
      $inc= 0;
      foreach ($data as $k => $v) {

      $data[$k]['symptoms'] = explode(",", $v['symptoms']);
      $data[$k]['appointment_status'] = array_combine(["id","status"],explode("|",$v['appointment_status']));
      if($v['prescription'] && $v['prescription']!=''){
      $data[$k]['prescription'] = explode("||||", $v['prescription']);
      foreach ($data[$k]['prescription'] as $key=>$value) {
      unset($data[$k]['prescription'][$key]);
      $data[$k]['prescription'][$inc] =array_combine(['id','quantity','dosage','refill','medication_instruction'], explode("|", $value));
      //$data[$k]['prescription'][] =explode("|", $value);
      $inc++;
      }
      }
      $inc=0;
      }
      return $data;
      } else {
      return [];
      }
      }
     */
}


/*
  |-----------------------------------------------------------------------------------------------
  | Get get User id by token create by pawan
  |------------------------------------------------------------------------------------------------
 */
if (!function_exists('getUserIdByToken')) {

    function getUserInfoByToken($token) {
        $CI = &get_instance();
        $CI->db->where_in("access_token", $token);
        $CI->db->select("*");
        $CI->db->from("users");
        $query = $CI->db->get();
        return $query->row();
    }

}

if (!function_exists('getDoctorInfoByToken')) {

    function getDoctorInfoByToken($token) {
        $CI = &get_instance();
        $CI->db->where_in("access_token", $token);
        $CI->db->select("*");
        $CI->db->from("doctors");
        $query = $CI->db->get();
        return $query->row();
    }

}
/*
  |--------------------------------------------------------------------------------
  | This function Used for the get prescriptions
  |--------------------------------------------------------------------------------
 */
if (!function_exists('get_prescriptions')) {

    function get_prescriptions($appointemnt_id, $language) {
        $CI = &get_instance();
        $medicantion_name = ($language == "spn") ? "medication.sp_name" : "medication.name";

        $app_condition = "prescriptions.appointment_id =(SELECT id FROM appointment WHERE id='" . $appointemnt_id . "' AND status = 6 )";
        $CI->db->where($app_condition);
        $CI->db->select("prescriptions.prescription_id,
        $medicantion_name,
        medication_info.quantity,
        medication_info.refill,
        medication_info.dosage,
        medication_info.medication_instruction");

        $CI->db->from("prescriptions");
        $CI->db->join("prescription_medication", "prescriptions.prescription_id = prescription_medication.prescription_id", "INNER");
        $CI->db->join("medication_info", "medication_info.id = prescription_medication.medication_info_id", "INNER");
        $CI->db->join("medication", "medication.id = medication_info.medication_id", "INNER");

        $q = $CI->db->get();
        return (!empty($q->result_array())) ? $q->result_array() : array();
    }

}

if (!function_exists('boolean_parse')) {

    function boolean_parse($data = '') {
        return ($data) ? "1" : '0';
    }

}
if (!function_exists('make_specaility_array')) {

    function make_array_format($data, $k = "speciality") {
        $array = explode("##", $data);
        $finaly_array = [];
        if (count(array_filter($array)) > 0 && !empty($array)) {

            foreach ($array as $key => $value) {
                $arr = explode("|||", $value);
                $finaly_array[] = ["id" => $arr[1], $k => $arr[0]];
            }
        } else {
            $finaly_array = null;
        }
        return $finaly_array;
    }

}
//------------------------------------------------------------------------------------------------------------
if (!function_exists('dd')) {

    function dd($data = []) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        die;
    }

}
if (!function_exists('vd')) {

    function vd($data = []) {
        var_dump($data);
        die;
    }

}
?>