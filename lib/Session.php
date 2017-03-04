<?php

class Session  {

    protected $messages = [];

    public function __construct () {
        if (array_key_exists('messages', $_SESSION)) $this->messages = $_SESSION['messages'];
    }

    public function prepare () {
        $_SESSION = [];
        $_SESSION['messages'] = $this->messages;
    }

    public function addMessage ($key = null, $message) {
        if ($key==null) $this->messages[] = $message;
        else $this->messages[$key] = $message;
    }

    public function getMessages () { return $this->messages; }

    public function getMessage ($key) {
        if (!array_key_exists($key, $this->messages)) return null;
        $r = $this->messages[$key];
        unset($this->messages[$key]);
        unset($_SESSION['messages'][$key]);
        return $r;
    }

}