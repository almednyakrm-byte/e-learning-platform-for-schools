<?php

namespace App\Tests\Controller;

use App\Controller\ProfesseursController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestProfesseurs extends TestCase
{
    private $professeursController;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->professeursController = new ProfesseursController($this->pdo);
    }

    public function testGetProfesseurs()
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM professeurs')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->professeursController->getProfesseurs();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPostProfesseurs()
    {
        $request = new Request([], [], ['json' => ['nom' => 'John', 'prenom' => 'Doe']]);
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO professeurs (nom, prenom) VALUES (:nom, :prenom)')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdo->expects($this->once())
            ->method('commit');

        $response = $this->professeursController->postProfesseurs($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testPutProfesseurs()
    {
        $request = new Request([], [], ['json' => ['id' => 1, 'nom' => 'John', 'prenom' => 'Doe']]);
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE professeurs SET nom = :nom, prenom = :prenom WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdo->expects($this->once())
            ->method('commit');

        $response = $this->professeursController->putProfesseurs($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteProfesseurs()
    {
        $request = new Request([], [], ['id' => 1]);
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM professeurs WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdo->expects($this->once())
            ->method('commit');

        $response = $this->professeursController->deleteProfesseurs($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}


Note: This code assumes that the `ProfesseursController` class has methods `getProfesseurs`, `postProfesseurs`, `putProfesseurs`, and `deleteProfesseurs` which handle the respective CRUD operations. Also, it assumes that the database table name is `professeurs`. You may need to adjust the code according to your actual implementation.