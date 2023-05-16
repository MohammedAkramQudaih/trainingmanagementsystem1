<?php

trait HttpResponses
{
    protected function success($data, $message = null, $code = 200)
    {
        return response()->json(
            [
                'status' => 'Request Was Successfull',
                'message' => $message,
                'data' => $data,
            ], $code
        );
    }

    protected function error($data, $message = null, $code)
    {
        return response()->json(
            [
                'status' => 'Error has occured',
                'message' => $message,
                'data' => $data,
            ], $code
        );
    }
}
