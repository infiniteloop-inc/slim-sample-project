<?php

declare(strict_types=1);

namespace Test\Unit\User\Auth;

use App\Domain\User\Auth\AuthenticationService;
use App\Domain\User\User;
use App\Domain\User\UserFactory;
use App\Domain\User\UserRepository;
use App\Exceptions\UnauthenticatedException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class AuthenticationServiceTest extends TestCase
{
    public function userProvider(): array
    {
        return [
            'authenticated'   => ['dummy_user_id', (new UserFactory())->create('dummy_name')],
            'unauthenticated' => [null, null],
        ];
    }

    public function userIdProvider(): array
    {
        return [
            'authenticated'   => ['dummy_user_id', true],
            'unauthenticated' => [null, false],
        ];
    }

    public function testAttemptLogin(): void
    {
        $service = new AuthenticationService(
            $userRepository = $this->createMock(UserRepository::class),
            $session = new Session(new MockArraySessionStorage()),
        );

        $token = 'dummy_token';

        $userRepository->expects($this->once())
            ->method('resolveByAuthToken')
            ->with($this->equalTo($token))
            ->willReturn((new UserFactory())->create('dummy_name'));

        $user = $service->attemptLogin($token);
        $this->assertSame($user->id(), $session->get('userId'));
    }

    public function testAttemptLoginThrowsUnauthenticatedException(): void
    {
        $this->expectException(UnauthenticatedException::class);

        $service = new AuthenticationService(
            $userRepository = $this->createMock(UserRepository::class),
            $session = new Session(new MockArraySessionStorage()),
        );

        $token = 'dummy_token';

        $userRepository->expects($this->once())
            ->method('resolveByAuthToken')
            ->with($this->equalTo($token))
            ->willReturn(null);

        $service->attemptLogin($token);
    }

    /**
     * @dataProvider userProvider
     */
    public function testFetchLoggedInUser(?string $userId, ?User $user): void
    {
        $service = new AuthenticationService(
            $userRepository = $this->createMock(UserRepository::class),
            $session = new Session(new MockArraySessionStorage()),
        );
        $session->set('userId', $userId);

        $userRepository->expects($this->any())
            ->method('resolve')
            ->with($this->equalTo($userId))
            ->willReturn($user);

        $resultUser = $service->fetchLoggedInUser();
        $this->assertSame($user, $resultUser);
    }

    /**
     * @dataProvider userIdProvider
     */
    public function testIsLoggedIn(?string $userId, bool $isLoggedIn): void
    {
        $service = new AuthenticationService(
            $userRepository = $this->createMock(UserRepository::class),
            $session = new Session(new MockArraySessionStorage()),
        );
        $session->set('userId', $userId);

        $result = $service->isLoggedIn();
        $this->assertSame($isLoggedIn, $result);
    }
}
