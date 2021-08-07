<?php

namespace App\Controller;

use App\Entity\ToDo;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(UserRepository $repo ): Response
    {
        $users = $repo->findAll();
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'users' => $users
        ]);
    }


    /**
     * @Route("/delete/user/{id}", name="deleteUser")
     */
    public function delete(User $user, EntityManagerInterface  $manager): Response
    {
        $manager->remove($user);
        $manager->flush();

        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/admin/{id}", name="showUser")
     *
     */
    public function showUser(User $user)
    {

        return $this->render("admin/show.html.twig", [
            'user' => $user
        ]);
    }

    /**
     *  @Route("/todo/delete/{id}", name = "deleteToDoOfUser", priority=2);
     *
     */
    public function deleteToDo(Todo $todo, EntityManagerInterface $manager)
    {

        $manager->remove($todo);
        $manager->flush();

        return $this->redirectToRoute('admin');
    }

}
