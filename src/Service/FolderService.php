<?php
namespace App\Service;

use App\Entity\Folder;
use App\Entity\FolderUser;
use App\Entity\User;
use Cocur\Slugify\Slugify;
use App\Utils\ServiceTrait;
use App\Repository\FolderRepository;
use App\Utils\Enum\Colors;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class FolderService
{

    use ServiceTrait;

    /**
     * @var Session $session
     */
    private $session;

    /**
     * @var Slugify $slugify
     */
    private $slugify;

    /**
     * @var ?User $user
     */
    private $user;

    public function __construct(
        private FolderRepository $repository,
        private SerializerInterface $serializer,
        private Security $security,
        private ValidatorInterface $validator,
        private UrlGeneratorInterface $router,
    ) {
        $this->session = new Session;
        $this->slugify = new Slugify;
        $this->user = $this->security->getUser();
    }

    /**
     * @param  mixed $user
     * @return object
     */
    public function create(Request $request): object
    {
        $data = $request->getContent();
        $folder = $this->serializer->deserialize($data, Folder::class, 'json');
        $folder
            ->setCreatedAt($this->now())
            ->setSlug($folder->getName())
        ;

        $errors = $this->validator->validate($folder);

        if (count($errors) > 0) {
            return $this->sendViolations($errors);
        }

        if ($this->user instanceof User) {
            $folder->addUser(
                (new FolderUser)
                    ->setColor(Colors::PRIMARY)
                    ->setUser($this->user)
                    ->setRole(FolderUser::ROLE_ADMIN)
            );
        }

        $this->save($folder);
        return $this->sendJson($folder, Response::HTTP_CREATED, [
            'Delete-Route' => $this->router->generate('app_folder_api_delete', ['id' => $folder->getId()]),
            'Edit-Route' => $this->router->generate('app_folder_api_edit', ['id' => $folder->getId()]),
            'Show-Route' => $this->router->generate('app_folder_show', ['id' => $folder->getId()]),
            'Api-Show-Route' => $this->router->generate('app_folder_show', ['id' => $folder->getId()]),
        ]);
    }

    /**
     * save
     *
     * @param  mixed $folder
     * @return bool
     */
    public function save(Folder $folder): bool
    {
        try {
            $this->repository->save($folder, true);
            return true;
        } catch (ORMException $e) {
            $this->session->getFlashBag()->add('danger', 'Une erreur est survenue lors de l\'enregistrement du dossier !');
            return false;
        }
    }

}