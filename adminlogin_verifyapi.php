<?php

session_name("ADMIN_SESSION");
$lifetime = 8 * 24 * 60 * 60; // 8 days
ini_set('session.gc_maxlifetime', $lifetime);

session_set_cookie_params([
    'lifetime' => $lifetime,
    'path' => '/',
    'httponly' => true
]);//These lines are used before session_start()
//To maintain session cookie as 8 days live
session_start();

header("Content-Type: application/json");

require "config.php";

require "vendor/autoload.php";

use Firebase\JWT\JWT;

$email =
$_POST['email'];

$password =
$_POST['password'];

//==========STEP1
//Fetch loginUsers record.


$sql =
"SELECT *
FROM admin_login
WHERE admin_email = :email";

$stmt =
$conn->prepare($sql);

$stmt->execute([
    ':email'=>$email
]);

$loginUser =
$stmt->fetch(PDO::FETCH_ASSOC);

//===================Step2
//No record

if(!$loginUser)
{
    echo json_encode([

        "status"=>false,

        "message"=>
        "This Email not registered So,Please register",

        "redirect"=>
        "adminRegister.html"
    ]);

    exit;
}

//==============STEP3
//Verify Password

if(
!password_verify(
    $password,
    $loginUser['admin_password']
))
{
    echo json_encode([

        "status"=>false,

        "message"=>
        "Invalid Credentials",
        "redirect"=>
        "adminLogin.html"
    ]);

    exit;
}

//===========Step4
//Fetch registerUsers row


$sql =
"SELECT *
FROM admin_users
WHERE admin_id=:register_id";

$stmt =
$conn->prepare($sql);

$stmt->execute([
 ':register_id'=>
 $loginUser['admin_id']
]);

$registerUser =
$stmt->fetch(PDO::FETCH_ASSOC);

//===================STEP5
//Generate JWT

$secretKey =
"1a3LM3W966D6QTJ5BJb9opunkUcw_d09NCOIJb9QZTsrneqOICoMoeYUDcd_NfaQyR787PAH98Vhue5g938jdkiyIZyJICytKlbjNBtebaHljIR6-zf3A2h3uy6pCtUFl1UhXWnV6madujY4_3SyUViRwBUOP-UudUL4wnJnKYUGDKsiZePPzBGrF4_gxJMRwF9lIWyUCHSh-PRGfvT7s1mu4-5ByYlFvGDQraP4ZiG5bC1TAKO_CnPyd1hrpdzBzNW4SfjqGKmz7IvLAHmRD-2AMQHpTU-hN2vwoA-iQxwQhfnqjM0nnwtZ0urE6HjKl6GWQW-KLnhtfw5n_84IRQ";

$payload = [

    "register_id" =>
    $registerUser['admin_id'],

    "fullname" =>
    $registerUser['admin_name'],

    "email" =>
    $registerUser['admin_email'],

    "iat" => time(),

    "exp" => time() + $lifetime//to maintain token as 8 days life time in browser also
];

$token =
JWT::encode(
    $payload,
    $secretKey,
    'HS256'
);


//==============Step6
//Update Login Users token

$updateSql =
"UPDATE admin_login

SET admin_token=:token

WHERE admin_email=:email";

$stmt =
$conn->prepare($updateSql);

$stmt->execute([

 ':token'=>$token,

 ':email'=>
 $email
]);

//=================Step 7

//  $_SESSION['admin_register_id'] =
//  $registerUser['admin_id'];

//  $_SESSION['admin_token'] =
//  $token;
 $_SESSION['ADMIN_SESSION'] = [

    'admin_register_id' =>
    $registerUser['admin_id'],

    'admin_token' =>
    $token
];
//==================Step 8
//Create Cookie

setcookie(

    "admin_token",

    $token,

    time() + $lifetime,//maintain cookie in 8 days in a browser

    "/",

    "",

    false,

    true
);
setcookie(

    "admin_register_id",

    $registerUser['admin_id'],

    time() + $lifetime,//maintain cookie in 8 days in a browser

    "/",

    "",

    false,

    true
);

//============Step 9
//============Success Response

echo json_encode([

    "status"=>true,

    "message"=>
    "Login Successful",

    "admin_token"=>$token,

    "admin_id"=>
    $registerUser['admin_id'],

    "location"=>
    "admindashboard.html"
]);
//here i pass response as staus,message,token,register_id and location
//here i create token and expiry time is 8 days
//The token and register_id stored cookie also maintain life time as 8 days
//The session cookie aslo maintain life time as 8 days