<?php

namespace App\Components\Traits;

trait ResponseTrait
{
    protected $success = true;
    protected $message = 'success';
    protected $responseData = [];
    protected $tag = null;

    public function setResponse($data, $code = true, $message = 'success', $tag = null)
    {
        $this->responseData = $data;
        $this->success      = $code;
        $this->message      = $message;
        $this->tag          = $tag;
        return $this;
    }

    public function response()
    {
        return json_encode([
            'success'      => $this->success,
            'body'         => $this->responseData,
            'message'      => $this->message,
            'tag'          => $this->tag
        ]);
    }
}