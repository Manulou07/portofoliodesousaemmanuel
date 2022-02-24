<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\Mime\Address;
use App\Repository\AproposRepository;
use App\Repository\ProjetsRepository;
use App\Repository\CompetenceRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request, MailerInterface $mailer, CompetenceRepository $competenceRepository, AproposRepository $aproposRepository, ProjetsRepository $projetsRepository): Response
    {

      
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData(); 
            $email = (new TemplatedEmail())
                ->from(new Address($contact['email'], $contact['prenom'] . ' ' . $contact['nom']))
                ->to(new Address('desousa.emmanuel@gmail.com'))
                ->subject('PORTOFOLIO - demande de contact - ' . $contact['objet'])
                ->htmlTemplate('contact/index.html.twig') 
                ->context([ 
                    'prenom' => $contact['prenom'],
                    'nom' => $contact['nom'],
                    'adresseEmail' => $contact['email'],
                    'telephone' => $contact['telephone'],
                    'objet' => $contact['objet'],
                    'message' => $contact['message'],
                ]);
           
            $mailer->send($email);
            $this->addFlash('secondary', 'Votre message a bien été envoyé');
            return $this->redirectToRoute('home');
        }
        
       $technologies = $competenceRepository->findAll();
        $apropos = $aproposRepository->findAll();
        $projets = $projetsRepository->findAll();
      

        
        return $this->render('home/index.html.twig',[
            'projets' => $projets,
            'competences' => $technologies, 
            'apropos' => $apropos,
            'contactForm' => $form->createView(),
        ]);
    }

    
}
