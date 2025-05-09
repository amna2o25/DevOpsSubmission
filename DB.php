<?php
class DB {
    private $host = "mysql:host=213.171.200.34;dbname=aidris;charset=utf8";
    private $user = "aidris";
    private $pass = "Password20*";

    public function connect(): PDO {
        $options = [
            PDO::ATTR_PERSISTENT => true, // Optional based on your application's need
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];
        $conn = new PDO($this->host, $this->user, $this->pass, $options);
        return $conn;
    }

    public function getAdminByEmail($email) {
        $conn = $this->connect();
        $stmt = $conn->prepare("SELECT admin_id, admin_email, admin_password FROM admins WHERE admin_email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProductDetails($productId) {
        $conn = $this->connect();
        $stmt = $conn->prepare("SELECT * FROM tbl_products WHERE ProductID = :id");
        $stmt->execute(['id' => $productId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}








