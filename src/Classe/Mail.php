<?php

namespace App\Classe;
use Mailjet\Resources;
use \Mailjet\Client;

Class Mail {

    private $api_key = '94c7509c5c33cfcd274d6623eff649c5';
    private $api_key_secret = 'd3c64c325db791c60b5d45958667b508';

    public function send($to_email,$to_name, $subject, $content) {
        $mj = new Client($this->api_key, $this->api_key_secret, true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "mkeninismael2@gmail.com",
                        'Name' => "Mailjet Pilot"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,//"marwa.mkenini.06@gmail.com",
                            'Name' => $to_name,//"passenger 1"
                        ]
                    ],
                    'Subject' => $subject,
                    'TemplateID' => 2976398,
                    'Variables' => [
                        'content' => $content,
                    ],
                    'TemplateLanguage' => true,
                    /*
                    'TextPart' => "Dear passenger 1, welcome to Mailjet! May the delivery force be with you!",
                    'HTMLPart' => "<h3>Dear passenger 1, welcome to <a href=\"https://www.mailjet.com/\">Mailjet</a>!</h3><br />May the delivery force be with you!"
                */
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() /*&& dd($response->getData())*/;
    }

}


?>