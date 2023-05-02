<?php
    require_once(__DIR__ . '/vendor/autoload.php');
    $config = parse_ini_file('config/config.ini', true);
    $key = $config['keys']['SENDGRID_API_KEY'];
    //var_dump($key);

    //require 'vendor/autoload.php';
    $email = new \SendGrid\Mail\Mail();
    $email->setFrom("r0784273@student.thomasmore.be", "Fred Kroket");
    $email->setSubject("Sending with Twilio SendGrid is Fun");
    $email->addTo("yadina.moreira@gmail.com", "Yadina");
    $email->addContent("text/plain", "and easy to do anywhere, even with PHP");
    $email->addContent(
    "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
    );

    $options = array(
    'turn_off_ssl_verification' => true
    );

    $sendgrid = new \SendGrid($key, $options);
    try {
        $response = $sendgrid->send($email);
        print $response->statusCode() . "\n";
        print_r($response->headers());
        print $response->body() . "\n";
        echo "email sent!\n";

    } catch (Exception $e) {
        echo 'Caught exception: '. $e->getMessage() ."\n";
    }
?>