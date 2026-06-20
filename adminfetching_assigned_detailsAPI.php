<?php

header("Content-Type: application/json");

include "config.php";

try
{
    $assigned_id =
    $_POST['assigned_id'];

    $sql =
    "SELECT *
     FROM registerusers
     WHERE register_id=:register_id";

    $stmt =
    $conn->prepare($sql);

    $stmt->execute([
        ':register_id'=>$assigned_id
    ]);

    $row =
    $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$row)
    {
        echo json_encode([
            "status"=>false,
            "message"=>"Assigned User Not Found"
        ]);

        exit;
    }

    echo json_encode([
        "status"=>true,
        "assigned_details"=>$row
    ]);
}
catch(PDOException $e)
{
    echo json_encode([
        "status"=>false,
        "message"=>$e->getMessage()
    ]);
}