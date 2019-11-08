<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        foreach ($this->getUserData() as [$name, $lastName, $userName, $password, $vimeo_api_key, $roles])
        {
            $user = new User();
            $user->setName($name);
            $user->setLastName($lastName);
            $user->setUsername($userName);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setVimeoApiKey($vimeo_api_key);
            $user->setRoles($roles);
            $manager->persist($user);

        }
        $manager->flush();
    }

    public function getUserData()
    {
        return [
            ['John', 'Waine', 'jonWaine', 'passw', 'hjd8dehdh',
              ['ROLE_ADMIN']],
            ['Paul', 'Waine', 'paulWaine', 'passw', 'hjd8dehdh',
                ['ROLE_ADMIN']],
            ['Julian', 'Waine', 'julianWaine', 'passw', 'hjd8dehdh',
                ['ROLE_USER']],
        ];
    }
}
