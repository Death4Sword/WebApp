<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use App\Form\EventCreationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventCreationController extends AbstractController
{
    #[Route('/event/creation', name: 'app_event_creation')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserRepository $USER): Response
    {
        
        $event = new Event();
        $form = $this->createForm(EventCreationFormType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password

            $user = $this->getUser()->getUserIdentifier();
            $dataUser = $USER->findOneBy([
                'email' => $user
                
            ]);
            $event->setUserId($dataUser);
            $event->setDate(new \DateTime());
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }



        return $this->render('event_creation/index.html.twig', [
            'controller_name' => 'EventCreationController',
            'formEvent' => $form->createView()
        ]);
    }
}
