<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Student;
use App\Entity\Note;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        for ($i=0; $i < 10 ; $i++) {
          $student = new Student();
          $student->setNom($faker->lastName)
                  ->setPrenom($faker->firstName)
                  ->setDateAnnive($faker->dateTimeBetween('-35 years', '-20 years'));
          for ($j=0; $j <3 ; $j++) {
            $note = new Note();
            $note->setMatiere("matiere_$j")
                 ->setValeur($faker->randomFloat(2,0,20))
                 ->setStudent($student);
            $manager->persist($note);
          }
          $manager->persist($student);
        }
        $manager->flush();
    }
}
