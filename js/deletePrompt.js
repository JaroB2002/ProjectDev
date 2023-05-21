
// deletePrompt.php

// Include the necessary files and start the session
include_once("bootstrap.php");
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
  http_response_code(401); // Unauthorized status code
  echo json_encode(["status" => "error", "message" => "Unauthorized"]);
  exit();
}

// Get the prompt ID from the request body
$requestBody = file_get_contents('php://input');
$data = json_decode($requestBody, true);
$promptId = $data['promptId'];

// Delete the prompt from the database
$prompt = new Prompt();
$deleted = $prompt->deletePrompt($promptId);

if ($deleted) {
  echo json_encode(["status" => "success", "message" => "Prompt deleted successfully"]);
} else {
  echo json_encode(["status" => "error", "message" => "Failed to delete prompt"]);
}

