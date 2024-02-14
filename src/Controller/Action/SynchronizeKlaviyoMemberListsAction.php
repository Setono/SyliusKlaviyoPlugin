<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Controller\Action;

use Psr\Log\LoggerInterface;
use Setono\SyliusKlaviyoPlugin\Synchronizer\ListSynchronizerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;

final class SynchronizeKlaviyoMemberListsAction
{
    private RouterInterface $router;

    private ListSynchronizerInterface $listSynchronizer;

    public function __construct(RouterInterface $router, LoggerInterface $logger, ListSynchronizerInterface $listSynchronizer)
    {
        $this->router = $router;

        $listSynchronizer->setLogger($logger);
        $this->listSynchronizer = $listSynchronizer;
    }

    public function __invoke(Request $request): RedirectResponse
    {
        $this->listSynchronizer->synchronize();
        $this->addFlash($request, 'success', 'setono_sylius_klaviyo.member_list.synchronized');

        return new RedirectResponse($this->getRedirectUrl($request));
    }

    private function getRedirectUrl(Request $request): string
    {
        if ($request->headers->has('referer')) {
            /** @var mixed $filtered */
            $filtered = filter_var($request->headers->get('referer'), \FILTER_SANITIZE_URL);

            if (is_string($filtered)) {
                return $filtered;
            }
        }

        return $this->router->generate('setono_sylius_klaviyo_admin_member_list_index');
    }

    /**
     * @param mixed $message
     */
    private function addFlash(Request $request, string $type, $message): void
    {
        $session = $request->getSession();
        if ($session instanceof Session) {
            $session->getFlashBag()->add($type, $message);
        }
    }
}
