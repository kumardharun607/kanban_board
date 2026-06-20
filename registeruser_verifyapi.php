<?php

header("Content-Type: application/json");

include "config.php";

try
{
    $fullname     = $_POST['fullname'];
    $role         = $_POST['role'];
    // $project_name = $_POST['project_name'];
    $email = $_POST['email'];
    
    $password     = $_POST['password'];
    // $start_date   = $_POST['start_date'];

    $checkSql =
    "SELECT register_id
     FROM registerUsers
     WHERE email = :email";

    $stmt = $conn->prepare($checkSql);

    $stmt->execute([
        ':email' => $email
    ]);
   

    if($stmt->rowCount() > 0)
    {
        echo json_encode([
            "status" => false,
            "message" =>
            "The Programmer is already register"
        ]);

        exit;
    }

    $hashedPassword =
    password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    $insertRegisterSql =
    "INSERT INTO registerusers
    (
        fullname,
        role,
        -- project_name,
        password,
        -- start_date,
        email
    )
    VALUES
    (
        :fullname,
        :role,
        -- :project_name,
        :password,
        -- :start_date,
        :email

    )";

    $stmt = $conn->prepare($insertRegisterSql);

    $stmt->execute([
        ':fullname' => $fullname,
        ':role' => $role,
        // ':project_name' => $project_name,
        ':password' => $hashedPassword,
        // ':start_date' => $start_date,
        ':email'=>$email
    ]);

    $registerId =
    $conn->lastInsertId();

    $insertLoginSql =
    "INSERT INTO loginusers
    (
        register_id,
        password,
        email
    )
    VALUES
    (
        :register_id,
        :password,
        :email
    )";

    $stmt = $conn->prepare($insertLoginSql);

    $stmt->execute([
        ':register_id' => $registerId,
        ':password' => $hashedPassword,
        ':email'=>$email
    ]);

    echo json_encode([
        "status" => true,
        "location" => "userlogin.html"
    ]);
}
catch(PDOException $e)
{
    echo json_encode([
        "status" => false,
        "message" => $e->getMessage()
    ]);
}