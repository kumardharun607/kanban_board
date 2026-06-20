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
            "message" => "Unauthorized Access"
        ]);
        exit;
    }

    $task_id = $_POST['task_id'];

    $suggestion = trim($_POST['suggestion']);

    // Fetch existing suggestions
    $stmt = $conn->prepare("
        SELECT admin_suggestion
        FROM tasks
        WHERE task_id = :task_id
    ");

    $stmt->execute([
        ':task_id' => $task_id
    ]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $oldSuggestion = $row['admin_suggestion'] ?? "";

    $currentTime = date("Y-m-d H:i:s");

    $newSuggestion =
        "[Suggested_at : ". $currentTime . "]\n" .
        $suggestion .
        "\n\n------------------------------------------\n\n" .
        $oldSuggestion;

    // Update
    $stmt = $conn->prepare("
        UPDATE tasks
        SET admin_suggestion = :admin_suggestion
        WHERE task_id = :task_id
    ");

    $stmt->execute([
        ':admin_suggestion' => $newSuggestion,
        ':task_id' => $task_id
    ]);

    echo json_encode([
        "status" => true,
        "message"=>"Successfully Suggessted!",
        "updated_suggestion" => $newSuggestion
    ]);
}
catch(PDOException $e)
{
    echo json_encode([
        "status" => false,
        "message" => $e->getMessage()
    ]);
}