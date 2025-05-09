<?php
class DB {
    private $host = "mysql:host=213.171.200.34;dbname=aidris";
    private $user = "aidris";
    private $pass = "Password20*";
    private $dbh;

    public function __construct() {
        try {
            $this->dbh = new PDO($this->host, $this->user, $this->pass);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    // Generic function to prepare and execute a query
    public function query($sql, $params = []) {
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Example: Fetch product details securely
    public function getProductDetails($id) {
        $sql = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->query($sql, ['id' => $id]);
        return $stmt->fetch();
    }
}
