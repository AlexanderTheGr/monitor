<?php

// Replace 'postageapp_config.inc' with the name of the PostageApp
// config file you have created
if(!defined('POSTAGE_HOSTNAME')) define ('POSTAGE_HOSTNAME', 'https://api.postageapp.com');

class PostageApp {

    // Sends a message to Postage App
    
    public $POSTAGE_API_KEY = "F3mWrTBispmTFFLN24ddW89e6T17M51R";


    function mail($recipient, $subject, $mail_body, $header, $variables=NULL,$attachments=NULL) {
        $content = array(
            'recipients' => $recipient,
            'headers' => array_merge($header, array('Subject' => $subject)),
            'variables' => $variables,
            'attachments' => $attachments,
            'uid' => time()
        );
        if (is_string($mail_body)) {
            $content['template'] = $mail_body;
        } else {
            $content['content'] = $mail_body;
        }

        return  PostageApp::post(
                        'send_message', json_encode(
                                array(
                                    'api_key' => $this->POSTAGE_API_KEY,
                                    'arguments' => $content
                                )
                        )
        );

    }

    // Makes a call to the Postage App API
    function post($api_method, $content) {
        $ch = curl_init(POSTAGE_HOSTNAME . '/v.1.0/' . $api_method . '.json');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output);
    }

}

?>