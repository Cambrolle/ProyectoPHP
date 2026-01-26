<?php

namespace App\Controller;

use App\Repository\CompeticionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CompeticionController extends AbstractController
{
    #[Route('/competicion', name: 'app_competicion')]
    public function index(CompeticionRepository $repository): Response
    {

        $competiciones_list = $repository->findAll();





        return $this->render('competicion/competicion.html.twig', [
            'controller_name' => 'CompeticionController',
            'competiciones' => $competiciones_list,
        ]);
    }
}
