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

    private $numberOfUsers = 50;

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

            for($i=0;$i<$this->numberOfUsers;$i++) {

                $user = new User();
                $fName = $this->faker->firstName;
                $lName = $this->faker->lastName;
                $fullName = $fName . ' ' . $lName;
                $userName = mb_strtolower($fName);
                $email = mb_strtolower($fName.'_'.$lName).'@gmail.com';

                $user->setEmail($email);
                $user->setUsername($userName);
                $user->setFullName($fullName);
                $user->setPassword($this->passwordEncoder->encodePassword($user, 'abc123'));

                $this->addReference("user_{$i}", $user);

                $manager->persist($user);
            }
    }

    private function loadMicroPosts(ObjectManager $manager): void {

        for($i=0;$i< 200;$i++){
            $mp = new MicroPost();
            $mp->setText($this->faker->paragraph(rand(5, 30)));
            $mp->setTime(new \DateTime($this->randomDateStr()));

            $user = $this->getReference($this->randomUserStr());
            $mp->setUser($user);

            $manager->persist($mp);
        }

    }

    private function randomUserStr(): string {
        $random = rand(0, $this->numberOfUsers-1);
        return "user_{$random}";
    }

    private function randomDateStr(): string {
        $year = rand(10, 18);
        $month = rand(1, 12);
        $day = rand(1, 28);
        return "20$year-$month-$day";
    }
}
