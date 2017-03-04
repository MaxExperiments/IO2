<?php

/**
 * Class servant a se connecter a la base de donnee
 */
class Model {

    use Filters;

    /**
     * La clef primaire de la table dans la base de donnee
     * par defaut le champ ID
     * @var string
     */
    protected $primary_key = 'id';

    /**
     * Nom de la table associe au model
     * @var String
     */
    protected $table;

    /**
     * Requete SQL
     * @var String
     */
    protected $query;

    /**
     * Toutes les restrictions de recherche dans la requete
     * @var array
     */
    protected $where = [];

    /**
     * Tous les champs a selectionner
     * @var array
     */
    protected $fields = [];

    public $attributes = [];
    
    protected $validation = [];

    public $messages = [];

    /**
     * Connection a la base donne
     * @var PDO
     */
    protected static $db;

    /**
     * Permet de tester rapidement si la connection a ete faite avec la base de donne
     * @var boolean
     */
    protected static $isConnected = false;

    protected static $filtersText = [
        'required' => 'Vous devez remplir ce champ',
        'max'      => 'Trop long'
    ];

    /**
     * Instancie la connection avec la base de donne lors de la premiere requete SQL
     * @throws DatabaseException Si la connection avec la base de donnee n'as pas pu etre faite
     */
    private function connect () {
        try {
            self::$db = new PDO(
                CONFIG['database']['driver'] . ':host=' . CONFIG['database']['host'] . 
                ((!empty(CONFIG['database']['port'])) ? ';port=' . CONFIG['database']['port'] : '') . 
                ';dbname=' . CONFIG['database']['database'],
                CONFIG['database']['username'],
                CONFIG['database']['password']
            );
            self::$isConnected = true;
        } catch (PDOException $e) {
            throw new DatabaseException('Can not connect to the database ' . CONFIG[database][database], $e->getCode());
        }
    }

    /**
     * Permet de choisir les champs a selectionne
     * @param  array  $fields Liste des champs a selectionner
     * @return Model          Retourne $this pour pouvoir composer facilement les fonctions
     */
    public function select ($fields = []) {
        foreach ($fields as $name => $val) $this->fields[$name] = $val;
        return $this;
    }

    /**
     * Ajoute des contraintes a la requete avec le mot clef WHERE
     * @param  array  $where Tableau associatif des champs et de la valeur qu'il doivent avoir
     * @return Model          Retourne $this pour pouvoir composer facilement les fonctions
     */
    public function where ($where = []) {
        foreach ($where as $name => $val) $this->where[$name] = $val;
        return $this;
    }

    /**
     * Fait une selection dans la table du model avec les contraintes definies au prealables
     * @return Array
     */
    public function get () {
        $this->query = 'SELECT ';
        if ($this->fields === []) $this->query .= '*';
        else foreach ($this->fields as $val) $this->query .= $val . ',';
        $this->query = rtrim($this->query,',') . ' FROM ' . ((!empty($this->table)) ? $this->table : strtolower(get_class(__CLASS__))) . ((!empty($this->where)) ? ' WHERE ' : '');

        $prepare = [];
        foreach ($this->where as $key => $value) {
            $k = ':' . $key;
            $prepare[$k] = $value;
            $this->query .= $key . ' = ' . $k . ' ';
        }

        return $this->getPDO($prepare);
    }

    public function find ($id) {
        $this->where([$this->primary_key=>$id]);
        return $this->get();
    }

    public function findFirst ($id) {
        $this->find($id);
        $var = array_values($this->get());
        return (empty($var)) ? [] : $var[0];        
    }

    public function findAll ($where = []) {
        $this->where($where);
        return $this->get();
    }

    public function insert ($data) {

    }

    public function validate ($data) {
        $validated = true;
        foreach ($data as $field => $value) {
            if (array_key_exists($field, $this->validation)) {
                foreach ($this->validation[$field] as $filter) {
                    $f = explode(':', $filter);
                    array_splice($f, 1, 0, $value);
                    
                    if (!call_user_func_array([$this,$f[0]], array_slice($f, 1))) {
                        $this->messages[$field] = ((array_key_exists($field, $this->messages)) ? $this->messages[$field] : '') . ' ' . self::$filtersText[$f[0]];
                        $this->messages[$field] = trim($this->messages[$field]);
                        $validated = false;
                    }
                }
            }
        }
        return $validated;
    }

    /**
     * Seule fonction utilisant l'objet PDO
     * @param  Array $prepare  Toutes la valeures a injecter dans la requete SQL
     * @return Array           Tableau php representant le resultat de la requete
     */
    private function getPDO ($prepare= []) {
        if (!self::$isConnected) $this->connect();
        $statement = self::$db->prepare($this->query);
        $statement->execute($prepare);
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

}