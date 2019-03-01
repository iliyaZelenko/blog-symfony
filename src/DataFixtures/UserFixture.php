<?php

namespace App\DataFixtures;

use App\DataFixtures\Traits\RandomReference;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture implements OrderedFixtureInterface
{
    use RandomReference;

    public const REFERENCE_PREFIX = 'user';
    public const USERS = [
        [
            'username' => 'Илья',
            'email' => 'iliyazelenkog@gmail.com',
            'password' => '123'
        ],
        [
            'username' => 'Василько-с_района.КРУТОЙ',
            'email' => 'vasilko@example.com',
            'password' => '321'
        ],
        [
            'username' => 'Неуравновешанный_хомяк_убийца',
            'email' => 'killer@example.com',
            'password' => null
        ],
        [
            'username' => 'ПьянаяяМартышка',
            'email' => 'obezyana@example.com',
            'password' => null
        ]
    ];
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public static function getCount(): int
    {
        return count(static::USERS);
    }

    public function load(ObjectManager $manager): void
    {
        $usersCount = static::getCount();

        for ($i = 1; $i <= $usersCount; ++$i) {
            [
                'username' => $username,
                'email' => $email,
                'password' => $password
            ] = static::USERS[$i - 1];
            // static password or random string
            $plainPassword = $password ?? 'just text ' . bin2hex(random_bytes(10));
            $user = new User($username, $email);

            $user->setPassword(
                $this->passwordEncoder->encodePassword($user, $plainPassword)
            );
            $this->addReference(self::REFERENCE_PREFIX . $i, $user);
            $manager->persist($user);
        }

        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder(): int
    {
        return 99;
    }
}
