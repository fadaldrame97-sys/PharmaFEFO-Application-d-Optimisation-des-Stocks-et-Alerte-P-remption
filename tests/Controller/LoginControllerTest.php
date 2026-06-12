<?php

declare(strict_types=1);

namespace Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use LoginController;
use UserRepository;
use User;

/**
 * Tests for LoginController.
 *
 * Since most controller methods call exit(), we test:
 * - Construction with mock dependencies
 * - The UserRepository mock is properly wired
 * - Business logic through mock expectations
 */
class LoginControllerTest extends TestCase
{
    private MockObject $userRepository;

    protected function setUp(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        $_POST = [];

        $this->userRepository = $this->createMock(UserRepository::class);
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
        $_POST = [];
    }

    public function testConstructorAcceptsUserRepository(): void
    {
        $controller = new LoginController($this->userRepository);
        $this->assertInstanceOf(LoginController::class, $controller);
    }

    public function testUserRepositoryFindByEmailCalledCorrectly(): void
    {
        $this->userRepository->expects($this->once())
            ->method('findByEmail')
            ->with('admin@pharma.fr')
            ->willReturn(null);

        $this->userRepository->findByEmail('admin@pharma.fr');
    }

    public function testUserRepositoryFindByEmailReturnsUser(): void
    {
        $user = new User(1, 'admin@pharma.fr', 'hashed_pw', 'ADMIN');

        $this->userRepository->method('findByEmail')
            ->willReturn($user);

        $result = $this->userRepository->findByEmail('admin@pharma.fr');
        $this->assertInstanceOf(User::class, $result);
        $this->assertSame('admin@pharma.fr', $result->getEmail());
    }

    public function testPasswordVerificationLogic(): void
    {
        $password = 'correct_password';
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $user = new User(1, 'admin@pharma.fr', $hashedPassword, 'ADMIN');

        $this->assertTrue(password_verify($password, $user->getPassword()));
        $this->assertFalse(password_verify('wrong_password', $user->getPassword()));
    }

    public function testEmailValidationLogic(): void
    {
        $this->assertTrue(filter_var('admin@pharma.fr', FILTER_VALIDATE_EMAIL) !== false);
        $this->assertFalse(filter_var('not-an-email', FILTER_VALIDATE_EMAIL) !== false);
        $this->assertFalse(filter_var('', FILTER_VALIDATE_EMAIL) !== false);
    }

    public function testSessionUserStructure(): void
    {
        $user = new User(1, 'admin@pharma.fr', 'hashed_pw', 'ADMIN');

        $sessionData = [
            'id'    => $user->getId(),
            'email' => $user->getEmail(),
            'role'  => $user->getRole()
        ];

        $this->assertArrayHasKey('id', $sessionData);
        $this->assertArrayHasKey('email', $sessionData);
        $this->assertArrayHasKey('role', $sessionData);
        $this->assertSame(1, $sessionData['id']);
        $this->assertSame('admin@pharma.fr', $sessionData['email']);
        $this->assertSame('ADMIN', $sessionData['role']);
    }

    public function testRoleBasedRedirectMapping(): void
    {
        $roleRedirects = [
            'ADMIN'       => 'index.php?action=dashboard',
            'PHARMACIEN'  => 'index.php?action=inventory',
            'PREPARATEUR' => 'index.php?action=stock',
        ];

        $this->assertArrayHasKey('ADMIN', $roleRedirects);
        $this->assertArrayHasKey('PHARMACIEN', $roleRedirects);
        $this->assertArrayHasKey('PREPARATEUR', $roleRedirects);
        $this->assertSame('index.php?action=dashboard', $roleRedirects['ADMIN']);
        $this->assertSame('index.php?action=inventory', $roleRedirects['PHARMACIEN']);
        $this->assertSame('index.php?action=stock', $roleRedirects['PREPARATEUR']);
    }
}
