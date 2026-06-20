<?php

header("Content-Type: application/json");

include "config.php";

try
{
    $created_id =
    $_POST['created_id'];

    $sql =
    "SELECT *
     FROM registerusers
     WHERE register_id=:register_id";

    $stmt =
    $conn->prepare($sql);

    $stmt->execute([
        ':register_id'=>$created_id
    ]);

    $row =
    $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$row)
    {
        echo json_encode([
            "status"=>false,
            "message"=>"Creator Not Found"
        ]);

        exit;
    }

    echo json_encode([
        "status"=>true,
        "created_details"=>$row
    ]);
}
catch(PDOException $e)
{
    echo json_encode([
        "status"=>false,
        "message"=>$e->getMessage()
    ]);
}