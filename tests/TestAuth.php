<?php

namespace App\Tests\Unit\Auth;

use App\Auth\AuthService;
use App\Auth\AuthRepository;
use App\Auth\User;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class TestAuth extends TestCase
{
    private $authService;
    private $authRepository;
    private $connection;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->authRepository = $this->createMock(AuthRepository::class);
        $this->authService = new AuthService($this->authRepository);

        $this->connection->method('connect')->willReturn(true);
        $this->connection->method('getWrappedConnection')->willReturn($this->connection);
    }

    public function testLoginSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->method('getUserByUsername')->willReturn(new User($username, $password));

        $result = $this->authService->login($username, $password);

        $this->assertTrue($result);
    }

    public function testLoginFailure()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->method('getUserByUsername')->willReturn(null);

        $result = $this->authService->login($username, $password);

        $this->assertFalse($result);
    }

    public function testRegisterSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->method('getUserByUsername')->willReturn(null);
        $this->authRepository->method('createUser')->willReturn(new User($username, $password));

        $result = $this->authService->register($username, $password);

        $this->assertTrue($result);
    }

    public function testRegisterFailure()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->method('getUserByUsername')->willReturn(new User($username, $password));

        $result = $this->authService->register($username, $password);

        $this->assertFalse($result);
    }
}


This test file includes four test methods: `testLoginSuccess`, `testLoginFailure`, `testRegisterSuccess`, and `testRegisterFailure`. Each test method tests a different scenario for the `login` and `register` methods of the `AuthService` class.

The `setUp` method is used to create a mock database connection and an instance of the `AuthService` class. The `createMock` method is used to create mock objects for the `AuthRepository` class and the database connection.

The `testLoginSuccess` and `testLoginFailure` methods test the `login` method of the `AuthService` class. The `testRegisterSuccess` and `testRegisterFailure` methods test the `register` method of the `AuthService` class.

Each test method uses the `assertEquals` and `assertTrue` assertions to verify that the expected result is returned by the `login` and `register` methods.