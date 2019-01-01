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

        $this->loadUsers($manager);

        $this->loadMicroPosts($manager);

        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager): void {

            for($i=0;$i<20;$i++) {

                $user = new User();
                $fullName = $this->faker->name;
                $user->setEmail($this->faker->email);
                $user->setUsername($this->faker->userName);
                $user->setFullName($fullName);
                $user->setPassword($this->passwordEncoder->encodePassword($user, 'abc123'));

                if($i == 0) {
                    $this->addReference('user_1', $user);
                }

                $manager->persist($user);
            }


    }

    private function loadMicroPosts(ObjectManager $manager): void {

        for($i=0;$i< 50;$i++){
            $mp = new MicroPost();
            $mp->setText($this->faker->paragraph(rand(5, 30)));
            $mp->setTime(new \DateTime($this->randomDateStr()));

            $user = $this->getReference('user_1');
            $mp->setUser($user);

            $manager->persist($mp);
        }

    }

    private function randomDateStr(): string {
        $month = rand(1, 12);
        $day = rand(1, 28);
        return "2018-$month-$day";
    }
}
