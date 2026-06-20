<?php

session_name("USER_SESSION");

session_start();

header("Content-Type: application/json");

include "config.php";

try
{
    $taskId =
    $_POST['task_id'];

    $sql =
    "SELECT *
     FROM tasks
     WHERE task_id=:task_id";

    $stmt =
    $conn->prepare($sql);

    $stmt->execute([
        ':task_id'=>$taskId
    ]);

    $taskdetails =
    $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$taskdetails)
    {
        echo json_encode([
            "status"=>false,
            "message"=>"Task Not Found"
        ]);

        exit;
    }

    $sql =
    "SELECT fullname,
            role
     FROM registerusers
     WHERE register_id=:register_id";

    $stmt =
    $conn->prepare($sql);

    $stmt->execute([
        ':register_id'=>
        $taskdetails['task_assigned_id']
    ]);

    $assigndetails =
    $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt =
    $conn->prepare($sql);

    $stmt->execute([
        ':register_id'=>
        $taskdetails['created_id']
    ]);

    $createddetails =
    $stmt->fetch(PDO::FETCH_ASSOC);

    if(
        !$assigndetails
        ||
        !$createddetails
    )
    {
        echo json_encode([
            "status"=>false,
            "message"=>"User Details Not Found"
        ]);

        exit;
    }

    echo json_encode([

        "status"=>true,

        "created_at"=>
        $taskdetails['task_created_at'],

        "created_Id"=>
        $taskdetails['created_id'],

        "created_name"=>
        $createddetails['fullname'],

        "created_role"=>
        $createddetails['role'],

        "assign_name"=>
        $assigndetails['fullname'],

        "assign_role"=>
        $assigndetails['role'],

        "project_code"=>
        $taskdetails['project_code'],

        "suggestion"=>
        $taskdetails['admin_suggestion']
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