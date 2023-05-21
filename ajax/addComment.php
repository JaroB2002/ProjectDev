<?php
include_once("bootstrap.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentText = $_POST['commentText'];
    $postId = $_POST['postId'];

    $comment = new Comment();
    $comment->setText($commentText);
    $comment->setPostId($postId);
    $comment->save();

    // Stuur een JSON-reactie terug met de status en het toegevoegde commentaar
    $response = [
        'status' => 'success',
        'comment' => $comment->toArray()
    ];
    echo json_encode($response);
}
