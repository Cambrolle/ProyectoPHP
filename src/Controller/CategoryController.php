<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\RankingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class CategoryController extends AbstractController
{
    #[Route('/categories', name: 'app_categories_index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/categories/{id}', name: 'app_category_show')]
    public function show(
        Category $category,
        RankingRepository $rankingRepo
    ): Response {
        $user = $this->getUser();

        // Buscamos si el usuario ya tiene un ranking guardado
        $miRanking = $rankingRepo->findOneBy([
            'usuario' => $user,
            'category' => $category
        ]);

        // SI NO TIENE RANKING: Mostramos la interfaz de creación (Drag & Drop)
        if (!$miRanking) {
            return $this->render('ranking/tier_list.html.twig', [
                'category' => $category,
                'competiciones' => $category->getCompeticiones(),
            ]);
        }

        // SI YA TIENE RANKING: Mostramos su Tier List ya guardada
        // (Asegúrate de tener esta plantilla o créala ahora)
        return $this->render('ranking/view_ranking.html.twig', [
            'category' => $category,
            'ranking' => $miRanking,
        ]);
    }
}
