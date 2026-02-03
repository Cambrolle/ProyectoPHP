<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class ApiLoaderController extends AbstractController
{
    #[Route('/admin/cargar-api', name: 'app_admin_cargar_api')]
    public function cargarDatos(): Response
    {
        // 1. Aquí pondrás tu lógica para llamar a la API
        // Ejemplo: $data = $apiService->fetchData();

        // 2. Aquí tu lógica para guardar en la base de datos

        // 3. Añadimos un mensaje de éxito para el usuario
        $this->addFlash('success', '¡Los datos de la API se han cargado correctamente!');

        // 4. Redirigimos de vuelta al Dashboard de EasyAdmin
        return $this->redirectToRoute('admin');
    }
}
