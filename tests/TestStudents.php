<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\StudentsController;
use App\Repository\StudentsRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class TestStudents extends TestCase
{
    private $studentsController;
    private $studentsRepository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->studentsRepository = $this->createMock(StudentsRepository::class);
        $this->studentsController = new StudentsController($this->studentsRepository);
    }

    public function testGetStudents()
    {
        $expectedResponse = ['students' => []];
        $this->studentsRepository->expects($this->once())
            ->method('getAllStudents')
            ->willReturn($expectedResponse);
        $response = $this->studentsController->getStudents();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreateStudent()
    {
        $studentData = ['name' => 'John Doe', 'email' => 'john@example.com'];
        $expectedResponse = ['message' => 'Student created successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO students (name, email) VALUES (:name, :email)');
        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['name' => $studentData['name'], 'email' => $studentData['email']]);
        $this->studentsRepository->expects($this->once())
            ->method('createStudent')
            ->with($studentData);
        $response = $this->studentsController->createStudent($studentData);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdateStudent()
    {
        $studentId = 1;
        $studentData = ['name' => 'John Doe', 'email' => 'john@example.com'];
        $expectedResponse = ['message' => 'Student updated successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE students SET name = :name, email = :email WHERE id = :id');
        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['name' => $studentData['name'], 'email' => $studentData['email'], 'id' => $studentId]);
        $this->studentsRepository->expects($this->once())
            ->method('updateStudent')
            ->with($studentId, $studentData);
        $response = $this->studentsController->updateStudent($studentId, $studentData);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDeleteStudent()
    {
        $studentId = 1;
        $expectedResponse = ['message' => 'Student deleted successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM students WHERE id = :id');
        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['id' => $studentId]);
        $this->studentsRepository->expects($this->once())
            ->method('deleteStudent')
            ->with($studentId);
        $response = $this->studentsController->deleteStudent($studentId);
        $this->assertEquals($expectedResponse, $response);
    }
}



// App\Controller\StudentsController.php

namespace App\Controller;

use App\Repository\StudentsRepository;
use PDO;

class StudentsController
{
    private $studentsRepository;

    public function __construct(StudentsRepository $studentsRepository)
    {
        $this->studentsRepository = $studentsRepository;
    }

    public function getStudents()
    {
        return $this->studentsRepository->getAllStudents();
    }

    public function createStudent(array $studentData)
    {
        $this->pdo->prepare('INSERT INTO students (name, email) VALUES (:name, :email)')->execute($studentData);
        return ['message' => 'Student created successfully'];
    }

    public function updateStudent(int $studentId, array $studentData)
    {
        $this->pdo->prepare('UPDATE students SET name = :name, email = :email WHERE id = :id')->execute($studentData + ['id' => $studentId]);
        return ['message' => 'Student updated successfully'];
    }

    public function deleteStudent(int $studentId)
    {
        $this->pdo->prepare('DELETE FROM students WHERE id = :id')->execute(['id' => $studentId]);
        return ['message' => 'Student deleted successfully'];
    }
}



// App\Repository\StudentsRepository.php

namespace App\Repository;

class StudentsRepository
{
    public function getAllStudents()
    {
        // Return all students from database
    }

    public function createStudent(array $studentData)
    {
        // Create a new student in the database
    }

    public function updateStudent(int $studentId, array $studentData)
    {
        // Update an existing student in the database
    }

    public function deleteStudent(int $studentId)
    {
        // Delete a student from the database
    }
}