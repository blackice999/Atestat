<?php
    require __DIR__. '/../../vendor/autoload.php';
    require __DIR__. '/config.php';

    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;

    /**
     * This class is responsible for
     * create, read, update, delete operations
     */
    class CrudUser extends Config
    {
        public function __construct()
        {
            parent::__construct();
            $this->monolog = new Logger('crudUser');
        }

        /**
         * Inserts data into 'user' table
         * @param  string $email
         * @param  string $statusID
         * @param  string $password
         * @param  string $password_hash
         * @param  string $date_registered
         * @return string
         */
        public function insertUser($email, $statusID, $password, $password_hash, $date_registered)   
        {
            try
            {
                $query = "INSERT INTO `user` 
                (`email`,`statusID`,`password`,`password_hash`,`date_registered`)
                VALUES (?, ?, ?, ?, ?)";

                $stmt = $this->database->stmt_init();
                if ($stmt->prepare($query))
                {
                    $stmt->bind_param('sssss',
                        $email,
                        $statusID,
                        $password,
                        $password_hash,
                        $date_registered
                    );

                    $stmt->execute();

                    //If the execution is successful,
                    //display that it succeeded
                    //also insert data into Memcached
                    if ($stmt->execute())
                    {
                        echo "Data added successfully";
                        static $i;

                        $key = 'user_' . $i;
                        $user = array(
                            'id' => $i,
                            'email' => $email,
                            'statusID' => $statusID,
                            'date_registered' => $date_registered
                            );

                        $this->memcached->set($key, $user);
                    }

                    else
                    {
                        echo "Try again later";
                    }
                }
            }

            catch (Exception $e)
            {
                $this->generateLogCrud();
            }
        }

        /**
         * Gets data from 'user' table up to a given $limit
         * @param  int $limit    The number of rows to get
         * @return string
         */
        public function getUserData_limit($limit = PHP_INT_MAX)
        {
            try
            {
                $limit = intval($limit);

                $query = "SELECT `ID`,`email`,`statusID`, `date_registered`
                 FROM `user`
                 LIMIT $limit";

                 //If the cache is available, fetch data from it
                 if ($this->memcached)
                 {
                     $this->getFromMemcached($limit, 'user');
                 }
                 else
                 {

                     //If the cache isn't availabe, fetch from MySQL
                    if($result = $this->database->query($query))
                    {
                        while($data = $result->fetch_object())
                        {
                            echo "<table border='1'>";
                                echo "<tr>";
                                    echo "<td> " .$data->ID . "</td>";
                                    echo "<td> " .$data->email . "</td>";
                                    echo "<td> " .$data->statusID  . "</td>";
                                    echo "<td> " .$data->date_registered . "</td>";
                                echo "</tr>";
                            echo "</table>";
                        }
                    }
                }
            }

            catch (Exception $e)
            {
                $this->monolog->pushHandler(new StreamHandler(__DIR__.'/../logs/crud.log', Logger::ERROR));
                $this->monolog->addError('Failed query: (' . $e->getCode() . ') ' .$e->GetMessage());
            }

        }

        /**
         * Fetches the ID, email and date_registered rows from user table
         * as an enumerated array if $type is 'row'
         * or as an object if $type is 'object'
         * @param  integer $ID
         * @param  string $type Holds which way to fetch the data
         * @return resource
         */
        public function getUserData_ID($ID, $type = 'row')
        {
            try
            {
                $user = NULL;

                //Get user with id number $ID
                if ($this->memcached)
                {
                    $key = 'user_' . $ID;
                    $user = $this->memcached->get($key);
                }

                //If the cache can't be accessed, get the data from mysql
                if (!$user)
                {
                    $query = "SELECT `ID`,`email`, `statusID`, `date_registered`
                    FROM `user`
                    WHERE `ID`= ?";

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
                                printf ("%s (%s) %s\n",
                                 $row[0],
                                 $row[1],
                                 $row[2]
                                );
                            }
                        }

                        elseif ($type == 'object')
                        {
                            while ($obj = $result->fetch_array(MYSQLI_ASSOC))
                            {
                                printf ("%s (%s) %s\n",
                                 $obj['ID'],
                                 $obj['email'],
                                 $obj['date_registered']
                                );
                            }
                        }
                    }
                }
            }

            catch(Exception $e)
            {
                $this->generateLogCrud();
            }
        }

        /**
         * Updates email with $email parameter
         * @param  int $ID    The row ID
         * @param  string $email The new email
         * @return string
         */
        public function updateUserEmail($ID, $email)
        {
            try
            {
                $query = "UPDATE `user` SET `email` = ? WHERE `ID` = ?";

                $stmt = $this->datebase->stmt_init();

                if ($stmt->prepare($query))
                {
                    $stmt->bind_param('si',$email, $ID);

                    $stmt->execute();

                    if ($stmt->execute())
                    {
                        echo "Updated user email with:" . $email;
                    }

                    else
                    {
                        echo "Error updating data, try again later";
                    }
                }
            }

            catch (Exception $e)
            {
                $this->generateLogCrud();
            }
        }

        /**
         * Updates password with $password parameter
         * @param  int $ID       The row ID
         * @param  string $password The new password
         * @return string
         */
        public function updateUserPassword($ID, $password)
        {
            try
            {
                $query = "UPDATE `user` SET `password` = ? WHERE `ID` = ?";

                $stmt = $this->database->stmt_init();

                if ($stmt->prepare($query))
                {
                    $stmt->bind_param('si', $password, $ID);

                    $stmt->execute();

                    if ($stmt->execute())
                    {
                        echo "Updated user password with" . $password;
                    }

                    else
                    {
                        echo "Error updating data, try again later";
                    }
                }
            }

            catch (Exception $e)
            {
                $this->generateLogCrud();
            }
        }

        /**
         * Updates the users statusID with $statusID parameter
         * @param  int $ID       The row ID
         * @param  int $statusID the statusID from 'user_status' table
         * @return string
         */
        public function updateUserStatusID($ID, $statusID)
        {
            try
            {
                $query = "UPDATE `user` SET `statusID` = ? WHERE `ID` = ?";

                $stmt = $this->database->stmt_init();

                if ($stmt->prepare($query))
                {
                    $stmt->bind_param('ii', $statusID, $ID);

                    $stmt->execute();

                    if ($stmt->execute())
                    {
                        echo "Updated user statusID with: " . $statusID; 
                    }

                    else
                    {
                        echo "Error updating data, try again later";
                    }
                }
            }

            catch (Exception $e)
            {
                $this->generateLogCrud();
            }
        }

        /**
         * Deletes data from 'user' table
         * @param  int $ID The row ID
         * @return string
         */
        public function deleteUser($ID) 
        {
            try
            {
                $query = "DELETE FROM `user` WHERE `ID` = ?";

                $stmt = $this->database->stmt_init();

                if ($stmt->prepare($query))
                {
                    $stmt->bind_param('i', $ID);

                    $stmt->execute();

                    if ($stmt->execute())
                    {
                        //Delete data from memcached
                        $this->memcached->delete('user_' . $ID);
                        echo "Deleted successfully data with ID: " . $ID;

                    }

                    else
                    {
                        echo "Error deleting data, try again later";
                    }
                }
            }

            catch (Exception $e)
            {
                $this->generateLogCrud();
            }
        }

        /**
         * Creates the log for CRUD operations that will be saved to logs/crud.log
         * @return string
         */
        protected function generateLogCrud()
        {
            $this->monolog->pushHandler(new StreamHandler(__DIR__.'/../logs/crud.log', Logger::ERROR));
            $this->monolog->addError('Failed query: (' . $stmt->errno . ') ' .$stmt->error);
        }

        /**
         * Get data from Memcached
         * @param  int $limit The number of rows to get
         * @param  string $table The table from which to get the data
         * @return string
         */
        protected function getFromMemcached($limit, $table)
        {
            if ($this->memcached->getResultCode() == Memcached::RES_SUCCESS)
            {
                for ($i = 1; $i <= $limit; $i++)
                {
                    $key = $table . '_' . $i;
                    $this->memcached->get($key);
                }
            }
        }

        /**
         * @param  string $email The users email address
         * @param  string $statusID The foreign key to user_status('ID')
         * @param  string $date_registered The current time of registration
         * @return string
         */
        private function insertIntoMemcached($email, $statusID, $date_registered)
        {
            static $i = 2;

            $key = 'user_' . $i;
            $user = array(
                'id' => $i,
                'email' => $email,
                'statusID' => $statusID,
                'date_registered' => $date_registered
                );

            $this->memcached->set($key, $user);
            $i++;
        }
    }
?>