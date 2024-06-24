<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }


    public function load(ObjectManager $manager): void
    {

        $user1 = $this->createUser1();
        $user2 = $this->createUser2();
        $user3 = $this->createUser3();

        $manager->persist($user1);
        $manager->persist($user2);
        $manager->persist($user3);

        $manager->flush();
    }

    private function createUser1(): User
    {
        $user = new User();

        $user->setFirstName("alvin");
        $user->setLastName("bellu");
        $user->setEmail("alvin@gmail.com");
        $user->setPhone("076543234");
        $user->setCreatedAt(new DateTimeImmutable());
        $user->setUpdatedAt(new DateTimeImmutable());

        $passwordHashed = $this->hasher-> hashPassword($user, "azerty1234A*");
        $user->setPassword($passwordHashed);

        $user->setVerified(true);
        $user->setVerifiedAt(new DateTimeImmutable() );

        return $user;
    }

    private function createUser2(): User
    {
        $user = new User();

        $user->setFirstName("spitz");
        $user->setLastName("bellu");
        $user->setEmail("spitz@gmail.com");
        $user->setPhone("076543238");
        $user->setCreatedAt(new DateTimeImmutable());
        $user->setUpdatedAt(new DateTimeImmutable());

        $passwordHashed = $this->hasher-> hashPassword($user, "azerty1234A*");
        $user->setPassword($passwordHashed);

        $user->setVerified(true);
        $user->setVerifiedAt(new DateTimeImmutable() );

        return $user;
    }

    private function createUser3(): User
    {
        $user = new User();

        $user->setFirstName("fiocco");
        $user->setLastName("bellu");
        $user->setEmail("fiocco@gmail.com");
        $user->setPhone("07654323422");
        $user->setCreatedAt(new DateTimeImmutable());
        $user->setUpdatedAt(new DateTimeImmutable());

        $passwordHashed = $this->hasher-> hashPassword($user, "azerty1234A*");
        $user->setPassword($passwordHashed);

        $user->setVerified(true);
        $user->setVerifiedAt(new DateTimeImmutable() );

        return $user;
    }
}
