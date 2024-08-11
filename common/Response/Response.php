<?php

namespace App\Response;

use App\MsAuth\Models\User;
use JMS\Serializer\SerializerBuilder;

class Response
{
    public static function success($data = null, $code = 200, $msg = 'ok'):void
    {
        http_response_code($code);
        header('Content-Type: application/json');
        $serializer = SerializerBuilder::create()->build();
        $dataResponse = array(
            'status' => 'success',
            'message' => $msg,
            'data' => $data,
        );
        $response = $serializer->serialize($dataResponse, 'json');
        echo $response;
    }
}
