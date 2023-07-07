<?php
namespace App\Service;

use App\Entity\User;
use App\Utils\ServiceTrait;
use App\Repository\UserRepository;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\HttpFoundation\Session\Session;

final class UserService
{

    use ServiceTrait;

    /**
     * @var Session $session
     */
    private $session;

    public function __construct(
        private UserRepository $repository
    ) {
        $this->session = new Session;
    }

    /**
     * @param  mixed $user
     * @return bool
     */
    public function create(User $user): bool
    {
        $user->setCreatedAt($this->now());

        return $this->save($user);
    }

    /**
     * save
     *
     * @param  mixed $user
     * @return bool
     */
    public function save(User $user): bool
    {
        try {
            $this->repository->save($user, true);
            return true;
        } catch (ORMException $e) {
            $this->session->getFlashBag()->add('danger', 'Une erreur est survenue lors de l\'enregistrement de votre compte !');
            return false;
        }
    }
}