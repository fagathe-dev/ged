<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class AppLoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';
    private const TARGET_PATH = 'app_default';

    /**
     * @var ?User $user
     */
    private $user = null;
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private UserRepository $repository,
        private UserPasswordHasherInterface $hasher,
        private Security $security,
    ) {
    }

    public function supports(Request $request): bool
    {
        return $request->isMethod('POST') && $this->getLoginUrl($request) === $request->getPathInfo();
    }

    /**
     * authenticate
     *
     * @param  mixed $request
     * @return Passport
     */
    public function authenticate(Request $request): Passport
    {
        $username = $request->request->get('username', '');
        $this->user = $this->repository->findOneBy(['username' => $username]);

        $request->getSession()->set(Security::LAST_USERNAME, $username);
        $badges = [new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token'))];
        if ($request->request->get('remember_me')) {
            array_push($badges, (new RememberMeBadge())->enable());
        }

        if ($this->user instanceof User) {
            return new Passport(
                new UserBadge($username),
                new PasswordCredentials($request->request->get('password', '')),
                $badges
            );
        }

        throw new CustomUserMessageAuthenticationException('Identifiants incorrects');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // For example:
        return new RedirectResponse($this->urlGenerator->generate(self::TARGET_PATH));
        // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}