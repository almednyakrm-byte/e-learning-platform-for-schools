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
    }

    public function testLoginSuccess(): void
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->with('SELECT * FROM users WHERE username = ?', [$username])
            ->willReturn([
                ['id' => 1, 'username' => $username, 'password' => $password],
            ]);

        $this->authRepository->expects($this->once())
            ->method('getUser')
            ->with($username)
            ->willReturn(new User(1, $username, $password));

        $this->authService->login($username, $password);

        $this->assertTrue($this->authService->isLoggedIn());
    }

    public function testLoginFailure(): void
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->with('SELECT * FROM users WHERE username = ?', [$username])
            ->willReturn([]);

        $this->authRepository->expects($this->never())
            ->method('getUser');

        $this->authService->login($username, $password);

        $this->assertFalse($this->authService->isLoggedIn());
    }

    public function testRegisterSuccess(): void
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->with('INSERT INTO users (username, password) VALUES (?, ?)', [$username, $password])
            ->willReturn(true);

        $this->authRepository->expects($this->once())
            ->method('getUser')
            ->with($username)
            ->willReturn(new User(1, $username, $password));

        $this->authService->register($username, $password);

        $this->assertTrue($this->authService->isLoggedIn());
    }

    public function testRegisterFailure(): void
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->with('INSERT INTO users (username, password) VALUES (?, ?)', [$username, $password])
            ->willReturn(false);

        $this->authRepository->expects($this->never())
            ->method('getUser');

        $this->authService->register($username, $password);

        $this->assertFalse($this->authService->isLoggedIn());
    }
}


This test file covers the following scenarios:

- `testLoginSuccess`: Tests that the `login` method successfully logs in a user with the correct credentials.
- `testLoginFailure`: Tests that the `login` method fails to log in a user with incorrect credentials.
- `testRegisterSuccess`: Tests that the `register` method successfully registers a new user.
- `testRegisterFailure`: Tests that the `register` method fails to register a new user.

Each test method uses the `createMock` method to create mock objects for the `Connection` and `AuthRepository` classes. The `expects` method is used to specify the expected behavior of the mock objects. The `willReturn` method is used to specify the return value of the mock objects.

The `assertEquals` and `assertTrue` assertions are used to verify that the expected behavior occurs.