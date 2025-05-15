<?php
// test/RegistrationTest.php

use PHPUnit\Framework\TestCase;
use MyApp\UserRegistrar;
use MyApp\ValidationException;

class RegistrationTest extends TestCase
{
    private UserRegistrar $reg;

    protected function setUp(): void
    {
        // inject a fake user repository or use an in-memory DB
        $this->reg = new UserRegistrar(/* … */);
    }

    public function testValidRegistrationReturnsUserId(): void
    {
        $userId = $this->reg->register([
            'email'    => 'foo@example.com',
            'password' => 'Password123!',
        ]);
        $this->assertIsInt($userId);
    }

    public function testRegistrationThrowsOnInvalidEmail(): void
    {
        $this->expectException(ValidationException::class);
        $this->reg->register([
            'email'    => 'not-an-email',
            'password' => 'Password123!',
        ]);
    }
}
