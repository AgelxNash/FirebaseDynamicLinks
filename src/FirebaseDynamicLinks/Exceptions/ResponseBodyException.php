<?php namespace AgelxNash\FirebaseDynamicLinks\Exceptions;

class ResponseBodyException extends \Exception
{
    protected $body;

    /**
     * @param string $body
     * @return ResponseBodyException
     */
    public function setBody($body) : self
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
}
