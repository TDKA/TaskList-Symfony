<?php

namespace App\Controller;

use App\Entity\Check;
use App\Entity\ToDo;
use App\Form\ToDoType;
use App\Repository\CheckRepository;
use App\Repository\ToDoRepository;
use ContainerD5BaPme\PaginatorInterface_82dac15;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

// Url example localhost:8000/fr/todo
/**
 * @Route("/{_locale}")
 *
 */
class ToDoController extends AbstractController
{
    /**
     * @Route("/todo", name="todo")
     * @Route("/todo/{order}", name="toDoOrder")
     */
    public function index(ToDoRepository $repo, UserInterface $user, $order = null, PaginatorInterface $paginator, Request $request): Response
    {

        if ($order) {

            if ($order == 'recent') {
                $todos = $repo->findByUserSortedByMostRecent($user);
            }
            if ($order == 'oldest') {
                $todos = $repo->findByUserSortedByLessRecent($user);
            }
            if ($order == 'urgent') {
                $todos = $repo->findByUserSortedByMostUrgent($user);
            }
            if ($order == 'lessUrgent') {
                $todos = $repo->findByUserSortedByLessUrgent($user);
            }
        } else {
            $todos = $user->getTodos();
        }

        $todos = $paginator->paginate(
            $todos,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('to_do/index.html.twig', [
            'controller_name' => 'ToDoController',
            'todos' => $todos,

        ]);
    }

    /**
     * @Route("/create", name="createTask", priority = 2)
     * 
     */
    public function create(Request $req, EntityManagerInterface $manager, UserInterface $user)
    {

        $todo = new ToDo();

        // if ($user != $todo->getUser()) {

        //     return $this->redirectToRoute('todo');
        // }

        $form = $this->createForm(ToDoType::class, $todo);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {

            $todo->setCreatedAt(new \DateTime());
            $todo->setUser($user);

            $manager->persist($todo);
            $manager->flush();

            return $this->redirectToRoute('todo');
        }

        return $this->render('to_do/create.html.twig', [
            'form' => $form->CreateView()

        ]);
    }


    /**
     *  @Route("/todo/delete/{id}", name = "deleteToDo", priority=2);
     * 
     */
    public function delete(Todo $todo, EntityManagerInterface $manager)
    {

        $manager->remove($todo);
        $manager->flush();

        return $this->redirectToRoute('todo');
    }

    /**
     * @Route("/todo/check/{id}", name="toDoCheck")
     *
     */
    public function check(ToDo $todo, EntityManagerInterface $manager, CheckRepository $checkRepo)
    {

        if (!$todo->getChecked()) {

            $check = new Check();

            $check->setTodo($todo);
            $check->setUser($todo->getUser());

            $manager->persist($check);
            $message = 'checked';
        } else {

            $check = $todo->getChecked();
            $manager->remove($check);
            $message = 'unchecked';
        }
        $manager->flush();

        //Checks done 4/32
        $user = $todo->getUser();
        $nbChecks = $checkRepo->count(['user' => $user]);

        $data = [
            'message' => $message,
            'nbChecks' => $nbChecks

        ];



        return $this->json($data, 200);
        //        return $this->redirectToRoute('todo');


    }
}
