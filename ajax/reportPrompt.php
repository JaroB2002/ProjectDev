<?php
include_once("../bootstrap.php");
session_start();

if(!empty($_POST)){
    $report = new Report();
    $report->setReporter($_SESSION['username']);
    $report->setPromptid($_POST['promptid']);

    if($report->isPromptReported()){
        $response = [
            'status' => 'success',
            'message' => 'You already reported this prompt before.',
        ];
    }else{
        $report->reportPrompt();
        $response = [
            'status' => 'success',
            'message' => 'Prompt reported.',
        ];
    }

    echo json_encode($response);
}