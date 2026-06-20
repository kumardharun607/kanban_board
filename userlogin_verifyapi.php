<?php

session_name("USER_SESSION");
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
FROM loginusers
WHERE email = :email";

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
        "userRegister.html"
    ]);

    exit;
}

//==============STEP3
//Verify Password

if(
!password_verify(
    $password,
    $loginUser['password']
))
{
    echo json_encode([

        "status"=>false,

        "message"=>
        "Invalid Credentials",
        "redirect"=>
        "userlogin.html"
    ]);

    exit;
}

//===========Step4
//Fetch registerUsers row


$sql =
"SELECT *
FROM registerusers
WHERE register_id=:register_id";

$stmt =
$conn->prepare($sql);

$stmt->execute([
 ':register_id'=>
 $loginUser['register_id']
]);

$registerUser =
$stmt->fetch(PDO::FETCH_ASSOC);

//===================STEP5
//Generate JWT

$secretKey =
"1a3LM3W966D6QTJ5BJb9opunkUcw_d09NCOIJb9QZTsrneqOICoMoeYUDcd_NfaQyR787PAH98Vhue5g938jdkiyIZyJICytKlbjNBtebaHljIR6-zf3A2h3uy6pCtUFl1UhXWnV6madujY4_3SyUViRwBUOP-UudUL4wnJnKYUGDKsiZePPzBGrF4_gxJMRwF9lIWyUCHSh-PRGfvT7s1mu4-5ByYlFvGDQraP4ZiG5bC1TAKO_CnPyd1hrpdzBzNW4SfjqGKmz7IvLAHmRD-2AMQHpTU-hN2vwoA-iQxwQhfnqjM0nnwtZ0urE6HjKl6GWQW-KLnhtfw5n_84IRQ";

$payload = [

    "register_id" =>
    $registerUser['register_id'],

    "fullname" =>
    $registerUser['fullname'],

    "role" =>
    $registerUser['role'],

    "project_name" =>
    $registerUser['project_name'],

    "email" =>
    $registerUser['email'],

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
"UPDATE loginusers

SET token=:token

WHERE email=:email";

$stmt =
$conn->prepare($updateSql);

$stmt->execute([

 ':token'=>$token,

 ':email'=>
 $email
]);

//=================Step 7

//  $_SESSION['user_register_id'] =
//  $registerUser['register_id'];

//  $_SESSION['user_token'] =
//  $token;
 $_SESSION['USER_SESSION'] = [

    'user_register_id' =>
    $registerUser['register_id'],

    'user_token' =>
    $token
];

//==================Step 8
//Create Cookie

setcookie(

    "user_token",

    $token,

    time() + $lifetime,//maintain cookie in 8 days in a browser

    "/",

    "",

    false,

    true
);
setcookie(

    "user_register_id",

    $registerUser['register_id'],

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

    "user_token"=>$token,

    "user_register_id"=>
    $registerUser['register_id'],

    "location"=>
    "userdashboard.html"
]);
//here i pass response as staus,message,token,register_id and location
//here i create token and expiry time is 8 days
//The token and register_id stored cookie also maintain life time as 8 days
//The session cookie aslo maintain life time as 8 days