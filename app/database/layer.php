<?php

    require __DIR__. '/../../vendor/autoload.php';
    require __DIR__. '/config.php';

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
        protected $database;

        /**
         * Creates a new Monolog Logger instance
         * @var resource
         */
        protected $monolog;
       
        /**
         * Creates a new config class where database info is stored
         * @var resource
         */
        protected $config;

        /**
         * Connects to Memcached
         * @var resource
         */
        protected $memcached;

        public function __construct()
        {
            $this->config = new Config();

            $this->memcached = new Memcached();
            $this->memcached->addServer('127.0.0.1', '11211');

            $this->monolog = new Logger('mysql');

            try
            {
                $this->database = new mysqli(
                    $config->databaseHost,
                    $config->databaseUser,
                    $config->databasePassword,
                    $config->databaseName
                 );
            }
            catch (Exception $e)
            {
                $this->monolog->pushHandler(new StreamHandler(__DIR__.'/../logs/mysqlError.log', Logger::ERROR));
                $this->monolog->addError(
                    'Failed to connect to mysql: (' .
                    $e->getCode() . ') ' .
                    $e->getMessage());
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
         * Fetches ID, userID, city, street, zip, country from user_address table
         * as an enumerated array if $type is 'row'
         * or as an object if $type is 'object'
         * @param  integer $ID
         * @param string $type Holds which way to fetch the data
         * @return resource
         */
        public function fetchUser_address($ID, $type = 'row')
        {
            try
            {
                $user = NULL;

                //Get user address with id $ID
                if ($this->memcached)
                {
                    $key = 'user_' . $ID;
                    $user = $this->memcached->get($key);
                }

                //If the cache can't be accessed, get the data from mysql
                if (!$user)
                {

                    $query = 'SELECT `ID`,`userID`, `city`, `street`, `zip`,`country`
                    FROM `user_address`
                    WHERE `ID`= ?';

                    $stmt = $this->database->stmt_init();

                    if($stmt->prepare($query))
                    {
                        $stmt->bind_param('i', $ID);

                        $stmt->execute();

                        $result = $stmt->get_result();

                        if ($type == 'row')
                        {
                            while ($row = $result->fetch_array(MYSQLI_NUM))
                            {
                                printf ("%s (%s) %s %s %s\n",
                                 $row[0],
                                 $row[1],
                                 $row[2],
                                 $row[3],
                                 $row[4],
                                 $row[5]
                                );
                            }
                        }

                        elseif ($type == 'object')
                        {
                            while ($row = $result->fetch_array(MYSQLI_ASSOC))
                            {
                                printf ("%s (%s) %s %s %s\n",
                                 $row['ID'],
                                 $row['userID'],
                                 $row['city'],
                                 $row['street'],
                                 $row['zip'],
                                 $row['country']
                                );
                            }
                        }
                    }
                }
            }

            catch(Exception $e)
            {
                $this->generateLogMysql();
            }
        }

        /**
         * Generates MySQL log that will be saved to logs/mysqlError.log
         * @return string
         */
        private function generateLogMysql()
        {
            $this->monolog->pushHandler(new StreamHandler(__DIR__.'/../logs/mysqlError.log', Logger::ERROR));
            $this->monolog->addError('Error:  (' .$stmt->errno . ') ' . $stmt->error);
        }
    }
?>