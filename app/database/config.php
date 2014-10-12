<?php 
	ini_set('display_errors',1);
	error_reporting(E_ALL);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    /**
     * This class holds the database connection info
     */
    class Config
    {
        /**
         * Holds the database host name
         * @var string
         */
        public $databaseHost = 'localhost';

        /**
         * Holds the database name
         * @var string
         */
        public $databaseName = 'loan-crm';

        /**
         * Holds the database users name
         * @var string
         */
        public $databaseUser = 'root';

        /**
         * Holds the database password
         * In Windows, there's no password,
         * In Ubuntu there is
         * Which one to use?
         * @var string
         */
        public $databasePassword = 'sampwoS1';
    }
?>