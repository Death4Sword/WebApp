<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Notification\ContactNotification;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WebAppController extends AbstractController
{
    
    #[Route('/', name: 'home')]
    public function home()
    {
        return $this->render('web_app/home.html.twig');
    }
    
    
    #[Route('/web/app', name: 'app_web_app')]
    public function index(): Response
    {
        return $this->render('web_app/index.html.twig', [
            'controller_name' => 'WebAppController',
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, EntityManagerInterface $manager, ContactNotification $notification)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $contact->setCreatedAt(new \DateTimeInterface);
            $notification->notify($contact);
            $this->addFlash('success', 'Votre Email a bien été envoyé');
            $manager->persist($contact); // on prépare l'insertion
            $manager->flush(); // on execute l'insertion
        }

        return $this->render("web_app/contact.html.twig", [
            'formContact' => $form->createView()
        ]);
    }
}
