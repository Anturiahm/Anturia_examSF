<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Utilisateur;

class RHFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new Utilisateur();
        $user->setNom('Nom de l\'utilisateur');
        $user->setPrenom('Prénom de l\'utilisateur');
        $user->setPhoto('chemin/vers/photo.jpg');
        $user->setSecteur('RH');
        $user->setTypeContrat('CDD'); 

        if ($user->getTypeContrat() === 'CDD' || $user->getTypeContrat() === 'Interim') {
            $dateSortie = new \DateTime('2023-12-31');  
            $user->setDateSortie($dateSortie);
        }

        $encodedPassword = '$2y$13$TDR8E7d1QX65cxYjccSTCeyiOEn6T39a24VQkw3UI8BiSKwwppDP6';
        $user->setPassword($encodedPassword);

        $user->setRoles(['ROLE_USER']);

        $manager->persist($user);
        $manager->flush();
    }
}
