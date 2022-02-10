<?php

namespace App\Controller;

use App\Repository\AproposRepository;
use App\Repository\CompetenceRepository;
use App\Repository\ProjetsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(CompetenceRepository $competenceRepository, AproposRepository $aproposRepository, ProjetsRepository $projetsRepository): Response
    {

        $technologies = $competenceRepository->findAll();
        $apropos = $aproposRepository->findAll();
        $projets = $projetsRepository->findAll();

        return $this->render('home/index.html.twig',[
            'competences' => $technologies, 
            'apropos' => $apropos,
            'projets' => $projets
        ]);
    }
}
