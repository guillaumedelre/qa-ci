<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\Repository;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $admin = (new User())
            ->setEmail('admin@gmail.com')
            ->setRoles(['ROLE_ADMIN'])
        ;
        $admin->setPassword(
            $this->passwordEncoder->encodePassword(
                $admin,
                'admin'
            )
        );
        $manager->persist($admin);

        $jmo = (new User())
            ->setEmail('jmo@gmail.com')
            ->setRoles(['ROLE_USER'])
        ;
        $jmo->setPassword(
            $this->passwordEncoder->encodePassword(
                $jmo,
                'jmo'
            )
        );
        $manager->persist($jmo);

        $gde = (new User())
            ->setEmail('gde@gmail.com')
            ->setRoles(['ROLE_USER'])
        ;
        $gde->setPassword(
            $this->passwordEncoder->encodePassword(
                $gde,
                'gde'
            )
        );
        $manager->persist($gde);

        $orphan = (new User())
            ->setEmail('orphan@gmail.com')
            ->setRoles(['ROLE_USER'])
        ;
        $orphan->setPassword(
            $this->passwordEncoder->encodePassword(
                $orphan,
                'orphan'
            )
        );
        $manager->persist($orphan);

        $repository = (new Repository())
            ->setUrl('git@github.com:guillaumedelre/qatools-viewer.git')
            ->addUser($jmo)
            ->addUser($gde)
        ;
        $manager->persist($repository);

        $repository = (new Repository())
            ->setUrl('git@github.com:guillaumedelre/mezzo.git')
            ->addUser($jmo)
            ->addUser($gde)
        ;
        $manager->persist($repository);

        $manager->flush();
    }
}
