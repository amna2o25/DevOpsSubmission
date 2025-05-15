<?php
// test/BasketTest.php

use PHPUnit\Framework\TestCase;
use MyApp\Basket;

class BasketTest extends TestCase
{
    private Basket $basket;

    protected function setUp(): void
    {
        // inject a fake product catalog or use an in-memory store
        $this->basket = new Basket(/* …dependencies… */);
    }

    public function testAddItemIncreasesCount(): void
    {
        $this->basket->addItem('widget', 2);
        $this->assertEquals(1, $this->basket->getItemCount());
    }

    public function testRemoveItemDecreasesCount(): void
    {
        $this->basket->addItem('widget', 2);
        $this->basket->removeItem('widget');
        $this->assertEquals(0, $this->basket->getItemCount());
    }


    
