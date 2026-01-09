<?php
// include($_SERVER['DOCUMENT_ROOT'].'/FRUtopia/api/config/config.php');
include(__DIR__ . '/config/config.php');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json'); 

$data = $_POST;

$todays_date = date('Y-m-d');

$response = array();

$email_id = isset($data['email_id']) ? $data['email_id'] : '';
$password = isset($data['password']) ? $data['password'] : '';

if(!empty($email_id) && !empty($password)){
    
    if(strlen($email_id) == '' && $password==''){
        $response['Code'] = "422";  // 422 = senantic errors
        $response['msg'] = "Email ID Required";
    } else {
        $checksql = mysqli_query($con, "select * from user_login where email_id = '".$email_id."' and password = '".$password."' and user_role=1");
        if(mysqli_num_rows($checksql) > 0){
            $fetchres = mysqli_fetch_assoc($checksql);
            $user_id = $fetchres['id'];
            $username = $fetchres['name'];
            
            $response['Code'] = 200; // 200 = successfull
            $response['msg'] = "Login successful";
            $response['user_id']=$user_id;
            $response['username'] = $username;
            
        } else {
            $response['Code'] = 401; // 401 = unauthorised
            $response['msg'] = "Invalid email id or password";
        }
    }
} else {
    $response['Code'] = 404; // 404 = not found
    $response['msg'] = "Email ID or password is missing";
}

echo json_encode($response);  
?>
