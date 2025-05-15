<?php
// test/BasketTest.php

use PHPUnit\Framework\TestCase;
use MyApp\BasketManager; // replace with your actual namespace

class BasketTest extends TestCase
{
    private BasketManager $basket;

    protected function setUp(): void
    {
        // e.g. clear session or inject a fresh basket
        $this->basket = new BasketManager();
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

    public function testGetTotalCalculatesCorrectly(): void
    {
        // assume price of widget = 10.50
        $this->basket->addItem('widget', 2);
        $this->assertEquals(21.00, $this->basket->getTotal());
    }
}
