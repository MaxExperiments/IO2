<?php

/**
 * Class servant a se connecter a la base de donnee
 */
class Model {

    /**
     * import de toutes les fonctions filtres
     */
    use Model\Filters;

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
     * Existence des champs `created_at` et `updated_at` dans la table
     * @var boolean
     */
    protected $timesteamps = true;

    /**
     * Jointure de tables systématiques du type appartient à
     * @var Array
     */
    protected $belongsTo = [];

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
     * Toutes les conditions en OR
     * @var array
     */
    protected $or  = [];

    /**
     * Tous les champs a selectionner
     * @var array
     */
    protected $fields = [];

    /**
     * Les champs à ordonner
     * @var Array
     */
    protected $order = [];

    /**
     * Première valeure selectionnée
     * Faux si n'est pas précisé
     * @var int|boolean
     */
    protected $from = false;

    /**
     * Nombre de valeures maximum a selectionner
     * Faux si n'est pas précisé
     * @var int|boolean
     */
    protected $number = false;

    /**
     * Tous les champs a hasher dans la base de donnée
     * @var Array
     */
    protected $hash = ['password'];

    /**
     * Type de formulaire pour chaque valeure dans la table
     * @var array
     */
    public $attributes = [];

    /**
     * Filtres a valider dans un formulaire
     * @var array
     */
    protected $validation = [];

    /**
     * Messages issus de la validation des filtres
     * @var array
     */
    public $messages = [];

    /**
     * Contenu obtenu apres la derniere requete SQL du model
     * @var array
     */
    public $last = [];

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

