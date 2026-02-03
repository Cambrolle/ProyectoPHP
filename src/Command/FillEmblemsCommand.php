<?php

namespace App\Command;

use App\Entity\Competicion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:fill-emblems',
    description: 'Busca logos en Wikipedia para las competiciones que no tienen.',
)]
class FillEmblemsCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private HttpClientInterface $httpClient
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $repo = $this->entityManager->getRepository(Competicion::class);

        // 1. Buscamos solo las que NO tienen emblema (null o vacío)
        $competiciones = $repo->createQueryBuilder('c')
            ->where('c.emblem IS NULL OR c.emblem = :empty')
            ->setParameter('empty', '')
            ->getQuery()
            ->getResult();

        $io->progressStart(count($competiciones));

        foreach ($competiciones as $comp) {
            try {
                $name = $comp->getName();
                $imageUrl = null;

                // PASO 1: Intento directo (el más rápido)
                $summaryUrl = "https://en.wikipedia.org/api/rest_v1/page/summary/" . rawurlencode($name);
                $response = $this->httpClient->request('GET', $summaryUrl);

                if ($response->getStatusCode() === 200) {
                    $data = $response->toArray();
                    $imageUrl = $data['originalimage']['source'] ?? null;
                }

                // PASO 2: Si el paso 1 falla, hacemos una búsqueda (Search)
                if (!$imageUrl) {
                    // Buscamos en la API de Wikipedia los artículos más relevantes
                    $searchUrl = "https://en.wikipedia.org/w/api.php?action=query&list=search&srsearch=" . rawurlencode($name . " football league") . "&format=json&origin=*";
                    $searchResponse = $this->httpClient->request('GET', $searchUrl)->toArray();

                    if (!empty($searchResponse['query']['search'])) {
                        // Cogemos el título del primer resultado (el más relevante)
                        $topTitle = $searchResponse['query']['search'][0]['title'];

                        // Pedimos la imagen de ese título específico
                        $imgUrl = "https://en.wikipedia.org/api/rest_v1/page/summary/" . rawurlencode($topTitle);
                        $imgData = $this->httpClient->request('GET', $imgUrl)->toArray();
                        $imageUrl = $imgData['originalimage']['source'] ?? null;
                    }
                }

                // Guardamos si hemos encontrado algo
                if ($imageUrl) {
                    $comp->setEmblem($imageUrl);
                    // Hacemos flush cada poco para no saturar la memoria si quieres,
                    // pero con 68 basta al final.
                }

            } catch (\Exception $e) {
                // Ignorar errores de conexión y seguir
            }

            $io->progressAdvance();
        }

        $this->entityManager->flush();
        $io->progressFinish();
        $io->success('¡Proceso completado! Se han intentado rellenar los logos faltantes.');

        return Command::SUCCESS;
    }
}
