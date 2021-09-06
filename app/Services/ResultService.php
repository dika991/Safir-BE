<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ResultService
{
    private $result = null;
    private $isFail = false;
    private $message = null;

    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function setFail($isFail)
    {
        $this->isFail = $isFail;
        return $this;
    }

    public function isFail()
    {
        return $this->isFail;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }
}
