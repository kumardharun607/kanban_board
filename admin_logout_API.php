<?php

session_name("ADMIN_SESSION");
session_start();

header("Content-Type: application/json");

try
{
    $_SESSION = [];

    if(session_id() != "")
    {
        session_destroy();
    }

    setcookie(
        "admin_token",
        "",
        time()-3600,
        "/"
    );

    setcookie(
        "admin_register_id",
        "",
        time()-3600,
        "/"
    );

    setcookie(
        session_name(),
        "",
        time()-3600,
        "/"
    );

    echo json_encode([

        "status" => true,

        "message" =>
        "Successfully Logged Out"
    ]);
}
catch(Exception $e)
{
    echo json_encode([

        "status" => false,

        "message" =>
        $e->getMessage()
    ]);
}