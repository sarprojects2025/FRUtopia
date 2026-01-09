<?php
// include($_SERVER['DOCUMENT_ROOT'] . '/FRUtopia/api/config/config.php');
include(__DIR__ . '/config/config.php');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$created_at = date('Y-m-d H:i:s');
$todays_date = date('Y-m-d');


$response = array();

// $userid = isset($data['userid']) ? $data['userid'] : '' ;

$alert_list = mysqli_query($con, "select * from alert_otp_request where DATE(requested_at) = CURDATE() order by id DESC");
if (mysqli_num_rows($alert_list) > 0) {
    $details = [];
    while ($fetchall = mysqli_fetch_assoc($alert_list)) {
        $id = $fetchall['id'];
        $user_id = $fetchall['user_id'];
        $access_type = $fetchall['type_access'];
        $requested_at = $fetchall['requested_at'];
        $requested_status = $fetchall['requested_status'];
        $remark = $fetchall['remark'];
        $updated_at = $fetchall['updated_at'];
        $updated_by = $fetchall['updated_by'];
        $latitude = $fetchall['latitude'];
        $longitude = $fetchall['longitude'];
        $location = $fetchall['location'];
        
        $usernamesql = mysqli_query($con, "select name,contact_no,email_id from user_login where id='$user_id' ");
        $fetch_username = mysqli_fetch_assoc($usernamesql);
        $username = $fetch_username['name'];
        $usercontact_no = $fetch_username['contact_no'];
        $user_emailid = $fetch_username['email_id'];
        
        $details[] = [
            'alertid' => $id,
            'userid' => $user_id,
            'usercontact_no' => $usercontact_no,
            'username' => $username,
            'user_emailid' => $user_emailid,
            'access_type' => $access_type,
            'requested_at' => $requested_at,
            'requested_status' => $requested_status,
            'remark' => $remark,
            'updated_at' => $updated_at,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'location' => $location
            
        ];
    }
    $response = [
        'Code' => 200,
        'msg' => 'Alert List fetched successfully',
        'data' => $details,
    ];
} else {
    $response = [
        'Code' => 250,
        'msg' => "Unable to fetch Details!!",
    ];
}

echo json_encode($response);
?>