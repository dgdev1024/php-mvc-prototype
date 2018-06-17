<?php
    /**
     * A class used for connecting to a database.
     */
    class database {
        private $host       = CONFIG['database']['host'];
        private $user       = CONFIG['database']['user'];
        private $pass       = CONFIG['database']['pass'];
        private $charset    = CONFIG['database']['charset'];
        private $pdo        = null;
        private $queries    = array();

        public function __construct (string $dbname) {
            $data_source_name = 'mysql:' .
                'host=' . $this->host . ';' .
                'dbname=' . $dbname . ';' .
                'charset=' . $this->charset . ';';

            $options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            );

            $this->pdo = new PDO(
                $data_source_name,
                $this->user,
                $this->pass,
                $options
            );
        }

        public function add_query (string $name, string $query) {
            if ($this->pdo === null) {
                return false;
            }

            if (isset($this->queries[$name])) {
                unset($this->queries[$name]);
            }

            $this->queries[$name] = $this->pdo->prepare($query);
            return true;
        }

        public function remove_query (string $name) {
            if ($this->pdo === null) {
                return false;
            }

            if (array_key_exists($name, $this->queries) === true) {
                unset($this->queries[$name]);
                return true;
            } else {
                return false;
            }
        }

        public function execute_query (string $name, array $params = array()) {
            if ($this->pdo === null) {
                return null;
            }

            if (array_key_exists($name, $this->queries) === true) {
                $this->queries[$name]->execute($params);
                return $this->queries[$name];
            } else {
                return null;
            }
        }
    }
?>