<?php

namespace App\Controller;

use App\Entity\Competence;
use App\Form\CompetenceType;
use App\Repository\CompetenceRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompetenceController extends AbstractController
{
    #[Route('/competence', name: 'competence')]
    public function index(): Response
    {
        return $this->render('competence/index.html.twig', [
            'controller_name' => 'CompetenceController',
        ]);
    }

    #[Route('/admin/competences', name: 'admin_competences_index')]
   
    public function adminIndex(CompetenceRepository $competencesRepository): Response
    {
        $competences = $competencesRepository->findAll();
        
        return $this->render('admin/competences.html.twig', [
            'competences' => $competences,
        ]);
    }
    #[Route('/admin/competences/create', name: 'competence_create')]
    public function create(Request $request,  ManagerRegistry $managerRegistry)
    {
        $competence = new Competence();
        $form = $this->createForm(CompetenceType::class, $competence); 
        $form->handleRequest($request);
      

        if ($form->isSubmitted() && $form->isValid()) {

            $manager = $managerRegistry->getManager();

            if (!empty($form['logo']->getData())) {
                $infoLogo = $form['logo']->getData(); 
                $extensionLogo = $infoLogo->guessExtension(); 
                $nomLogo = time() . '.' . $extensionLogo;
                $infoLogo->move($this->getParameter('dossier_photos'), $nomLogo);
                $competence->setLogo($nomLogo);
                $manager->persist($competence);
           }else{
            $this->addFlash('danger', 'La Logo est obligatoire');
            return $this->redirectToRoute('competence_create');
            }
            
            $manager->flush();

            $this->addFlash('success', 'La competence a bien été ajoutée');
            return $this->redirectToRoute('admin_competences_index');
        }
            return $this->render('admin/competencesForm.html.twig', [
            'competenceForm' => $form->createView()
        ]);
    }
}
