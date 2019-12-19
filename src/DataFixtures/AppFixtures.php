<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\Repository;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {

    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
