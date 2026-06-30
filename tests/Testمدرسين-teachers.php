<?php

namespace App\Tests\Controller;

use App\Controller\TeachersController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestمدرسينTeachers extends TestCase
{
    private $teachersController;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->teachersController = new TeachersController($this->pdoMock);
    }

    public function testGetTeachers()
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM teachers')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->teachersController->getTeachers();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testPostTeacher()
    {
        $teacher = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO teachers (name, email) VALUES (:name, :email)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->teachersController->postTeacher($teacher);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testPutTeacher()
    {
        $teacher = [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE teachers SET name = :name, email = :email WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->teachersController->putTeacher($teacher);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteTeacher()
    {
        $id = 1;

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM teachers WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->teachersController->deleteTeacher($id);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// TeachersController.php

namespace App\Controller;

use PDO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TeachersController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getTeachers()
    {
        $stmt = $this->pdo->query('SELECT * FROM teachers');
        $teachers = $stmt->fetchAll();
        return new Response(json_encode($teachers));
    }

    public function postTeacher(array $teacher)
    {
        $stmt = $this->pdo->prepare('INSERT INTO teachers (name, email) VALUES (:name, :email)');
        $stmt->execute($teacher);
        return new Response('', Response::HTTP_CREATED);
    }

    public function putTeacher(array $teacher)
    {
        $stmt = $this->pdo->prepare('UPDATE teachers SET name = :name, email = :email WHERE id = :id');
        $stmt->execute($teacher);
        return new Response('', Response::HTTP_OK);
    }

    public function deleteTeacher(int $id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM teachers WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}