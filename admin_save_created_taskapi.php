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
            "status"=>false,
            "message"=>"Unauthorized"
        ]);

        exit;
    }

    $created_id =
    $_POST['admin_duplicate_id'];

    $assigned_id =
    $_POST['assigned_id'];

    $project_code =
    $_POST['project_code'];

    $task =
    $_POST['task'];

    $suggestion =
    $_POST['suggestion'];

    $sql =
    "INSERT INTO tasks
    (
        task_created_at,
        created_id,
        task_assigned_id,
        project_code,
        tasks,
        admin_suggestion,
        status
    )
    VALUES
    (
        NOW(),
        :created_id,
        :assigned_id,
        :project_code,
        :tasks,
        :admin_suggestion,
        'To Do'
    )";

    $stmt =
    $conn->prepare($sql);

    $stmt->execute([

        ':created_id'=>$created_id,

        ':assigned_id'=>$assigned_id,

        ':project_code'=>$project_code,

        ':tasks'=>$task,

        ':admin_suggestion'=>$suggestion
    ]);

    $task_id =
    $conn->lastInsertId();

    echo json_encode([

        "status"=>true,

        "message"=>
        "Successfully Created Task",

        "task_id"=>$task_id
    ]);
}
catch(PDOException $e)
{
    echo json_encode([

        "status"=>false,

        "message"=>$e->getMessage()
    ]);
}