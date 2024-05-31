<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO\Response;

/**
 * Represents a list in the REST API: https://developers.klaviyo.com/en/reference/get_lists
 */
final class ListData
{
    public string $list_id;

    public string $list_name;
}
