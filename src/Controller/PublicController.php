<?php

namespace App\Controller;

use App\Entity\ToDo;
use App\Form\ToDoType;
use App\Manager\ToDoManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', []);
    }

    #[Route('/todo/add', name: 'add_todo', methods: ['GET', 'POST'])]
    public function add(Request $request, ToDoManager $manager): Response
    {
        $toDo = new ToDo();
        $form = $this->createForm(ToDoType::class, $toDo, [
            'action' => $this->generateUrl('add_todo'),
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
}
