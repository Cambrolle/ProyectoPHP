<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Ranking;
use App\Entity\RankingCompeticion;
use App\Repository\CompeticionRepository;
use App\Repository\RankingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class RankingController extends AbstractController
{
    #[Route('/ranking/category/{id}', name: 'app_ranking_create')]
    public function create(Category $category, RankingRepository $rankingRepo): Response
    {
        // 1. Verificar si el usuario ya tiene un ranking para esta categoría
        $existingRanking = $rankingRepo->findOneBy([
            'usuario' => $this->getUser(),
            'category' => $category
        ]);

        if ($existingRanking) {
            $this->addFlash('info', 'Ya has rankeado esta categoría.');
            return $this->redirectToRoute('app_categories_index');
        }

        return $this->json(['success' => true, 'redirect' => $this->generateUrl('app_category_show', ['id' => $category->getId()])]);
    }


    #[Route('/ranking/edit/{id}', name: 'app_ranking_edit', methods: ['POST', 'GET'])]
    public function edit(Ranking $ranking, EntityManagerInterface $em): Response
    {
        // Seguridad: Solo el dueño puede editar
        if ($ranking->getUsuario() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $categoryId = $ranking->getCategory()->getId();

        // Borramos el ranking y sus relaciones (gracias a que es OneToMany)
        $em->remove($ranking);
        $em->flush();

        // Redirigimos a la página de creación para que lo haga de nuevo
        return $this->redirectToRoute('app_category_show', ['id' => $categoryId]);
    }

    #[Route('/ranking/save/{id}', name: 'app_ranking_save', methods: ['POST'])]
    public function save(Category $category, Request $request, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true); // Formato: {"compId": "1", "compId": "2"}

        if (!$data) return $this->json(['success' => false], 400);

        $ranking = new Ranking();
        $ranking->setUsuario($this->getUser());
        $ranking->setCategory($category);
        $em->persist($ranking);

        foreach ($data as $compId => $tierValue) {
            if ($tierValue === "") continue; // Saltar si no se seleccionó nivel

            $competicion = $em->getReference(\App\Entity\Competicion::class, $compId);

            $rc = new RankingCompeticion();
            $rc->setRanking($ranking);
            $rc->setCompeticion($competicion);
            $rc->setPosition((int)$tierValue); // 1=S, 2=A, 3=B, 4=C
            $em->persist($rc);
        }

        $em->flush();
        return $this->json(['success' => true]);
    }
    #[Route('/estadisticas', name: 'app_stats')]
    public function index(CompeticionRepository $repository): Response
    {
        // Obtenemos el Top 5
        $topCompeticionesData = $repository->findTopRated(5);

        return $this->render('stats/index.html.twig', [
            'topCompeticiones' => $topCompeticionesData,
        ]);
    }

}
