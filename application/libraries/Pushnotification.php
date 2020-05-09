<?php

class Pushnotification {

    private static $API_SERVER_KEY = 'AAAA-O_JtCE:APA91bHlDsIGs1t_nG2MTZJOQuQ8URmbTmEC6ondyC9SUFlmzfswIEMNZXixYLl4QcWVxV8v7XUdWOOvFFhieHdvUz_Fwj5GDlDv-OGwt5Y8XALgngHwqWlJYI7SiqhEIin3CA0apWIK';


    private static $is_background = "TRUE";

    public function sendPushNotificationToFCMSever($token, $message, $title = null) {
        $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            'registration_ids' =>$token , //$token,
            'priority' => "high", //10,
            'notification' => $title, //$title,
            'data' =>$message //$message, //$message
        );

        $headers = array(
            'Authorization:key=' . self::$API_SERVER_KEY,
            'Content-Type:application/json'
        );

        // Open connection  
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post   
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        // if ( $status != 201 ) {
        //   die("Error: call to URL $ch failed with status $status, response $result, curl_error " . curl_error($ch) . ", curl_errno " . curl_errno($ch));
        // }
        // Close connection      
        curl_close($ch);
        return $result;
    }


}

?>