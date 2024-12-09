<?php

class Functions
{
    private $base_url;

    public function __construct()
    {
        $this->base_url = $GLOBALS['base_url'];
    }
    
    public function setSessionMessage($result, $message)
    {
        $_SESSION['success'] = $result;
        $_SESSION['message'] = $message;
    }

    public function redirect($url = "")
    {
        header('Location: ' . $this->base_url . $url);
        exit();
    }
}
