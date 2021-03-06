<?php 
	error_reporting(E_ALL);
    ini_set('display_errors',1);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    //Holds a string to the site name, in order to simply <title> tags
    $site_name = 'Prototype CRM';

    // require __DIR__. '/../../vendor/autoload.php';

    // use Monolog\Logger;
    // use Monolog\Handler\StreamHandler;

    /**
     * This class holds the database connection info
     */
    class Database
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
        protected $databasePassword = 'admin';

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
            // $this->memcached = new Memcached();
            // $this->memcached->addServer('127.0.0.1', '11211');

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
                // $this->monolog = new Logger('mysql');
                // $this->monolog->pushHandler(new StreamHandler(__DIR__.'/../logs/mysqlError.log', Logger::ERROR));
                // $this->monolog->addError(
                //     'Failed to connect to mysql: (' .
                //     $e->getCode() . ') ' .
                //     $e->getMessage());
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
            //use myql_close
            $this->database->close();
            $this->database = NULL;
        }

        //@param sqlString string
        //return boolean
        /**
         * Runs the given $sqlString
         * @param  string $sqlString
         * @param  array  $param
         * @return boolean
         */
        public function runQuery($sqlString, array $param)
        {
            try
            {
                //$this->database istanceOf mysqli
                if ($this->database instanceof mysqli)
                {
                    //run the query method
                    $this->execute = $this->database->query($sqlString);
                    
                    return $this->execute;
                }
            }

            catch (Exception $e)
            {
                return false;
            }
        }

        /**
         * Binds the given query
         * @param  string $sqlString
         * @param  array  $param     The given bindTypes and bindVariables
         * @return boolean
         */
        public function bindQuery($sqlString, array $param)
        {
            try
            {
                $stmt = $this->database->stmt_init();
                if ($stmt->prepare($sqlString))
                    {
                        // foreach ($param['bindVariables'] as $variables)
                        // {
                        //     $stmt->bind_param($param['bindTypes'], $variables);
                        // }

                        //Calls call_user_func_array
                        //to dynamically pass the params array
                        call_user_func_array(
                            array($stmt, "bind_param"),
                            array_merge(
                                array($param['bindTypes']), $param['bindVariables']
                            )
                        );

                        //If the query starts with SELECT
                        //It means a result set will be returned
                        if (strpos($sqlString, "SELECT") !== false)
                        {
                            $stmt->execute();
                            return $this->result = $stmt->get_result();
                        }

                        return $stmt->execute();
                    }
            }

            catch (Exception $e)
            {
                $this->error = $this->database->error;
            }
        }

        /**
         * Returns a result array from $sqlString
         * @param  string $query
         * @param  const $type  Numerical or associative array
         * @return string
         */
        public function getArray($sqlString, $type = MYSQLI_NUM)
        {
            return $sqlString->fetch_array($type);
        }


        /**
         * Checks if the update query is successful
         * @param  string $sqlString
         * @param  array  $param
         * @return boolean
         */
        public function updateQuery($sqlString, array $param)
        {
            $runQueryResult = $this->runQuery($sqlString,$param);

            // if ($runQueryResult instanceof mysqli)
            if ($runQueryResult)
            {
               return $this->database->affected_rows;

               //To fix why this doesn't return anything
            }

            return false;
        }

        /**
         * Checks if the current email given is in the database
         * @param  string $email
         * @return boolean       Returns true if exists, false otherwise
         */
        public function emailExists($email)
        {
            try
            {
                $bindArray = array(
                    'bindTypes' => 's',
                    'bindVariables' => array(&$email)
                    );

                $bind = $this->bindQuery(
                    "SELECT `email` FROM `user` WHERE `email` = ? LIMIT 1",
                    $bindArray
                    );

               $info = $this->getArray($bind);

                if (is_array($info) && !empty($info))
                {
                    return true;
                }

                else
                {
                    return false;
                }
            }

            catch (Exception $e)
            {
                return false;
            }
        }

        public function authorizeAccess($email, $password)
        {
            try
            {
                $bindArray = array(
                    'bindTypes' => 's',
                    'bindVariables' => array(&$email)
                    );

                $bind = $this->bindQuery(
                    "SELECT `ID`, `password` FROM `user` WHERE `email` = ? LIMIT 1",
                    $bindArray
                    );

                $info = $this->getArray($bind);
                
                if (password_verify($password, $info[1]) && $this->emailExists($email))
                {
                    return $info[0];
                }

                else
                {
                    return false;
                }
            }

            catch (Exception $e)
            {
                return false;
            }
        }
    }
?>