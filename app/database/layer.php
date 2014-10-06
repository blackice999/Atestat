<?php

    require __DIR__. '/../../vendor/autoload.php';

    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;

    /**
     * This class is responsible for all the database interaction relating
     * the DB-layer task
     * Will be split into smaller files
     */
    class Layer
    {
        /**
         * Holds the database connection link
         * @var resource
         */
        private $database;

        /**
         * Holds the database host name
         * @var string
         */
        private $databaseHost = 'localhost';

        /**
         * Holds the database name
         * @var string
         */
        private $databaseName = 'loan-crm';

        /**
         * Holds the database users name
         * @var string
         */
        private $databaseUser = 'root';

        /**
         * Holds the database password
         * In Windows, there's no password,
         * In Ubuntu there is
         * Which one to use?
         * @var string
         */
        private $databasePassword = '';

        /**
         * Creates a new Monolog Logger instance
         * @var resource
         */
        private $monolog;

        /**
        * Connects to MySQL database using PDO
        */
        public function __construct()
        {
            $this->database = new mysqli(
                $this->databaseHost
                $this->databaseUser,
                $this->databasePassword
                $this->databaseName,
             );

            if ($this->database->connect_errno)
            {

                $this->monolog = new Logger('mysql');
                $this->monolog->pushHandler(new StreamHandler(__DIR__.'/../logs/mysqlError.log', Logger::ERROR));
                $this->monolog->addError(
                    'Failed to connect to mysql: (' .
                    $this->database->connect_errno . ')' .
                    $this->database->connect_error);
                die();
            }
        }

        /**
         * Destroys the database link
         */
        public function __destruct()
        {
            $this->database = NULL;
        }

        public function getData(...$parameters)
        {
            $this->database->query('SELECT ' . $parameters);
            return $this;
        }

        public function fromData($from)
        {
            $this->database->query('FROM' . $from);
            return $this;
        }

        public function whereData($where = null)
        {
            $this->database->query('WHERE' . $where);
            return $this;
        }
    }
 ?>