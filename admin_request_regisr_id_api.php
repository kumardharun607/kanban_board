<?php

session_name("ADMIN_SESSION");
session_start();

header("Content-Type: application/json");

include "config.php";

try
{
    if(
        !isset($_SESSION['ADMIN_SESSION'])
    )
    {
        echo json_encode([

            "status" => false,

            "message" =>
            "Unauthorized Access"
        ]);

        exit;
    }

    $admin_register_id =
    $_SESSION['ADMIN_SESSION']
    ['admin_register_id'];

    $sql =
    "SELECT admin_duplicate_id

     FROM admin_users

     WHERE admin_id = :admin_id";

    $stmt =
    $conn->prepare($sql);

    $stmt->execute([

        ':admin_id' =>
        $admin_register_id
    ]);

    $row =
    $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$row)
    {
        echo json_encode([

            "status"=>false,

            "message"=>
            "Admin Not Found"
        ]);

        exit;
    }

    echo json_encode([

        "status"=>true,

        "admin_duplicate_id"=>
        $row['admin_duplicate_id']
    ]);
}
catch(PDOException $e)
{
    echo json_encode([

        "status"=>false,

        "message"=>$e->getMessage()
    ]);
}