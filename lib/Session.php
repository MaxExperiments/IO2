<?php

class Session  {

    protected static $user = null;

    protected static $authenticate;

    protected $messages = [];

    public function __construct () {
        if (array_key_exists('messages', $_SESSION)) $this->messages = $_SESSION['messages'];
        if (array_key_exists('user',$_SESSION)) {
            self::$authenticate = true;
            self::$user = $_SESSION['user'];
        }
    }

    public function prepare () {
        unset($_SESSION['messages']);
        $_SESSION['messages'] = $this->messages;
    }

    public function isMessageWithName ($name) {
        return array_key_exists($name, $this->messages);
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

    public function connect (Model $user) {
        $_SESSION['user'] = $user->last[0];
        $_SESSION['user']->token = time()*rand(1,100);
        $user->where(self::$user)->update(['token'=>$_SESSION['user']->token]);
        self::$authenticate = true;
    }

    public function disconnect () {
        unset($_SESSION['user']);
        self::$authenticate = false;
        self::$user = null;
    }

    public static function isAuthenticate () {
        return self::$authenticate;
    }

    public static function Auth () {
        return self::$user;
    }

}
