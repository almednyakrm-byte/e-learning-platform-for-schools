<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class TestCourses extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
    }

    public function testGetCourses()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([':id' => 1]));

        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Course 1'],
                ['id' => 2, 'name' => 'Course 2'],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('SELECT * FROM courses WHERE id = :id'))
            ->willReturn($stmt);

        $courses = [];
        $stmt = $this->pdo->prepare('SELECT * FROM courses WHERE id = :id');
        $stmt->execute([':id' => 1]);
        $courses = $stmt->fetchAll();

        $this->assertCount(2, $courses);
    }

    public function testPostCourse()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([':name' => 'New Course']));

        $stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('INSERT INTO courses (name) VALUES (:name)'))
            ->willReturn($stmt);

        $stmt = $this->pdo->prepare('INSERT INTO courses (name) VALUES (:name)');
        $result = $stmt->execute([':name' => 'New Course']);

        $this->assertTrue($result);
    }

    public function testPutCourse()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([':id' => 1, ':name' => 'Updated Course']));

        $stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('UPDATE courses SET name = :name WHERE id = :id'))
            ->willReturn($stmt);

        $stmt = $this->pdo->prepare('UPDATE courses SET name = :name WHERE id = :id');
        $result = $stmt->execute([':id' => 1, ':name' => 'Updated Course']);

        $this->assertTrue($result);
    }

    public function testDeleteCourse()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([':id' => 1]));

        $stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('DELETE FROM courses WHERE id = :id'))
            ->willReturn($stmt);

        $stmt = $this->pdo->prepare('DELETE FROM courses WHERE id = :id');
        $result = $stmt->execute([':id' => 1]);

        $this->assertTrue($result);
    }
}