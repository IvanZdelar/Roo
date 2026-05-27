<?php

use PHPUnit\Framework\TestCase;

class DatabaseConnectionTest extends TestCase
{
    private PDO $pdo;

    protected function setUp(): void
    {
        $this->pdo = require __DIR__ . '/../../db.php';
    }

    public function test_database_connection_exists(): void
    {
        $this->assertInstanceOf(PDO::class, $this->pdo);
    }

    public function test_users_table_exists(): void
    {
        $stmt = $this->pdo->query(
            "SHOW TABLES LIKE 'users'"
        );

        $this->assertNotFalse($stmt->fetch());
    }
}