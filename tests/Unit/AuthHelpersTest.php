<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../auth_helpers.php';

class AuthHelpersTest extends TestCase
{
    public function test_password_hashing_works()
    {
        $password = 'Test123!';

        $hash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $this->assertTrue(
            password_verify($password, $hash)
        );
    }

    public function test_random_token_is_generated()
    {
        $token = bin2hex(random_bytes(32));

        $this->assertEquals(64, strlen($token));
    }
}