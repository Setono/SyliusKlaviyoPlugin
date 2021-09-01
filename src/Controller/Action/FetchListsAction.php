<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Controller\Action;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * This class will fetch lists from Klaviyo API and add the lists as MemberList entities
 */
final class FetchListsAction
{
    public function __invoke(Request $request): Response
    {
    }
}
