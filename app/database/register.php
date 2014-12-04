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
    public $errors = array(
        'hasError' => false,
        'errors' => array()
        );

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
    public function isAddressValid()
    {
        $this->isCityValid();
        $this->isStreetValid();
        $this->isZipValid();
        $this->isCountryValid();

        if (!empty($this->errors['errors']))
        {
            return false;
        }
        
        return true;
    }

    public function isUserProfileValid()
    {
        //check the userprofile(email, password)
        $this->isEmailValid();
        
         //If the received email address already exists and emptyEmail is not empty
        //set errors hasError to true and insert new item into emailExists
        if ($this->emailExists($this->email) && !isset($this->errors['errors']['emptyEmail']))
        {
            $this->errors['hasError'] = true;
            $this->errors['errors']['emailExists'] = 'Email address already exists';
        }
        
        $this->isPasswordValid();

        if (!empty($this->errors['errors']))
        {
            return false;
        }

        return true;
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
                //Returns false if password is empty, email already exists or empty
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

            //Returns true if the user was successfully inserted
            return $insert;
        }

        catch (Exception $e)
        {
            return false;
        }
    }

     public function insertAddress($userID, $city, $street, $zip, $country)
        {
            try
            {
                if (!$this->isAddressValid())
                {
                    //Returns false if there was an error ( empty city, invalid zip etc.)
                    return false;
                }

                //Gets the userID by email
                $userID = $this->getUserID()[0];

                //Specifies which items to apply the filtering
                $data = array(
                    'userID' => $userID,
                    'city' => $city,
                    'street' => $street,
                    'zip' => $zip,
                    'country' => $country
                    );

                //Specifies rules to use for filtering
                $args = array(
                    'userID' => FILTER_VALIDATE_INT,
                    'city' => FILTER_SANITIZE_STRING,
                    'street' => FILTER_SANITIZE_STRING,
                    'zip' => FILTER_VALIDATE_INT,
                    'country' => FILTER_SANITIZE_STRING
                    );

                filter_var_array($data, $args);

                $bindArray = array(
                    'bindTypes' => 'issis',
                    'bindVariables' => array(&$userID, &$city, &$street, &$zip, &$country)
                    ); 
                
                $insert = $this->bindQuery(
                    "INSERT INTO `user_address` (`userID`, `city`, `street`, `zip`, `country`)
                    VALUES (?, ?, ?, ?, ?)",
                    $bindArray
                    );

                //Returns true if the user address was successfully inserted
                return $insert;
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
            $user = $this->insertUser($email, $statusID, $password);

            $address = $this->insertAddress($this->userID, $this->city, $this->street, $this->zip, $this->country);

            if (!$user || !$address)
            {
                return false;
            }

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
            foreach ($this->errors['errors'] as $key => $value)
            {
                echo $value;
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
            $this->errors['hasError'] = true;
            $this->errors['errors']['emptyCity'] = 'Please enter a city';
            return false;
        }

        //Sanitize the city string
        $this->city = filter_var($this->city, FILTER_SANITIZE_STRING);

        //If the received city doesn't match the validCities and emptyCity is not empty
        //set errors hasError to true and insert new item into invalidCity
        if (!in_array(ucwords($this->city), $this->validCities) && !isset($this->errors['errors']['emptyCity']))
        {
            $this->errors['hasError'] = true;
            $error = $this->errors['errors']['invalidCity'] = 'Please enter a valid city';
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

        //If the street is empty, set erros hasError to true and insert new item into emptyStreet
        if (empty($this->street))
        {
            $this->errors['hasError'] = true;
            $this->errors['errors']['emptyStreet'] = 'Please enter a street';
            return false;
        }

        //Sanitize the street string
        $this->street = filter_var($this->street, FILTER_SANITIZE_STRING);

        //If the received street doesn't match the validStreets and emptyStreet is not empty
        //set errors hasError to true and insert new item into invalidStreet
        if (!in_array(ucwords($this->street), $this->validStreets) && !isset($this->errors['errors']['emptyStreet']))
        {
           $this->errors['hasError'] = true;
           $this->errors['errors']['invalidStreet'] = 'Please enter a valid street';
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
        //If the country is empty, set errors hasError to true and set new item into emptyCountry
        if (empty($this->country))
        {
            $this->errors['hasError'] = true;
            $this->errors['errors']['emptyCountry'] = 'Please enter a country';
            return false;
        }

        //Sanitize the country string
        $this->country = filter_var($this->country, FILTER_SANITIZE_STRING);

        //If the received country doesn't match the validCountries and emptyCountry is not empty
        //set errors hasError to true and insert new item into invalidCountry
        if (!in_array(ucwords($this->country), $this->validCountries) && !isset($this->errors['errors']['emptyCountry']))
        {
            $this->errors['hasError'] = true;
            $this->errors['errors']['invalidCountry'] = 'Please enter a valid country';
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
        //If the zip code is empty, set errors hasError to true and set new item into emptyZip
        if (empty($this->zip))
        {
            $this->errors['hasError'] = true;
            $this->errors['errors']['emptyZip'] = 'Please enter a zip code';
            return false;
           
        }

        //Sanitize the zip code int
        $this->zip = filter_var($this->zip, FILTER_SANITIZE_NUMBER_INT);

        //If the received zip code doesn't match the validZips and emptyZip is not empty
        //set errors hasError to true and insert new item into invalidZip
        if (!in_array(ucwords($this->zip), $this->validZips) && !isset($this->errors['errors']['emptyZip']))
        {
            $this->errors['hasError'] = true;
            $this->errors['errors']['invalidZip'] = 'Please enter a valid zip code';
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
            $this->errors['hasError'] = true;
            $this->errors['errors']['emptyEmail'] = "Please enter an email address";
            return false;
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL) && !isset($this->errors['errors']['emptyEmail']))
        {
            $this->errors['hasError'] = true;
            $this->errors['errors']['invalidEmail'] = "Please enter a valid email address";
            return false;
        }

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
            $this->errors['hasError'] = true;
            $this->errors['errors']['emptyPassword'] = "Please enter a password";
            return false;
        }

        // $bindArray = array(
        //     'bindTypes' => 's',
        //     'bindVariables' => array(&$this->email)
        //     );

        // $bind = $this->bindQuery(
        //     "SELECT `password` FROM `user` WHERE `email` = ?",
        //     $bindArray
        //     );

        // $result = $this->getArray($bind);

        // if (!password_verify($this->password, $result[0]))
        // {
        //     return false;
        // }
        if (!($this->password == $_POST['Register']['password2']) && !isset($this->errors['errors']['emptyPassword']))
        {
            $this->errors['hasError'] = true;
            $this->errors['errors']['passwordMismatch'] = "Password's don't match";
            return false;
        }

        return true;
    }

    /**
     * Gets the statusID based on email
     * @return int
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

    /**
     * Gets the userID based on email
     * @return int
     */
    private function getUserID()
    {
        try
        {
            //Specifies to which items to apply the filter
            $bindArray = array(
                'bindTypes' => 's',
                'bindVariables' => array(&$this->email)
                );

            $bind = $this->bindQuery(
                "SELECT `ID` FROM `user` WHERE `email` = ?",
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