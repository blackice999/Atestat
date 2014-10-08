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
        private $databasePassword = 'sampwoS1';

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
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            $this->database = new mysqli(
                $this->databaseHost,
                $this->databaseUser,
                $this->databasePassword,
                $this->databaseName
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

        /**
         * Fetches the ID, email and date_registered rows from user table
         * as an enumerated array
         * @param  integer $ID
         * @return resource
         */
        public function fetchRowUser($ID)
        {
            $query = 'SELECT `ID`,`email`, `date_registered` FROM `user` WHERE = ?';
            $stmt = $this->database->prepare($query);
            $stmt = $this->database->bind_param('i', $ID);

            try
            {
                if ($stmt->execute())
                {
                    while ($row = $stmt->fetch_row)
                    {
                        printf ("%s (%s)\n",
                         $row[0],
                         $row[1],
                         $row[2]
                        );
                    }
                }
            }

            catch(Exception $e)
            {
                return $this->monolog->addError('Error:' . $this->database->error);
            }
        }

        /**
         * Fetches ID, userID, city, street, zip, country from user_address table
         * as an enumerated array
         * @param  integer $ID
         * @return resource
         */
        public function fetchRowUser_address($ID)
        {
            $query = 'SELECT `ID`,`userID`, `city`, `street`, `zip`,`country` FROM `user_address` WHERE = ?';
            $stmt = $this->database->prepare($query);
            $stmt = $this->database->bind_param('i', $ID);

            try
            {
                if ($stmt->execute())
                {
                    while ($row = $stmt->fetch_row)
                    {
                        printf ("%s (%s)\n",
                         $row[0],
                         $row[1],
                         $row[2],
                         $row[3],
                         $row[4],
                         $row[5]
                        );
                    }
                }
            }

            catch(Exception $e)
            {
                return $this->monolog->addError('Error:' . $this->database->error);
            }
        }

        /**
         * Fetches ID, email, date_registered from user table
         * as an object
         * @param  integer $ID
         * @return resource
         */
        public function fetchObjectUser($ID)
        {
            $query = 'SELECT `ID`,`email`, `date_registered` FROM `user` WHERE = ?';
            $stmt = $this->database->prepare($query);
            $stmt = $this->database->bind_param('i', $ID);

            try
            {
                if ($stmt->execute())
                {
                    while ($obj = $stmt->fetch_object)
                    {
                        printf ("%s (%s)\n",
                         $obj->ID,
                         $obj->email,
                         $obj->date_registered);
                    }
                }
            }

            catch(Exception $e)
            {
                return $this->monolog->addError('Error:' . $this->database->error);
            }
        }

        /**
         * Fetches ID, userID, city, street, zip, country from user_address
         * as an object
         * @param  intger $ID
         * @return resource
         */
        public function fetchObjectUser_address($ID)
        {
            $query = 'SELECT `ID`,`userID`, `city`, `street`, `zip`,`country` FROM `user_address` WHERE = ?';
            $stmt = $this->database->prepare($query);
            $stmt = $this->database->bind_param('i', $ID);

            try
            {
                if ($stmt->execute())
                {
                    while ($row = $stmt->fetch_row)
                    {
                        printf ("%s (%s)\n",
                         $obj->ID,
                         $obj->userID,
                         $obj->city,
                         $obj->street,
                         $obj->zip,
                         $obj->country
                        );
                    }
                }
            }

            catch(Exception $e)
            {
                return $this->monolog->addError('Error:' . $this->database->error);
            }
        }

    }
 ?>