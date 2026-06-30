<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class TestStudents extends TestCase
{
    private MockObject $pdo;
    private MockObject $statement;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->statement = $this->createMock(PDOStatement::class);
    }

    public function testGetStudents(): void
    {
        $this->statement->expects($this->once())
            ->method('execute')
            ->with([]);

        $this->statement->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'John Doe'],
                ['id' => 2, 'name' => 'Jane Doe'],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM students')
            ->willReturn($this->statement);

        $students = $this->getStudents($this->pdo);
        $this->assertCount(2, $students);
    }

    public function testCreateStudent(): void
    {
        $this->statement->expects($this->once())
            ->method('execute')
            ->with(['name' => 'John Doe']);

        $this->statement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO students (name) VALUES (:name)')
            ->willReturn($this->statement);

        $result = $this->createStudent($this->pdo, 'John Doe');
        $this->assertTrue($result);
    }

    public function testUpdateStudent(): void
    {
        $this->statement->expects($this->once())
            ->method('execute')
            ->with(['id' => 1, 'name' => 'Jane Doe']);

        $this->statement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE students SET name = :name WHERE id = :id')
            ->willReturn($this->statement);

        $result = $this->updateStudent($this->pdo, 1, 'Jane Doe');
        $this->assertTrue($result);
    }

    public function testDeleteStudent(): void
    {
        $this->statement->expects($this->once())
            ->method('execute')
            ->with(['id' => 1]);

        $this->statement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM students WHERE id = :id')
            ->willReturn($this->statement);

        $result = $this->deleteStudent($this->pdo, 1);
        $this->assertTrue($result);
    }

    private function getStudents(PDO $pdo): array
    {
        $statement = $pdo->prepare('SELECT * FROM students');
        $statement->execute();
        return $statement->fetchAll();
    }

    private function createStudent(PDO $pdo, string $name): bool
    {
        $statement = $pdo->prepare('INSERT INTO students (name) VALUES (:name)');
        $statement->execute(['name' => $name]);
        return $statement->rowCount() > 0;
    }

    private function updateStudent(PDO $pdo, int $id, string $name): bool
    {
        $statement = $pdo->prepare('UPDATE students SET name = :name WHERE id = :id');
        $statement->execute(['id' => $id, 'name' => $name]);
        return $statement->rowCount() > 0;
    }

    private function deleteStudent(PDO $pdo, int $id): bool
    {
        $statement = $pdo->prepare('DELETE FROM students WHERE id = :id');
        $statement->execute(['id' => $id]);
        return $statement->rowCount() > 0;
    }
}