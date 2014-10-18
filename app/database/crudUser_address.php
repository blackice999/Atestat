<?php 
    require __DIR__. '/../../vendor/autoload.php';
    require __DIR__. '/config.php';

    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;

    /**
     * This class is responsible for
     * create, read, update, delete operations
     */
    class CrudUser_address extends Crud
    {
        public function __construct()
        {
            parent::__construct();
            $this->monolog = new Logger('crudUser_address');
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
        public function getUser_addressData_limit($limit = PHP_INT_MAX)
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
         * Fetches ID, userID, city, street, zip, country from user_address table
         * as an enumerated array if $type is 'row'
         * or as an object if $type is 'object'
         * @param  integer $ID
         * @param string $type Holds which way to fetch the data
         * @return resource
         */
        public function getUser_addressData_ID($ID, $type = 'row')
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

        /**
         * Updates City with $city parameter
         * @param  int $ID   The row ID
         * @param  string $city The new city name
         * @return string
         */
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

        /**
         * Updates street with $street parameter
         * @param  int $ID     The row ID
         * @param  string $street The new street name
         * @return string
         */
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

        /**
         * Updates zip with $zip parameter
         * @param  int $ID  The row ID
         * @param  string $zip The new zip code
         * @return string
         */
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
         * Updates country with $country parameter
         * @param  int $ID      The row ID
         * @param  string $country The new country
         * @return string
         */
        public function updateUser_addressCountry($ID, $country)
        {
            try
            {
                $query = "UPDATE `user_address` SET `country` = ? WHERE `ID` = ?";

                $stmt = $this->database->stmt->init();

                if ($stmt->prepare($query))
                {
                    $stmt->bind_param('si', $country, $ID);

                    $stmt->execute();

                    if ($stmt->execute())
                    {
                        echo "Updated user address country with " . $country;
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
         * Deletes data from 'user_address' table
         * @param  int $ID The row ID
         * @return string
         */
        public function deleteUser_address($ID)
        {
            try
            {
                $query = "DELETE FROM `user_address` WHERE `ID` = ?";

                $stmt = $this->database->stmt_init();

                if ($stmt->prepare($query))
                {
                    $stmt->bind_param('i', $ID);

                    $stmt->execute();

                    if ($stmt->execute())
                    {
                        //Delete data from memcached
                        $this->memcached->delete('user_address_' . $ID);
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
    }
?>