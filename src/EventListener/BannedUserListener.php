<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\SecurityBundle\Security;

class BannedUserListener
{
    private Security $security;
    private RouterInterface $router;

    public function __construct(Security $security, RouterInterface $router)
    {
        $this->security = $security;
        $this->router = $router;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $user = $this->security->getUser();

        if ($user && in_array('ROLE_BANNED', $user->getRoles(), true)) {
            $currentRoute = $event->getRequest()->attributes->get('_route');

            if (!in_array($currentRoute, ['app_home', 'app_banned'], true)) {
                $bannedUrl = $this->router->generate('app_banned');
                $event->setResponse(new RedirectResponse($bannedUrl));
            }
        }
    }
}
