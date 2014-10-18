<?php 
	ini_set('display_errors',1);
	error_reporting(E_ALL);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    require __DIR__. '/../../vendor/autoload.php';

    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;

    /**
     * This class holds the database connection info
     */
    class Config
    {
        /**
         * Holds the database connection link
         * @var resource
         */
        protected $database;

        /**
         * Holds the database host name
         * @var string
         */
        protected $databaseHost = 'localhost';

        /**
         * Holds the database name
         * @var string
         */
        protected $databaseName = 'loan-crm';

        /**
         * Holds the database users name
         * @var string
         */
        protected $databaseUser = 'root';

        /**
         * Holds the database password
         * In Windows, there's no password,
         * In Ubuntu there is
         * Which one to use?
         * @var string
         */
        protected $databasePassword = 'sampwoS1';

        /**
         * Connects to Memcached
         * @var resource
         */
        protected $memcached;

        /**
         * Creates a new Monolog Logger instance
         * @var resource
         */
        protected $monolog;

        public function __construct()
        {
            $this->memcached = new Memcached();
            $this->memcached->addServer('127.0.0.1', '11211');

            try
            {
                $this->database = new mysqli(
                    $this->databaseHost,
                    $this->databaseUser,
                    $this->databasePassword,
                    $this->databaseName
                 );
            }
            catch (Exception $e)
            {
                $this->monolog = new Logger('mysql');
                $this->monolog->pushHandler(new StreamHandler(__DIR__.'/../logs/mysqlError.log', Logger::ERROR));
                $this->monolog->addError(
                    'Failed to connect to mysql: (' .
                    $e->getCode() . ') ' .
                    $e->getMessage());
                die();
                    
            }
        }

        /**
         * Check if the server is alive
         * Send error to log if connection is down
         * @return string Returns friendly notice to try again later
         */
        public function checkConnectionStatus()
        {
           if(!$this->database->ping())
           {
                echo "Please try again later";
                $this->monolog->addError("Error:" . $this->database->error);
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