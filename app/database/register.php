<?php

class Register extends Database{

    public $city;
    public $street;
    public $zip;
    public $country;
    private $userID;    
    
    public $email;
    public $password;

    private $validCities = array();
    private $validStreets = array();
    private $validCountries = array();
    private $validZips = array();

    //Holds error like if city or street is missing etc.
    private $errors = array();

    public function __construct()
    {
        parent::__construct();
        $this->validCities = array("Baia Mare", "Oradea", "Bucuresti");
        $this->validStreets = array("Nothing", "Bd.Bucuresti 17", "Decebal");
        $this->validCountries = array("Romania", "Germany", "America");
        $this->validZips = array("123456789", "987654321");
    }

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

    private function isPasswordValid()
    {
        if (empty($this->password))
        {
            $this->errors[] = "Please enter a password!";
        }

        return true;

        //what next
    }

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