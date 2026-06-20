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
            "status" => false,
            "message" => "Session Expired"
        ]);
        exit;
    }

    $sql =
    "SELECT *
     FROM tasks
     ORDER BY task_created_at DESC";

    $stmt = $conn->prepare($sql);

    $stmt->execute();

    $rows =
    $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => true,
        "rows" => $rows
    ]);
}
catch(PDOException $e)
{
    echo json_encode([
        "status" => false,
        "message" => $e->getMessage()
    ]);
}