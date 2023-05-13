<?php
include_once("../bootstrap.php");
session_start();

if(!empty($_POST)){
    $report = new Report();
    $report->setReporter($_SESSION['username']);
    $report->setPromptid($_POST['promptid']);

    $report->reportPrompt();
    $response = [
        'status' => 'success',
        'message' => 'Prompt reported.',
    ];
    echo json_encode($response);
    /*if($report->isPromptReported()){
        $response = [
            'status' => 'success',
            'message' => 'You have already reported this prompt.',
        ];
    }
    else{
        $report->reportPrompt();
        $response = [
            'status' => 'success',
            'message' => 'Prompt reported.',
        ];
    }*/
}