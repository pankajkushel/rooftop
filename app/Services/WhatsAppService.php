<?php

namespace App\Services;

use Exception;

class WhatsAppService
{
    public function sendMessage($to, $templateName, $parameters)
    {
        // API URL
        $url = 'https://cloudapi.wbbox.in/api/v1.0/messages/send-template/917838357872';

        // Prepare the request data
        $data = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => ['code' => 'en'],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => $parameters
                    ]
                ]
            ]
        ];

        // Initialize cURL
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                //'Authorization: Bearer ' . env('WHATSAPP_API_TOKEN'),
                'Authorization: Bearer dYjPebs9fU67IeafcZbL6g',
                'Content-Type: application/json'
            ],
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        // Execute the cURL request and handle errors
        $response = curl_exec($curl);

        // if ($response === false) {
        //     throw new Exception('cURL error: ' . curl_error($curl));
        // }

        // $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        // if ($httpCode != 200) {
        //     throw new Exception("HTTP error: $httpCode - $response");
        // }

        curl_close($curl);
        //dd($response);
        return $response;
    }


    public function sendMessageWp($to, $templateName)
    {
        // API URL
        $url = 'https://cloudapi.wbbox.in/api/v1.0/messages/send-template/917838357872';

        // Prepare the request data
        $data = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => ['code' => 'en'],
                'components' => []
            ]
        ];

        // Initialize cURL
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                //'Authorization: Bearer ' . env('WHATSAPP_API_TOKEN'),
                'Authorization: Bearer dYjPebs9fU67IeafcZbL6g',
                'Content-Type: application/json'
            ],
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        // Execute the cURL request and handle errors
        $response = curl_exec($curl);

        // if ($response === false) {
        //     throw new Exception('cURL error: ' . curl_error($curl));
        // }

        // $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        // if ($httpCode != 200) {
        //     throw new Exception("HTTP error: $httpCode - $response");
        // }

        curl_close($curl);

        return $response;
    }
}