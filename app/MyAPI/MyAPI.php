<?php

namespace App\MyAPI;

class MyAPI
{
    const VALIDATION_MSG = 'Validation Error';
    const DELETING_MSG = 'Done Deleting';

    public static function JsonResponse(array $status_msg_data)
    {
        $json = [
            'status' => $status_msg_data[0],
            'msg' => $status_msg_data[1],
            'data' => $status_msg_data[2] ?? null,
        ];
        return response()->json($json, $json['status']);
    }
}
