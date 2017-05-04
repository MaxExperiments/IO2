<?php

class Session  {

    /**
     * Les données de l'utilisateur connecté
     * @var stdClass
     */
    protected static $user = null;

    /**
     * Est ce qu'un utilisateur est connecté
     * @var Boolean
     */
    protected static $authenticate;

    /**
     * Les messages à passer d'un page à l'autre à travers la session
     * @var Array
     */
    protected $messages = [];

    public function __construct () {
        if (array_key_exists('messages', $_SESSION)) $this->messages = $_SESSION['messages'];
        if (array_key_exists('user',$_SESSION)) {
            self::$authenticate = true;
            self::$user = $_SESSION['user'];
        }
    }

    /**
     * Récupère les messages de la session
     */
    public function prepare () {
        unset($_SESSION['messages']);
        $_SESSION['messages'] = $this->messages;
    }

    /**
     * Teste l'existence d'un message avec le nom donné
     * @param  String  $name Nom de la clef du message
     * @return boolean
     */
    public function isMessageWithName ($name) {
        return array_key_exists($name, $this->messages);
    }

    /**
     * Ajoute un message à passer dans la session
     * @param String $key     Clef du message
     * @param mixed  $message Contenu du message
     */
    public function addMessage ($key = null, $message) {
        if ($key==null) $this->messages[] = $message;
        else $this->messages[$key] = $message;
    }

    /**
     * @return Array retourne tous les messages
     */
    public function getMessages () { return $this->messages; }

    /**
     * Prend un message avec la clef donnée
     * @param  String $key Valeure de la clef
     * @return mixed       Valeure du message
     */
    public function getMessage ($key) {
        if (!array_key_exists($key, $this->messages)) return null;
        $r = $this->messages[$key];
        unset($this->messages[$key]);
        unset($_SESSION['messages'][$key]);
        return $r;
    }

    /**
     * Connecte un utilisateur à la session
     * @param  Model  $user Model ayant trouve l'utilisateur à connecter
     */
    public function connect (Model $user) {
        $_SESSION['user'] = $user->last[0];
        $_SESSION['user']->token = time()*rand(1,100);
        $user->where('id',self::$user->id)->update(['token'=>$_SESSION['user']->token]);
        self::$authenticate = true;
    }

    /**
     * Détruit la session de l'utilisateur
     */
    public function disconnect () {
        unset($_SESSION['user']);
        self::$authenticate = false;
        self::$user = null;
    }

    /**
     * @return boolean  Vrai si un utilisateur est connecté
     */
    public static function isAuthenticate () {
        return self::$authenticate;
    }

    /**
     * @return Array  Retoune les informations sur l'utilisateur connecté
     */
    public static function Auth () {
        return self::$user;
    }

    /**
     * Getter du token de la session
     * @return String token
     */
    public static function token () {
        return $_SESSION['user']->token;
    }

    /**
     * Modifie les attributs de l'utilisateur dans la session
     * @param  Array $vals Tableau associatif des nouvelles valeures
     */
    public static function authUpdate($vals) {
        foreach ($vals as $key => $value)
            if (isset($_SESSION['user']->$key)) $_SESSION['user']->$key = $value;
    }

}
