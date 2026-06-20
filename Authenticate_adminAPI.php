<?php

session_name("ADMIN_SESSION");

session_start();

header("Content-Type: application/json");

include "config.php";

try
{
    if(
        !isset($_COOKIE['admin_token'])
    )
    {
        echo json_encode([
            "status" => false,
            
            "location" => "adminLogin.html"
        ]);

        exit;
    }

    if(
        !isset($_SESSION['ADMIN_SESSION'])
    )
    {
        echo json_encode([
            "status" => false,
            
            "location" => "adminLogin.html"
        ]);

        exit;
    }

    $token =
    $_SESSION['ADMIN_SESSION']
    ['admin_token'];

    $sql =
    "SELECT admin_id,
            admin_token
     FROM admin_login
     WHERE admin_token = :token";

    $stmt =
    $conn->prepare($sql);

    $stmt->execute([
        ':token' => $token
    ]);

    $admin =
    $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$admin)
    {
        echo json_encode([
            "status" => false,
            
            "location" => "adminLogin.html"
        ]);

        exit;
    }

    $sessionAdminId =
    $_SESSION['ADMIN_SESSION']
    ['admin_register_id'];

    if(
        $sessionAdminId
        !=
        $admin['admin_id']
    )
    {
        echo json_encode([
            "status" => false,

            "location" => "adminLogin.html"
        ]);

        exit;
    }

    echo json_encode([
        "status" => true
    ]);
}
catch(PDOException $e)
{
    echo json_encode([
        "status" => false,
        "message" => $e->getMessage()
    ]);
}