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
    "DELETE FROM tasks
     WHERE task_id=:task_id";

    $stmt =
    $conn->prepare($sql);

    $stmt->execute([
        ':task_id'=>$task_id
    ]);

    echo json_encode([

        "status"=>true,

        "message"=>
        "Task Deleted Successfully"
    ]);
}
catch(PDOException $e)
{
    echo json_encode([

        "status"=>false,

        "message"=>$e->getMessage()
    ]);
}