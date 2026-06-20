<?php

session_name("USER_SESSION");
session_start();

include "config.php";

$assignedId =
$_SESSION['USER_SESSION']
['user_register_id'];

$projectCode =
$_POST['project_code'];

$task =
$_POST['task'];

$sql =
"INSERT INTO tasks
(
created_id,
task_assigned_id,
project_code,
tasks,
status,
task_created_at
)
VALUES
(
:created_id,
:assigned_id,
:project_code,
:tasks,
'To Do',
NOW()
)";

$stmt =
$conn->prepare($sql);

$stmt->execute([

':created_id'=>$assignedId,
':assigned_id'=>$assignedId,
':project_code'=>$projectCode,
':tasks'=>$task

]);

echo json_encode([
"status"=>true
]);