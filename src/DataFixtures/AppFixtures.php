<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    private $faker;

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $this->faker = Faker\Factory::create();

        $this->loadMicroPosts($manager);


        $manager->flush();
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
