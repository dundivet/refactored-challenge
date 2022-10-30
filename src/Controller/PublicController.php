<?php

namespace App\Controller;

use App\Entity\ToDo;
use App\Form\ToDoType;
use App\Manager\ToDoManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PublicController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', []);
    }

    #[Route('/todos/add', name: 'todos_add', methods: ['GET', 'POST'])]
    public function add(Request $request, ToDoManager $manager): Response
    {
        $toDo = new ToDo();
        $form = $this->createForm(ToDoType::class, $toDo, [
            'action' => $this->generateUrl('todos_add'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (null !== $manager->update($toDo)) {
                $this->addFlash('success', 'ToDo added successfully');

                return $this->redirectToRoute('home');
            }

            $this->addFlash('error', 'ToDo could not be added');
        }

        return $this->render('default/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/todos/{id<\d+>}/add', name: 'todos_add_with_parent', methods: ['GET', 'POST'])]
    public function addWithParent(int $id, Request $request, ToDoManager $manager): Response
    {
        $parent = $manager->findById($id);
        if (null === $parent) {
            throw new NotFoundHttpException('ToDo with id \''.$id.'\' was not found');
        }

        $toDo = new ToDo();
        $form = $this->createForm(ToDoType::class, $toDo, [
            'action' => $this->generateUrl('todos_add_with_parent', ['id' => $id]),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $toDo->setParent($parent);
            if (null !== $manager->update($toDo)) {
                $this->addFlash('success', 'ToDo added successfully');

                return $this->redirectToRoute('todos_show', ['id' => $id]);
            }

            $this->addFlash('error', 'ToDo could not be added');
        }

        return $this->render('default/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/todos/{id<\d+>}', name: 'todos_show', methods: ['GET'])]
    public function show(int $id, ToDoManager $manager): Response
    {
        $todo = $manager->findById($id);
        if (null === $todo) {
            throw new NotFoundHttpException('ToDo with id \''.$id.'\' was not found');
        }

        return $this->render('default/show.html.twig', [
            'todo' => $todo,
        ]);
    }
}
