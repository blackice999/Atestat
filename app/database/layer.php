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
            try
            {
                $this->database = new PDO(
                    'mysql:host='. $this->databaseHost . ';
                    dbname=' . $this->databaseName,
                    $this->databaseUser,
                    $this->databasePassword
                );
            }

            catch(PDOException $e)
            {
                $this->monolog = new Logger('pdo');
                $this->monolog->pushHandler(new StreamHandler(__DIR__.'/../logs/pdoError.log', Logger::ERROR));
                $this->monolog->addError($e->getMessage());
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
    }
 ?>