<?php
namespace App\Controller;

use App\Entity\Folder;
use App\Service\FolderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/folder', name: 'app_folder_')]
class FolderController extends AbstractController
{

    public function __construct(
        private FolderService $service
    ) {
    }

    #[Route('/api/{id}', name: 'api_show', methods: ['GET'])]
    public function apiShow(Folder $folder): JsonResponse
    {
        return $this->json([]);
    }

    #[Route('/api/{id}', name: 'api_edit', methods: ['PUT'])]
    public function apiEdit(Folder $folder): JsonResponse
    {
        return $this->json([]);
    }

    #[Route('/api/{id}', name: 'api_delete', methods: ['DELETE'])]
    public function apiDelete(Folder $folder): JsonResponse
    {
        return $this->json([]);
    }

    #[Route('/api', name: 'api_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $response = $this->service->create($request);

        return $this->json(
            $response->data,
            $response->status,
            $response->headers,
            ['groups' => 'folder_show']
        );
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Folder $folder): Response
    {
        return $this->render('', compact('folder'));
    }

}