    /**
     * Les textes d'erreurs par defaut des filtres
     * @var Array
     */
    protected static $filtersText = [
        'required' => 'Vous devez remplir ce champ',
        'max'      => 'Trop long',
        'min'      => 'Trop court',
        'unique'   => 'Déja pris',
        'match'    => 'Caractères interdits',
        'isImage'  => 'Ce fichier doit être une image',
        'maxImageSize' => 'Image trop grande',
        'fileType' => 'Type de fichier non supporté'
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
            throw new DatabaseException('Can not connect to the database ' . CONFIG['database']['database'], $e->getCode());
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
     * @param  String $field  La nom du champ sur lequel s'applique la condition
     * @param  String $a      La valeure du champ ou le comparateur si $b est non nul
     * @param  String $b      Si $b est non nul il est la valeure du champ
     * @return Model          Retourne $this pour pouvoir composer facilement les fonctions
     */
    public function where ($field, $a, $b = null) {
        if ($b === null) $this->where[] = $this->table.'.'.$field . '=\'' . addslashes($a) . '\'';
        else $this->where[] = $this->table.'.'.$field . ' ' . $a . ' \'' . addslashes($b) . '\'';
        return $this;
    }

    /**
     * Ajoute des contraintes a la requete avec le mot clef WHERE et le séparateur OR
     * @param  String $field  La nom du champ sur lequel s'applique la condition
     * @param  String $a      La valeure du champ ou le comparateur si $b est non nul
     * @param  String $b      Si $b est non nul il est la valeure du champ
     * @return Model          Retourne $this pour pouvoir composer facilement les fonctions
     */
    public function or ($field, $a, $b) {
        if ($b === null) $this->or[] = $this->table.'.'.$field . '=\'' . addslashes($a) . '\'';
        else $this->or[] = $this->table.'.'.$field . ' ' . $a . ' \'' . addslashes($b) . '\'';
        return $this;
    }

    /**
     * Défini l'ordre des sortie de la requête SQL
     * @param  String $ord  Valeure de l'ordre ASC|DESC|...
     * @param  String $fiel Le champ a ordonner
     * @return Model        Retourne $this pour pouvoir composer facilement les fonctions
     */
    public function order ($field, $ord) {
        $this->order[$field] = $ord;
        return $this;
    }

    /**
     * Modifie les valeures de limites de la selection
     * @param  int|boolean $f premiere indice a selectionner
     * @param  int|boolean $n nombre d'indices a selectionner
     */
    public function limit($f,$n) {
        $this->from = $f;
        $this->number = $n;
        return $this;
    }

    /**
     * Fait une selection dans la table du model avec les contraintes definies au prealables
     * @return Array
     */
    public function get () {
        $this->query = 'SELECT ';
        if ($this->fields === []) $this->query .= '*';
        else foreach ($this->fields as $name => $val) $this->query .= $val . ((is_string($name)) ? ' AS '.$name : '') . ',';
        $this->query = rtrim($this->query,',') . ' FROM ' . ((!empty($this->table)) ? $this->table : strtolower(get_class(__CLASS__)));
        $this->clear();

        if (!empty($this->belongsTo)) {
            $this->query .= ' INNER JOIN ';
            foreach ($this->belongsTo as $table => $assoc) {
                $this->query .= $table . ' ON ';
                foreach ($assoc as $innerkey => $key)
                    $this->query .= $this->table.'.'.$innerkey . ' = ' . $table.'.'.$key . ' ';
            }
        }

        $this->insertWhereClosure();
        $this->insertOrderClosure();
        $this->insertLimitClosure();
        return $this->getPDO($this->last);
    }

    /**
     * Compte la nombre de lignes séléctionnées
     * @return int
     */
    public function count () {
        $this->query = 'SELECT COUNT(*) as count ';
        $this->query = rtrim($this->query,',') . ' FROM ' . ((!empty($this->table)) ? $this->table : strtolower(get_class(__CLASS__)));
        $this->clear();
        $this->insertWhereClosure();
        $posts = $this->getPDO($this->last);
        return $posts[0]->count;
    }

    /**
     * Trouve tous les champs avec l'id donne
     * @param  int $id Valeure de l'id
     * @return Array
     */
    public function find ($id) {
        $this->where($this->primary_key,$id);
        return $this->get();
    }

    /**
     * retrouve uniquement le premier element avec l'id
     * @param  int $id Valeure de l'id
     * @return Array
     */
    public function findFirst ($id) {
        $this->find($id);
        return (empty($this->last)) ? [] : $this->last[0];
    }

    /**
     * Trouve tous les elements correspondant au model
     * @return Array
     */
    public function findAll () {
        return $this->get();
    }

    /**
     * Ajoute une nouvelle valeure dans la table
     * @param  Array $data  Tableau associatif nomDuChamp => valeure
     */
    public function insert ($data) {
        $this->query = 'INSERT INTO ' . $this->table . ' (';
        $fields = ''; $values = '';
        foreach ($data as $field => $value) {
            $fields .= $field . ',';
            $values .= ':' . $field . ',';
            unset($data[$field]);
            $this->last[':'.$field] = $value;
        }
        $this->query = $this->query . rtrim($fields, ',') . ') VALUES (' . rtrim($values,',') . ')';
        return $this->getPDO();
    }

    /**
     * Modifie les valeures du tableau dans la table
     * @param  Array $data Les nouvelles donees
     */
    public function update ($data) {
        $this->query = 'UPDATE ' . $this->table .' SET ';
        $this->last = [];
        foreach ($data as $field => $value) {
            $this->query .= $field . '=' . ':' . $field . ',';
            $this->last[':'.$field] = $value;
        }
        $this->query = rtrim($this->query,',');
        $this->insertWhereClosure();
        return $this->getPDO();
    }

    public function delete () {
        $this->query = 'DELETE FROM ' . $this->table;
        $this->insertWhereClosure();
        return $this->getPDO();
    }

    /**
     * Test tous les filtres sur la tableau de donnees
     * @param  Array $data Tableau de donnes a tester
     * @return Boolean
     */
    public function validate ($data) {
        $validated = true;
        foreach ($data as $field => $value) {
            if (array_key_exists($field, $this->validation)) {
                foreach ($this->validation[$field] as $filter) {
                    $f = explode(':', $filter);
                    array_splice($f, 1, 0, [$value]);
                    array_splice($f, 1, 0, $field);

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
     * Ajoute le mot clef WHERE avec les conditions prédéfinies dans le model
     */
    private function insertWhereClosure() {
        $this->query .= (!empty($this->where)) ? ' WHERE ' : '';
        foreach ($this->where as $value) $this->query .= $value . ' AND ';
        $this->query = rtrim($this->query,' AND ');

        $this->query .= (!empty($this->or)) ? ' OR ' : '';
        foreach ($this->or as $value) $this->query .= $value . ' OR ';
        $this->query = rtrim($this->query,' OR ');
    }

    /**
     * Ajoute les contraintes d'ordres sur les colonnes
     */
    private function insertOrderClosure () {
        $this->query .= (!empty($this->order)) ? ' ORDER BY ' : '';
        foreach ($this->order as $key => $ord) {
            $this->query .= $this->table.'.'.$key . ' ' . $ord . ', ';
        }
        $this->query = rtrim($this->query,', ');
    }

    /**
     * Ajoute les contraintes de limites sur les données selectionnées
     */
    private function insertLimitClosure () {
        if ($this->from && $this->number) $this->query .= ' LIMIT ' . $this->from . ',' . $this->number;
        else if ($this->number) $this->query .= ' LIMIT ' . $this->number;
    }

    private function clear () {
        $this->last = [];
    }

    /**
     * Seule fonction utilisant l'objet PDO
     * @return Array           Tableau php representant le resultat de la requete
     */
    private function getPDO () {
        foreach ($this->last as $field => $val)
            if (in_array(trim($field,':'),$this->hash)) $this->last[$field] = password_hash($val,PASSWORD_BCRYPT);
        if (!self::$isConnected) $this->connect();
        $statement = self::$db->prepare($this->query);
        $statement->execute($this->last);
        $this->last = $statement->fetchAll(PDO::FETCH_OBJ);
        return $this->last;
    }

}
