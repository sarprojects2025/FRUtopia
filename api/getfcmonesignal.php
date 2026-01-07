<?php
include($_SERVER['DOCUMENT_ROOT'] . '/SAR_payroll/api/config/config.php');
require 'vendor/autoload.php'; // 🔹 Google Client Library

use Google\Client;


error_reporting(E_ALL);
ini_set('display_errors', 1);


// ✅ Firebase JSON Key का पथ
$firebase_key_path = $_SERVER['DOCUMENT_ROOT'] . '/SAR_payroll/api/testing-app-e27e1-firebase-adminsdk-fbsvc-6e3da28fce.json'; // 🔹 अपनी JSON फाइल डालें

// ✅ Firebase Project ID
$project_id = "testing-app-e27e1"; // 🔹 अपना Firebase प्रोजेक्ट ID डालें

// ✅ Firebase API URL
$fcm_url = "https://fcm.googleapis.com/v1/projects/$project_id/messages:send";

// ✅ Firebase Token प्राप्त करें
function getAccessToken($firebase_key_path) {
    $client = new Client();
    $client->setAuthConfig($firebase_key_path);
    $client->addScope("https://www.googleapis.com/auth/firebase.messaging");
    $client->fetchAccessTokenWithAssertion();
    return $client->getAccessToken()['access_token'];
}

// 🔹 FCM Token Generate करें
$access_token = getAccessToken($firebase_key_path);

// 🔹 Device Token (जिस डिवाइस पर भेजना है)
$device_token = "cFzwypU4SOCnmzSPZGVXCJ:APA91bGavRU29AP_xtmhHCd41PnLZggYKxvlYSSXdSp06gGrsLCeVqanEvVNAJGDlgg4pw4iWmVko-veFT-r8P11BQLBIimka16e9TCVd2tbbHT894QrGSs"; // 🔹 यहाँ User का FCM Token डालें

// 🔹 Notification Data
$notification_data = [
    "message" => [
        "token" => $device_token,
        "notification" => [
            "title" => "🔔 नया Notification!",
            "body" => "यह Firebase से भेजा गया एक टेस्ट Notification है!",
        ],
    ]
];

$headers = [
    "Authorization: Bearer $access_token",
    "Content-Type: application/json"
];

// 🔹 cURL Request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $fcm_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notification_data));

$response = curl_exec($ch);
curl_close($ch);

echo "📨 Notification Sent Response: " . $response;

?>
