<?php

namespace App\Controller;

use App\Entity\Competicion;
use App\Entity\Review;
use App\Repository\CompeticionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ReviewType;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
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
    #[Route('/competicion/{id}', name: 'app_competicion_show')]
    public function show(
        Competicion $competicion,
        Request $request,
        EntityManagerInterface $em,
        \App\Repository\ReviewRepository $reviewRepo // Inyectamos el repositorio
    ): Response {
        $user = $this->getUser();

        // 1. Buscamos si el usuario ya tiene una review en esta competición
        $existingReview = $reviewRepo->findOneBy([
            'usuario' => $user,
            'competicion' => $competicion
        ]);

        $form = null;

        // 2. Solo creamos el formulario si NO existe una review previa
        if (!$existingReview) {
            $review = new Review();
            $form = $this->createForm(ReviewType::class, $review);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $review->setUsuario($user);
                $review->setCompeticion($competicion);

                $em->persist($review);
                $em->flush();

                $this->addFlash('success', '¡Gracias por tu valoración!');
                return $this->redirectToRoute('app_competicion_show', ['id' => $competicion->getId()]);
            }
            $form = $form->createView();
        }

        return $this->render('competicion/show.html.twig', [
            'competicion' => $competicion,
            'reviewForm' => $form,
            'hasAlreadyReviewed' => $existingReview !== null, // Pasamos esta variable a la vista
            'reviews' => $competicion->getReviews(),
        ]);
    }
}
