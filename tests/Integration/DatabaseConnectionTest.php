<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../db.php';

class DatabaseConnectionTest extends TestCase
{
    public function test_database_connection_exists()
    {
        global $pdo;

        $this->assertInstanceOf(PDO::class, $pdo);
    }

    public function test_users_table_exists()
    {
        global $pdo;

        $stmt = $pdo->query("
            SHOW TABLES LIKE 'users'
        ");

        $result = $stmt->fetch();

        $this->assertNotFalse($result);
    }
}