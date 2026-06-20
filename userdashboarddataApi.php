<?php

session_name("USER_SESSION");

session_start();

header("Content-Type: application/json");

include "config.php";

try
{
    if(
        !isset($_SESSION['USER_SESSION'])
    )
    {
        echo json_encode([
            "status" => false,
            "message" => "Session Expired"
        ]);

        exit;
    }

    $registerId =
    $_SESSION['USER_SESSION']
    ['user_register_id'];

    $sql =
    "SELECT fullname
     FROM registerusers
     WHERE register_id=:register_id";

    $stmt =
    $conn->prepare($sql);

    $stmt->execute([
        ':register_id'=>$registerId
    ]);

    $user =
    $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$user)
    {
        echo json_encode([
            "status"=>false,
            "message"=>"User Not Found"
        ]);

        exit;
    }

    $sql =
    "SELECT
        task_id,
        tasks,
        status,
        admin_suggestion,
        task_created_at
     FROM tasks
     WHERE task_assigned_id=:register_id
     ORDER BY task_created_at DESC";

    $stmt =
    $conn->prepare($sql);

    $stmt->execute([
        ':register_id'=>$registerId
    ]);

    $tasks =
    $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([

        "status"=>true,

        "name"=>
        $user['fullname'],
        "register_id"=>$registerId,

        "task"=>
        $tasks
    ]);
}
catch(PDOException $e)
{
    echo json_encode([

        "status"=>false,

        "message"=>
        $e->getMessage()
    ]);
}