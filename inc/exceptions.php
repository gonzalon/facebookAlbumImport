<?php

class FacebookException extends Exception
{
    public function __construct(Exception $previous = null) {
        parent::__construct("An error was produced while trying to consume the Facebook API. \n\r" . $previous->getMessage(), 5, $previous);
    }
}

class ImportException extends Exception
{
    public function __construct($message, $code) {
        parent::__construct($message, $code, $previous);
    }
}

