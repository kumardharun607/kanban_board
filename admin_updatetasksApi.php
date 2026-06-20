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

    $created_id =
    $_POST['created_id'];

    $assigned_id =
    $_POST['task_assigned_id'];

    $project_code =
    $_POST['project_code'];

    $tasks =
    $_POST['tasks'];

    $status =
$_POST['status'];

    $sql =
    "UPDATE tasks

     SET

     created_id = :created_id,

     task_assigned_id = :assigned_id,

     project_code = :project_code,

     tasks = :tasks,

     status = :status

     WHERE task_id = :task_id";

    $stmt =
    $conn->prepare($sql);

    $stmt->execute([

        ':created_id'=>$created_id,

        ':assigned_id'=>$assigned_id,

        ':project_code'=>$project_code,

        ':tasks'=>$tasks,
        ':status'=>$status,

        ':task_id'=>$task_id
    ]);

    echo json_encode([

        "status"=>true,

        "message"=>
        "Task Updated Successfully"
    ]);
}
catch(PDOException $e)
{
    echo json_encode([

        "status"=>false,

        "message"=>$e->getMessage()
    ]);
}