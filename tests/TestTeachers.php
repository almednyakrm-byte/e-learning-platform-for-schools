<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use PDO;
use PDOStatement;

class TestTeachers extends TestCase
{
    private $pdo;
    private $teacherController;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->teacherController = new TeacherController($this->pdo);
    }

    public function testGetAllTeachers()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([]);

        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'John Doe'],
                ['id' => 2, 'name' => 'Jane Doe'],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM teachers')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $result = $this->teacherController->getAllTeachers($request, $response);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testGetTeacherById()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'John Doe']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM teachers WHERE id = ?')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->teacherController->getTeacherById($request, $response);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testCreateTeacher()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['John Doe']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO teachers (name) VALUES (?)')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'John Doe']);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->teacherController->createTeacher($request, $response);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(201, $result->getStatusCode());
    }

    public function testUpdateTeacher()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['John Doe', 1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE teachers SET name = ? WHERE id = ?')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'John Doe']);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->teacherController->updateTeacher($request, $response);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testDeleteTeacher()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM teachers WHERE id = ?')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->teacherController->deleteTeacher($request, $response);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(204, $result->getStatusCode());
    }
}

class TeacherController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllTeachers(ServerRequestInterface $request, ResponseInterface $response)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM teachers');
        $stmt->execute();
        $teachers = $stmt->fetchAll();

        $response->getBody()->write(json_encode($teachers));
        return $response->withStatus(200);
    }

    public function getTeacherById(ServerRequestInterface $request, ResponseInterface $response)
    {
        $id = $request->getAttribute('id');
        $stmt = $this->pdo->prepare('SELECT * FROM teachers WHERE id = ?');
        $stmt->execute([$id]);
        $teacher = $stmt->fetch();

        if (!$teacher) {
            return $response->withStatus(404);
        }

        $response->getBody()->write(json_encode($teacher));
        return $response->withStatus(200);
    }

    public function createTeacher(ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = $request->getParsedBody();
        $stmt = $this->pdo->prepare('INSERT INTO teachers (name) VALUES (?)');
        $stmt->execute([$data['name']]);

        return $response->withStatus(201);
    }

    public function updateTeacher(ServerRequestInterface $request, ResponseInterface $response)
    {
        $id = $request->getAttribute('id');
        $data = $request->getParsedBody();
        $stmt = $this->pdo->prepare('UPDATE teachers SET name = ? WHERE id = ?');
        $stmt->execute([$data['name'], $id]);

        return $response->withStatus(200);
    }

    public function deleteTeacher(ServerRequestInterface $request, ResponseInterface $response)
    {
        $id = $request->getAttribute('id');
        $stmt = $this->pdo->prepare('DELETE FROM teachers WHERE id = ?');
        $stmt->execute([$id]);

        return $response->withStatus(204);
    }
}