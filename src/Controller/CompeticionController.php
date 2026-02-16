<?php

namespace App\Controller;

use App\Entity\Competicion;
use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\CompeticionRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class CompeticionController extends AbstractController
{
    /**
     * Listado general de competiciones con buscador y filtro por tipo
     */
    #[Route('/competicion', name: 'app_competicion')]
    public function index(CompeticionRepository $repository, Request $request): Response
    {
        // 1. Obtenemos los parámetros de la URL (?search=xxx&type=xxx)
        $search = $request->query->get('search');
        $type = $request->query->get('type');

        // 2. Llamamos al método filtrado que creamos en el Repository
        $competiciones_list = $repository->findByFilters($search, $type);

        return $this->render('competicion/competicion.html.twig', [
            'competiciones' => $competiciones_list,
            'searchTerm' => $search,    // Enviamos el texto buscado para mantenerlo en el input
            'selectedType' => $type,    // Enviamos el tipo seleccionado para mantenerlo en el select
            'controller_name' => 'Competiciones'
        ]);
    }

    /**
     * Detalle de una competición y formulario de Review
     */
    #[Route('/competicion/{id}', name: 'app_competicion_show')]
    public function show(
        Competicion $competicion,
        Request $request,
        EntityManagerInterface $em,
        ReviewRepository $reviewRepo
    ): Response {
        $user = $this->getUser();

        // Buscamos si el usuario ya valoró esta competición
        $existingReview = $reviewRepo->findOneBy([
            'usuario' => $user,
            'competicion' => $competicion
        ]);

        $form = null;

        // Si no existe review, creamos el formulario para que pueda hacerla
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
            'hasAlreadyReviewed' => $existingReview !== null,
            'reviews' => $competicion->getReviews(),
        ]);
    }

    /**
     * Listado de reviews del usuario identificado
     */
    #[Route('/mis-reviews', name: 'app_mis_reviews')]
    public function misReviews(): Response
    {
        $user = $this->getUser();
        return $this->render('user/mis_reviews.html.twig', [
            'reviews' => $user->getReviews(),
        ]);
    }

    /**
     * Edición de una review existente
     */
    #[Route('/review/editar/{id}', name: 'app_review_edit')]
    public function editReview(Review $review, Request $request, EntityManagerInterface $em): Response
    {
        if ($review->getUsuario() !== $this->getUser()) {
            throw $this->createAccessDeniedException('No puedes editar una valoración que no es tuya.');
        }

        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Valoración actualizada correctamente.');
            return $this->redirectToRoute('app_mis_reviews');
        }

        return $this->render('user/editar_review.html.twig', [
            'reviewForm' => $form->createView(),
            'competicion' => $review->getCompeticion(),
        ]);
    }
}
