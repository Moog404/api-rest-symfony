<?php

namespace App\DataFixtures;

use App\Entity\Phone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PhoneFixtures extends Fixture
{
    private $names = ["Iphone", "Samsung"];
    private $colors = ["noir", "rouge", "blanc"];
    public function load(ObjectManager $manager)
    {
        for($i=1; $i <= 20; $i++) {
            $phone = new Phone();
            $phone->setName($this->names[rand(0,1)].''.rand(5,8))
                ->setColor($this->colors[rand(0,2)])
                ->setPrice(rand(500,1000))
                ->setDescription('A phone with '.rand(10,50).' tricks');
            $manager->persist($phone);
        }
        $manager->flush();
    }
}
