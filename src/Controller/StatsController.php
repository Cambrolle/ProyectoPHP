<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\CompeticionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class StatsController extends AbstractController
{
    #[Route('/estadisticas/{id}', name: 'app_stats', defaults: ['id' => null])]
    public function index(
        ?Category $category,
        CompeticionRepository $compRepo,
        CategoryRepository $catRepo
    ): Response {

        $rankingCategory = null;

        if ($category) {
            // Consultamos la tabla ranking_competicion (rc) a través de competicion (c)
            $rankingCategory = $compRepo->createQueryBuilder('c')
                ->select('c as competicion, AVG(rc.position) as avgPos') // Ojo: mira si en tu tabla es 'position' o 'posicion'
                ->join('c.categories', 'cat')
                ->join('c.rankingCompeticions', 'rc')
                ->where('cat.id = :catId')
                ->setParameter('catId', $category->getId())
                ->groupBy('c.id')
                ->orderBy('avgPos', 'ASC') // El 1º es el que tiene media más cercana a 1
                ->getQuery()
                ->getResult();
        }

        return $this->render('stats/index.html.twig', [
            'topCompeticiones' => $compRepo->findTopRated(5),
            'todasLasCategorias' => $catRepo->findAll(),
            'categorySelected' => $category,
            'rankingCategory' => $rankingCategory
        ]);
    }
}
