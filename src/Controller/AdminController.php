<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    #[Route('/admin/competicion/cargar', name: 'app_admin')]
    public function index(HttpClientInterface $httpClient): Response
    {

        $response = $httpClient->request(
            'GET',
            'http://api.football-data.org/v4/competitions/'
        );

        $content = $response->getContent();

        return $this->render('admin/admin.html.twig', [
            'controller_name' => 'AdminController',
            'content' => $content,
        ]);
    }
}
