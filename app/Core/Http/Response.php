<?php namespace Core\Http;

class Response {
    protected $status;
    protected $payload;

    public function __construct($payload = [], $status = 200, $error = []) {
        $this->payload = (is_array($payload)) ?  (object)  $payload : (object) ['message' => $payload];
        $this->status = $status;

        if(!empty($error))
            $this->payload->error = $error;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getError() {
        return $this->payload->error;
    }

    public function getPayload() {
        return $this->payload;
    }
}