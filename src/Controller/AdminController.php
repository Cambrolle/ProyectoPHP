<?php

namespace App\Controller;

use App\Entity\Competicion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_site')]
    public function index(): Response
    {

        return $this->render('admin/homeAdmin.html.twig', [

            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/custom/cargar-datos', name: 'app_cargar_datos_api')]
    public function data_load(HttpClientInterface $httpClient, EntityManagerInterface $entityManager): Response
    {
        $response = $httpClient->request('GET', 'http://api.football-data.org/v4/competitions/');
        $content = $response->toArray();

        foreach ($content['competitions'] as $data) {
            $existente = $entityManager->getRepository(Competicion::class)->findOneBy(['name' => $data['name']]);
            if (!$existente) {
                $competicion = new Competicion();
                $competicion->setName($data['name']);
                $competicion->setType($data['type']);
                $competicion->setEmblem($data['emblem'] ?? null);
                $entityManager->persist($competicion);
            }
        }
        $entityManager->flush();

        return $this->render('admin/admin.html.twig', [
            'content' => $content,
        ]);
    }
}
