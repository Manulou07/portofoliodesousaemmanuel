<?php

namespace App\Controller;

use App\Entity\Apropos;
use App\Form\AproposType;
use App\Repository\AproposRepository;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AproposController extends AbstractController
{
    #[Route('/apropos', name: 'apropos')]
    public function index(): Response
    {
        return $this->render('apropos/index.html.twig', [
            'controller_name' => 'AproposController',
        ]);
    }
    
    #[Route('/admin/apropos', name: 'admin_apropos_index')]
   
    public function adminIndex(AproposRepository $aproposRepository): Response
    {
        $apropos = $aproposRepository->findAll();
        
        return $this->render('admin/apropos.html.twig', [
            'apropos' => $apropos,
        ]);
    }

    #[Route('/admin/apropos/update/{id}', name: 'apropos_update')]
    public function update(AproposRepository $aproposRepository, int $id, Request $request, ManagerRegistry $managerRegistry)
    {
        $apropos = $aproposRepository->find($id);
        $form = $this->createForm(AproposType::class, $apropos);
        $form->handleRequest($request);

        $manager = $managerRegistry->getManager();
        $manager->persist($apropos);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->flush();

            $this->addFlash('success', 'Le descriptif a bien été modifiée');
            return $this->redirectToRoute('admin_apropos_index');

        }
        
        return $this->render('admin/aproposForm.html.twig', [
            'aproposForm' => $form->createView(),
            'apropos' => $apropos
        ]);
    }

    #[Route('/admin/apropos/create', name: 'apropos_create')]
    public function create(AproposRepository $aproposRepository, Request $request,  ManagerRegistry $managerRegistry)
    {
        $apropos = $aproposRepository->findAll();
        
        if (count($apropos) >= 1){
            $this->addFlash('danger', 'Il ne peut pas avoir qu\'un seul descripif veuillez modifier un existant');
            return $this->redirectToRoute('admin_apropos_index');
        } else {
            $apropo = new Apropos();
            $form = $this->createForm(AproposType::class, $apropo); 
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $manager = $managerRegistry->getManager();
                $manager->persist($apropo);
                $manager->flush();

                $this->addFlash('success', 'Le descriptif a bien été ajoutée');
                return $this->redirectToRoute('admin_apropos_index');
            }
                return $this->render('admin/forfaitForm.html.twig', [
                'forfaitForm' => $form->createView()
            ]);
        }
    }

}
