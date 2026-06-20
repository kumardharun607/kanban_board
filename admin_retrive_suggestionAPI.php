<?php

session_name("ADMIN_SESSION");
session_start();

header("Content-Type: application/json");

include "config.php";

try
{
    if(!isset($_SESSION['ADMIN_SESSION']))
    {
        echo json_encode([
            "status"=>false,
            "message"=>"Unauthorized Access"
        ]);

        exit;
    }

    $task_id =
    $_POST['task_id'];

    $sql =
    "SELECT *
     FROM tasks
     WHERE task_id=:task_id";

    $stmt =
    $conn->prepare($sql);

    $stmt->execute([
        ':task_id'=>$task_id
    ]);

    $task =
    $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$task)
    {
        echo json_encode([
            "status"=>false,
            "message"=>"Task Not Found"
        ]);

        exit;
    }

    echo json_encode([

        "status"=>true,

        "task"=>$task
    ]);
}
catch(PDOException $e)
{
    echo json_encode([

        "status"=>false,

        "message"=>$e->getMessage()
    ]);
}