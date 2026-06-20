<?php

include "config.php";

$taskId = $_POST['task_id'];
$status = $_POST['status'];

$sql =
"UPDATE tasks
SET status=:status
WHERE task_id=:task_id";

$stmt =
$conn->prepare($sql);

$stmt->execute([

':status'=>$status,
':task_id'=>$taskId

]);

echo json_encode([
"status"=>true
]);