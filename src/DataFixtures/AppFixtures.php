<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Objet;
use App\Entity\Personne;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for($i = 0; $i < 5;$i++)
        {
            $personne = new Personne();
            $personne->setNom($faker->name($gender ='male'|'female'));
            $personne->setPrenom($faker->firstName($gender ='male'|'female'));
            $personne->setEmail($faker->email);
            $manager->persist($personne);
        }
                
        for($i = 0; $i < 10;$i++)
        {
            $objet = new Objet();
            $objet->setLibelle('Objet '.$i);
            $objet->setDescription($faker->text($maxNbChars = 200));
            $objet->setPhoto('https://thumbs.dreamstime.com/b/loup-9205133.jpg');
            $manager->persist($objet);
        }

        $manager->flush();
    }
}
