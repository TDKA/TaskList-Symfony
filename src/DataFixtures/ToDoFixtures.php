<?php

namespace App\DataFixtures;

use App\Entity\User;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\ToDo;


class ToDoFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $id = 5;
        $user = $manager->getRepository(User::class)->find($id);


        for ($i = 1; $i < 40; $i++) {

            $todo = new ToDo();
            $todo->setDescription('une description');
            $todo->setCreatedAt(new \DateTime());
            $todo->setDueDate(new \DateTime());
            $todo->setUser($user);
            $manager->persist($todo);
        }

        $manager->flush();
    }
}
