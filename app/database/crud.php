<?php
    require __DIR__. '/../../vendor/autoload.php';
    require __DIR__ . '/layer.php';
    require __DIR__. '/config.php';

    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;

    /**
     * This class is responsible for
     * create, read, update, delete operations
     */
    class Crud extends Layer
    {
        public function __construct()
        {
            parent::__construct();
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
        public function getUserData($limit = PHP_INT_MAX)
        {
            try
            {
                $limit = intval($limit);

                //If the cache is available, fetch data from it
                if ($this->memcached())
                {
                    $this->getFromMemcached($limit, 'user');
                }

                //If the cache isn't availabe, fetch from MySQL
                else
                {

                    $query = "SELECT `ID`,`email`,`statusID`, `date_registered`
                     FROM `user`
                     LIMIT $limit";

                    if($result = $this->database->query($query))
                    {
                        while($data = $result->fetch_object())
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

            catch (Exception $e)
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
         * Inserts data into 'user_address' table
         * @param  int $userID  The foreign key to user('ID')
         * @param  string $city    The city of the user
         * @param  string $street  The street of the user
         * @param  int $zip     The Zip code of the user
         * @param  string $country The country of the user
         * @return string
         */
        public function insertUser_address($userID, $city, $street, $zip, $country)
        {
            try
            {
                $query = "INSERT INTO `user_address`
                (`userID`, `city`, `street`, `zip`, `country`)
                VALUES (?, ?, ?, ?, ?)";

                $stmt = $this->database->stmt_init();

                if ($stmt->prepare($query))
                {
                    $stmt->bind_param('sssis',
                        $userID,
                        $city,
                        $street,
                        $zip,
                        $country
                    );

                    $stmt->execute();

                    //If the execution is successful,
                    //display that it succeeded
                    //also insert data into Memcached
                    if ($stmt->execute())
                    {
                        echo "Data added successfully";
                        static $i;

                        $key = 'user_address_' . $i;
                        $user_address = array(
                            'id' => $i,
                            'userID' => $userID,
                            'city' => $city,
                            'street' => $street,
                            'zip' => $zip,
                            'country' => $country
                            );

                        $this->memcached->set($key, $user_address);
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
         * Get data from `user_address` table up to a given $limit
         * @param  int $limit The number of rows to get
         * @return string
         */
        public function getUser_addressData($limit = PHP_INT_MAX)
        {
            try
            {
                $limit = intval($limit);

                //If the cache is availabe, fetch data from it
                if ($this->memcached)
                {
                   $this->getFromMemcached($limit, 'user_address');
                }

                //If the cache isn't available, fetchfrom MySQL
                else
                {
                    $query = "SELECT `ID`, `userID`, `city, `street`, `zip`, `country`
                    FROM `user_address`
                    LIMIT $limit";

                    if ($result = $this->database->query($query))
                    {
                        while ($data = $result->fetch_object())
                        {
                            echo "<table border='1'>";
                            echo "<tr>";
                                echo "<td> " .$data->ID . "</td>";
                                echo "<td> " .$data->userID . "</td>";
                                echo "<td> " .$data->city  . "</td>";
                                echo "<td> " .$data->street . "</td>";
                                echo "<td> " .$data->zip . "</td>";
                                echo "<td> " .$data->country . "</td>";
                            echo "</tr>";
                        echo "</table>";
                        }
                    }
                }
            }

            catch (Exception $e)
            {
                $this->generateLogCrud();
            }
        }

        /**
         * Updates the user's address userID with $userID parameter
         * @param  int $ID     The row ID
         * @param  int $userID The userID from 'user' table
         * @return string
         */
        public function updateUser_addressUserID($ID, $userID)
        {
            try
            {
                $query = "UPDATE `user_address` SET `userID` = ? WHERE `ID` = ?";

                $stmt = $this->database->stmt_init();

                if ($stmt->prepare($query))
                {
                    $stmt->bind_param('ii', $userID, $ID);

                    $stmt->execute();

                    if($stmt->execute())
                    {
                        echo "Updated user address userID with: " . $userID; 
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

        public function updateUser_addressCity($ID, $city)
        {
            try
            {
                $query = "UPDATE `user_address` SET `city` = ? WHERE `ID` = ?";

                $stmt = $this->database->stmt->init();

                if ($stmt->prepare($query))
                {
                    $stmt->bind_param('si', $city, $ID);

                    $stmt->execute();

                    if ($stmt->execute())
                    {
                        echo "Updated user address city with " . $city;
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

        public function updateUser_addressStreet($ID, $street)
        {
            try
            {
                $query = "UPDATE `user_address` SET `street` = ? WHERE `ID` = ?";

                $stmt = $this->database->stmt->init();

                if ($stmt->prepare($query))
                {
                    $stmt->bind_param('si', $street, $ID);

                    $stmt->execute();

                    if ($stmt->execute())
                    {
                        echo "Updated user address street with " . $street;
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

        public function updateUser_addressZip($ID, $zip)
        {
            try
            {
                $query = "UPDATE `user_address` SET `zip` = ? WHERE `ID` = ?";

                $stmt = $this->database->stmt->init();

                if ($stmt->prepare($query))
                {
                    $stmt->bind_param('ii', $zip, $ID);

                    $stmt->execute();

                    if ($stmt->execute())
                    {
                        echo "Updated user address zip with " . $zip;
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
         * Creates the log for CRUD operations that will be saved to logs/crud.log
         * @return string
         */
        private function generateLogCrud()
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
        private function getFromMemcached($limit, $table)
        {
            for ($i = 0; $i <= $limit, $i++)
            {
                $key = $table . '_' . $i;
                $this->memcached->get($key);
            }
        }
    }
?>