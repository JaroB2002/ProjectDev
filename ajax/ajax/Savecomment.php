<?php
// Make sure the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the comment text and post ID from the form data
    $text = $_POST['text'];
    $postId = $_POST['postId'];

    // Create a new Comment object and set its properties
    $comment = new Comment();
    $comment->setText($text);
    $comment->setPostId($postId);

    // Save the comment to the database
    $result = $comment->save();

    // Return a JSON response with the saved comment
    if ($result) {
        $response = [
            'success' => true,
            'body' => '<p>' . $text . '</p>'
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Failed to save comment'
        ];
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}
