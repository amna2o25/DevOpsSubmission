<?php
class DB {
    private $host = "213.171.200.34";
    private $dbname = "aidris";
    private $user = "aidris";
    private $pass = "Password20*";
    private $charset = 'utf8mb4';

    public function connect(): PDO {
        // Correctly format the DSN string
        $dsn = "mysql:host=$this->host;dbname=$this->dbname;charset=$this->charset";

        // Define options to enhance security and performance
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // turns on error mode to exceptions
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // fetches results as associative arrays
            PDO::ATTR_EMULATE_PREPARES => false, // use true prepared statements
        ];

        try {
            // Create a new PDO instance with the provided options
            $pdo = new PDO($dsn, $this->user, $this->pass, $options);
            return $pdo;
        } catch (PDOException $e) {
            // Handle any connection errors gracefully
            die("Connection failed: " . $e->getMessage());
        }
    }
}
?>
