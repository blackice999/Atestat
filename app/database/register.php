<?php

class Register extends Database {

    //Variables for user address
    public $city;
    public $street;
    public $zip;
    public $country;
    private $userID;    
    
    //Variables for user profile
    public $email;
    public $password;

    //Variables for correct values
    private $validCities = array();
    private $validStreets = array();
    private $validCountries = array();
    private $validZips = array();

    //Holds error like if city or street is missing etc.
    private $errors = array();

    /**
     * Creates arrays holding the correct values
     */
    public function __construct()
    {
        parent::__construct();
        $this->validCities = array("Baia Mare", "Oradea", "Bucuresti");
        $this->validStreets = array("Nothing", "Bd.Bucuresti 17", "Decebal");
        $this->validCountries = array("Romania", "Germany", "America");
        $this->validZips = array("123456789", "987654321");
    }

    /**
     * Checks if all the address values are correct
     * @return boolean
     */
    public function isAdressValid()
    {
        //check all the address variables (city, street, etc.)
        if (!$this->isCityValid() || !$this->isStreetValid() || !$this->isZipValid() || !$this->isCountryValid())
        {
            return false;
        }

        return true;
    }

    public function isUserProfileValid()
    {
        //check the userprofile(email, password)

        if (!empty($this->email))
        {
            //do something
        }
    }

     /**
        * Inserts the new person
        * @param  string $email    The persons email
        * @param  int $statusID foreign key referencing the status of the person
        * @param  string $password The persons password
        * @return boolean           If it succeeds, returns true, otherwise false
     */
    public function insertUser($email, $statusID, $password)
    {
        try
        {

            if (!$this->isUserProfileValid())
            {
                return false;
            }

            //Sanitize the email
            $email = filter_var($this->email, FILTER_SANITIZE_EMAIL);

            //Sanitize the password
            $password = filter_var($this->password, FILTER_SANITIZE_STRING);

            $statusID = intval($statusID);

            // $date = date("Y-m-d H:i:s");

            //Maybe add a personal salt, but it's not effective
            $password = password_hash($password, PASSWORD_BCRYPT);

            $bindArray = array(
                'bindTypes' => 'sis',
                'bindVariables' => array(&$email, &$statusID, &$password)
                );

            $insert = $this->bindQuery(
                "INSERT INTO `user` (`email`, `statusID`, `password`)
                VALUES (?,?,?)",
                $bindArray
                ); 
            
        }

        catch (Exception $e)
        {
            return false;
        }
    }


    /**
     * Inserts a new user into database
     * @param  string $email
     * @param  int $statusID A foreign key to (user_status)
     * @param  string $password
     * @return boolean
     */
    public function doRegister($email, $statusID, $password)
    {
        try
        {
            $this->insertUser($email, $statusID, $password);

            $this->insertAddress($userID, $city, $street, $zip, $country);

             if(isset($this->error))
            {
                return false;
            }
                
            return true;
        }

        catch (Exception $e)
        {
            return false;
        }
    }

    /**
     * If there are any errors, output them
     * @return string The error
     */
    public function outputErrors()
    {
        if (isset($this->errors))
        {
            foreach ($this->errors as $error)
            {
                echo $error;
            }
        }
    }

    /**
     * Checks on an array if the city received is valid
     * @return boolean
     */
    private function isCityValid()
    {

        if (empty($this->city))
        {
            $this->errors[] = "Please enter a city!";
            return false;
        }

        $this->city = filter_var($this->city, FILTER_SANITIZE_STRING);

        if (!in_array(ucwords($this->city), $this->validCities))
        {
            return false;
        }

        return true;
    }

    /**
     * Checks on an array if the street received is valid
     * @return boolean
     */
    private function isStreetValid()
    {

        if (empty($this->street))
        {
            $this->errors[] = "Please enter a street!";
            return false;
        }

        $this->street = filter_var($this->street, FILTER_SANITIZE_STRING);

        if (!in_array(ucwords($this->street), $this->validStreets))
        {
            return false;
        }

        return true;
    }

    /**
     * Checks on array if the country received is valid
     * @return boolean
     */
    private function isCountryValid()
    {

        if (empty($this->country))
        {
            $this->errors[] =  "Please enter a country!";
            return false;
        }

        $this->country = filter_var($this->country, FILTER_SANITIZE_STRING);

        if (!in_array(ucwords($this->street), $this->validStreets))
        {
            return false;
        }

        return true;
    }

    /**
     * Checks on an array if the zip received is valid
     * @return boolean
     */
    private function isZipValid()
    {
        if (empty($this->zip))
        {
            $this->errors[] =  "Please enter a zip code!";
            return false;
        }

        $this->zip = filter_var($this->zip, FILTER_SANITIZE_STRING);

        if (!in_array(ucwords($this->zip), $this->validZips))
        {
            return false;
        }

        return true;
    }

    /**
     * Checks if the received email is valid
     * @return boolean
     */
    private function isEmailValid()
    {
        if (empty($this->email))
        {
            $this->errors[] = "Please enter an email!";
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL))
        {
            $this->errors[] = "Please enter a valid email!";
            return false;
        }

        $this->email = filter_var($this->email, FILTER_SANITIZE_EMAIL);

        return true;
    }

    /**
     * Checks on a database query if the received password is valid
     * @return boolean [description]
     */
    private function isPasswordValid()
    {
        if (empty($this->password))
        {
            $this->errors[] = "Please enter a password!";
        }

        $bindArray = array(
            'bindTypes' => 's',
            'bindVariables' => array(&$this->email)
            );

        $bind = $this->bindQuery(
            "SELECT `password` FROM `user` WHERE `email` = ?",
            $bindArray
            );

        $result = $this->getArray($bind);

        if (!password_verify($this->password, $result[0]))
        {
            return false;
        }

        return true;
    }

    /**
     * Gets the statusID based on email
     * @return [type] [description]
     */
    private function getStatusID()
    {
        try
        {
            //Specifies to which items to apply the filter
            $bindArray = array(
                'bindTypes' => 's',
                'bindVariables' => array(&$this->email)
                );

            $bind = $this->bindQuery(
                "SELECT `statusID` FROM `user` WHERE `email` = ?",
                $bindArray
                );

            return $this->getArray($bind);
        }

        catch (Exception $e)
        {
            echo $this->database->error;
            return false;
        }
    }
}


?>