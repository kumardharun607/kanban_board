<?php

header("Content-Type: application/json");

include "config.php";

$admin_name =
$_POST['admin_name'];

$admin_email =
$_POST['admin_email'];

$admin_password =
$_POST['admin_password'];

$secret_code =
$_POST['secret_code'];

//=======================================Verify Secret Code is already present or not if
//=======================================it is present it retun true


$secretSql =
"SELECT secret_id
 FROM admin_secret_codes
 WHERE secret_code = :secret_code";

$stmt =
$conn->prepare($secretSql);

$stmt->execute([
    ':secret_code' => $secret_code
]);

if($stmt->rowCount() == 0)
{
    echo json_encode([
        "status" => false,
        "message" =>
        "Your entering invalid secret code"
    ]);

    exit;
}

//===============================Check Email is Already present or not

$emailSql =
"SELECT admin_id
 FROM admin_users
 WHERE admin_email=:admin_email";

$stmt =
$conn->prepare($emailSql);

$stmt->execute([
    ':admin_email'=>$admin_email
]);

if($stmt->rowCount() > 0)
{
    echo json_encode([
        "status"=>false,
        "message"=>
        "The admin is already registered"
    ]);

    exit;
}

//=============================================Insert Admin in register table

$hashedPassword =
password_hash(
    $admin_password,
    PASSWORD_DEFAULT
);

$insertSql =
"INSERT INTO admin_users
(
 admin_name,
 admin_email,
 admin_password,
 secret_code
)
VALUES
(
 :admin_name,
 :admin_email,
 :admin_password,
 :secret_code
)";

$stmt =
$conn->prepare($insertSql);

$stmt->execute([

 ':admin_name'=>$admin_name,

 ':admin_email'=>$admin_email,

 ':admin_password'=>$hashedPassword,

 ':secret_code'=>$secret_code
]);

//===================================Insert Admin in login table
$admin_registerId =
    $conn->lastInsertId();


$insertSql =
"INSERT INTO admin_login
(
 admin_id,
 admin_email,
 admin_password
)
VALUES
(
 :admin_id,
 :admin_email,
 :admin_password
)";



$stmt =
$conn->prepare($insertSql);

$stmt->execute([

 ':admin_id'=>$admin_registerId,

 ':admin_email'=>$admin_email,

 ':admin_password'=>$hashedPassword,

 
]);

//==
$insertRegisterSql =
    "INSERT INTO registerusers
    (
        
        fullname,
        role,
        email
    )
    VALUES
    (
        
        :fullname,
        :role,
        :email

    )";

    $stmt = $conn->prepare($insertRegisterSql);
    $admin_role="Admin";
    
    $stmt->execute([
        
        ':fullname' => $admin_name,
        ':role' => $admin_role,
        ':email'=>$admin_email
    ]);

//====
$new_registerId = $conn->lastInsertId();
$updateSql =
"UPDATE admin_users

SET admin_duplicate_id = :admin_duplicate_id

WHERE admin_email = :admin_email";

$stmt = $conn->prepare($updateSql);

$stmt->execute([

    ':admin_duplicate_id' => $new_registerId,

    ':admin_email' => $admin_email
]);

//==================================Success   REsponse

echo json_encode([

    "status"=>true,

    "message"=>
    "Register Successfully",

    "location"=>
    "adminLogin.html"
]);

?>