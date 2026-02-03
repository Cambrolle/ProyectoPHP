<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Competicion;
use App\Entity\Usuario;
use App\Repository\CategoryRepository;
use App\Repository\CompeticionRepository;
use App\Repository\UsuarioRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class DashboardController extends AbstractDashboardController
{
    private UsuarioRepository $usuarioRepo;
    private CompeticionRepository $competicionRepo;
    private CategoryRepository $categoryRepo;

    // Inyectamos los repositorios en el constructor
    public function __construct(
        UsuarioRepository $usuarioRepo,
        CompeticionRepository $competicionRepo,
        CategoryRepository $categoryRepo
    ) {
        $this->usuarioRepo = $usuarioRepo;
        $this->competicionRepo = $competicionRepo;
        $this->categoryRepo = $categoryRepo;
    }
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Obtenemos los conteos totales
        $stats = [
            'total_usuarios' => $this->usuarioRepo->count([]),
            'total_competiciones' => $this->competicionRepo->count([]),
            'total_categorias' => $this->categoryRepo->count([]),
        ];

        return $this->render('admin/homeAdmin.html.twig', [
            'stats' => $stats,
        ]);

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // 1.1) If you have enabled the "pretty URLs" feature:
        // return $this->redirectToRoute('admin_user_index');
        //
        // 1.2) Same example but using the "ugly URLs" that were used in previous EasyAdmin versions:
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirectToRoute('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('La Casa del Fútbol Admin')

            ->setFaviconPath('favicon.ico');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Panel Principal', 'fa fa-home');

        // Cambiamos linkToCrud por linkToRoute
        // IMPORTANTE: Asegúrate de que el tercer parámetro sea EXACTAMENTE el name del Route anterior
        yield MenuItem::linkToCrud('Usuarios', 'fa fa-user', Usuario::class);
        yield MenuItem::linkToRoute('Cargar Datos API', 'fa fa-cloud-download', 'app_cargar_datos_api');
        yield MenuItem::linkToCrud('Competiciones', 'fa fa-trophy', Competicion::class);
        yield MenuItem::linkToCrud('Categorías', 'fa fa-tags', Category::class);
    }

}
