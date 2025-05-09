<?php

class Product {
    private int $ProductID;
    private string $ProductName;
    private string $Image;
    private float $Price;
    private string $Description;

    public function __construct(int $ProductID, string $ProductName, string $Image, float $Price,) {
        $this->ProductID = $ProductID;
        $this->ProductName = $ProductName;
        $this->Image = $Image;
        $this->Price = $Price;
       
    }

    public function id(): int {
        return $this->ProductID;
    }

    public function name(): string {
        return $this->ProductName;
    }

    public function image(): string {
        return $this->Image;
    }

    public function price(): float {
        return $this->Price;
    }

  
}




