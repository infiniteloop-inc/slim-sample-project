<?php

declare(strict_types=1);

namespace Test\Unit;

use App\Domain\User\UserFactory;
use App\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UserTest extends TestCase
{
    public function validNameProvider(): array
    {
        return [
            ['2 characters' => 'ab'],
            ['20 characters' => 'abcdefghijklmnopqrst'],
        ];
    }

    public function invalidNameProvider(): array
    {
        return [
            ['1 character' => 'a'],
            ['21 characters' => 'abcdefghijklmnopqrstu'],
        ];
    }

    public function testRegenerateAuthToken(): void
    {
        $user = (new UserFactory())->create('foo');
        $oldToken = $user->userDto->authToken;
        $this->assertNotSame($oldToken, $user->regenerateAuthToken());
    }

    public function testGenerateAuthToken(): void
    {
        $user = (new UserFactory())->create('foo');
        $this->assertTrue(Uuid::isValid($user->userDto->authToken));
    }

    /**
     * @doesNotPerformAssertions
     * @dataProvider validNameProvider
     */
    public function testValidateName(string $name): void
    {
        (new UserFactory())->create($name);
    }

    /**
     * @dataProvider invalidNameProvider
     */
    public function testValidateNameThrowsInvalidArgumentException(string $name): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new UserFactory())->create($name);
    }
}
