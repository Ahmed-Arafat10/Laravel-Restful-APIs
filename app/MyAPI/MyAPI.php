<?php

namespace App\MyAPI;

class MyAPI
{
    public static function JsonResponse(array $status_msg_data)
    {
        $json = [
            'status' => $status_msg_data[0],
            'msg' => $status_msg_data[1],
            'data' => $status_msg_data[2],
        ];
        return response()->json($json, $json['status']);
    }
}
