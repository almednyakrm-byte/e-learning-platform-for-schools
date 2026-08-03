<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\CoursesController;
use App\Repository\CoursesRepository;
use App\Entity\Courses;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\User;
use PHPUnit\Framework\MockObject\MockObject;

class TestCourses extends TestCase
{
    private $coursesController;
    private $coursesRepository;
    private $entityManager;
    private $router;
    private $requestStack;
    private $session;
    private $security;
    private $user;

    protected function setUp(): void
    {
        $this->coursesRepository = $this->createMock(CoursesRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->session = $this->createMock(SessionInterface::class);
        $this->security = $this->createMock(Security::class);
        $this->user = $this->createMock(UserInterface::class);

        $this->coursesController = new CoursesController(
            $this->coursesRepository,
            $this->entityManager,
            $this->router,
            $this->requestStack,
            $this->session,
            $this->security
        );
    }

    public function testGetCourses(): void
    {
        $this->coursesRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                new Courses('Course 1'),
                new Courses('Course 2'),
            ]);

        $response = $this->coursesController->getCourses();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testPostCourses(): void
    {
        $course = new Courses('New Course');
        $this->coursesRepository->expects($this->once())
            ->method('save')
            ->with($course);

        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('request')
            ->willReturn(['name' => 'New Course']);

        $response = $this->coursesController->postCourses($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testPutCourses(): void
    {
        $course = new Courses('Updated Course');
        $this->coursesRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($course);

        $this->coursesRepository->expects($this->once())
            ->method('save')
            ->with($course);

        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('request')
            ->willReturn(['name' => 'Updated Course']);

        $response = $this->coursesController->putCourses(1, $request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteCourses(): void
    {
        $this->coursesRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new Courses('Course 1'));

        $this->coursesRepository->expects($this->once())
            ->method('remove')
            ->with(new Courses('Course 1'));

        $response = $this->coursesController->deleteCourses(1);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test class covers the basic CRUD operations for the 'courses' module. It uses PHPUnit's mocking features to isolate the dependencies of the CoursesController class and test its behavior in isolation. The test methods cover the following scenarios:

*   `testGetCourses`: Tests the `getCourses` method, which should return a list of all courses.
*   `testPostCourses`: Tests the `postCourses` method, which should create a new course and return a JSON response with the created course.
*   `testPutCourses`: Tests the `putCourses` method, which should update an existing course and return a JSON response with the updated course.
*   `testDeleteCourses`: Tests the `deleteCourses` method, which should delete a course and return a JSON response with a 204 status code.

Note that this is a basic example and you may need to add more tests to cover additional scenarios or edge cases.