<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $faker;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;


    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {

        $this->passwordEncoder = $passwordEncoder;
    }


    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $this->faker = Faker\Factory::create();

        $this->loadMicroPosts($manager);

        $this->loadUsers($manager);

        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager): void {

            for($i=0;$i<20;$i++) {

                $user = new User();
                $user->setEmail($this->faker->email);
                $user->setUsername($this->faker->userName);
                $user->setFullName($this->faker->name);
                $user->setPassword($this->passwordEncoder->encodePassword($user, 'abc123'));

                $manager->persist($user);
            }


    }

    private function loadMicroPosts(ObjectManager $manager): void {

        for($i=0;$i< 50;$i++){
            $mp = new MicroPost();
            $mp->setText($this->faker->paragraph(rand(5, 30)));
            $mp->setTime(new \DateTime($this->randomDateStr()));
            $manager->persist($mp);
        }

    }

    private function randomDateStr(): string {
        $month = rand(1, 12);
        $day = rand(1, 28);
        return "2018-$month-$day";
    }
}
