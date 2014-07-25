<?php


require( 'Mandrill.php' );
$mandrill = new Mandrill($mandrill_key);

$template_html = file_get_contents( 'html_email.html' );

$send_to = array(
    array(
        'email' => 'hugo@healthkeep.com',
        'name' => 'It is you!'
    ),
    array(
        'email' => 'email@hugogameiro.com',
        'name' => 'It is you again!'
    ),
);

$message = array(
    'html' => $template_html,
    'text' => "Hello dude",
    'subject' => "First mandrill",
    'from_email' => "hmgameiro@gmail.com",
    'from_name' => "From Name",
    'track_opens' => true,
    'track_clicks' => true,
    'auto_text' => true,
);

foreach( $send_to as $to )
{
    $message['to'] = array( $to );
    $result = $mandrill->messages->send( $message );
    print_r($result);
}