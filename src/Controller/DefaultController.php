<?php
namespace App\Controller;

use App\Entity\User;
use App\Service\FolderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    public function __construct(
        private FolderService $service
    ) {
    }

    #[Route('/', name: 'app_default')]
    public function index(): Response
    {
        $user = $this->getUser();
        if ($user instanceof User) {
            $folders = $user->getFolderUsers();
            return $this->render('default/index.html.twig', compact('folders'));
        }

        return $this->render('default/anonymous.html.twig');
    }
}