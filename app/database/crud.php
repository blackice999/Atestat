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
        public function insert($email, $statusID, $password, $password_hash, $date_registered)   
        {
            try
            {
                $query = "INSERT INTO `user` 
                (`email`,`statusID`,`password`,`password_hash`,`date_registered`)
                VALUES (?, ?, ?, ?, ?)
                WHERE `ID` = ?";

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

                    if ($stmt->execute())
                    {
                        echo "Data added successfully";
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
         * Deletes data from 'user' table
         * @param  int $ID The row ID
         * @return string
         */
        public function delete($ID) 
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
        private function generateLogCrud()
        {
            $this->monolog->pushHandler(new StreamHandler(__DIR__.'/../logs/crud.log', Logger::ERROR));
            $this->monolog->addError('Failed to add data: (' . $stmt->errno . ') ' .$stmt->error);
        }
    }
?>