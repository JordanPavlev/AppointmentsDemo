<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }


    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $this->loadUsers($manager);



        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager)
    {
        // Create a user
        $user = new User();
        $user->setUsername('Ivan');
        $user->setRoles(['ROLE_USER']);
        $plaintextPassword = '1Password!';
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );

        // Hash the password
        $user->setPassword($hashedPassword);

        $manager->persist($user);

        // Create another user
        $user = new User();
        $user->setUsername('Stefan');
        $user->setRoles(['ROLE_USER']);

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );

        $user->setPassword($hashedPassword);

        $manager->persist($user);
    }
}
