<?php
session_name("USER_SESSION");
session_start();

header("Content-Type: application/json");

include "config.php";



try
{
    if(
        !isset($_COOKIE['user_token'])
    )
    {
        echo json_encode([
            "status"   => false,
            "message" => "Cookie Expired",
            "location" => "userlogin.html"
        ]);

        exit;
    }

    if(
        !isset($_SESSION['USER_SESSION'])
    )
    {
        echo json_encode([
            "status"   => false,
             "message" => "Session Expired",
            "location" => "userlogin.html"
        ]);

        exit;
    }

    $token =
    $_SESSION['USER_SESSION']
    ['user_token'];

    $sql =
    "SELECT register_id,
            token
     FROM loginusers
     WHERE token = :token";

    $stmt =
    $conn->prepare($sql);

    $stmt->execute([
        ':token' => $token
    ]);

    $user =
    $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$user)
    {
        echo json_encode([
            "status"   => false,
             "message" => " based on the token cannot found record",
            "location" => "userlogin.html"
        ]);

        exit;
    }

    $sessionRegisterId =
    $_SESSION['USER_SESSION']
    ['user_register_id'];

    if(
        $sessionRegisterId
        !=
        $user['register_id']
    )
    {
        echo json_encode([
            "status"   => false,
             "message" => "in registerusers table ur register id not found",
            "location" => "userlogin.html"
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