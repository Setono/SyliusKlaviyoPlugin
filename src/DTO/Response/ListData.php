<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO\Response;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

/**
 * Represents a list in the REST API: https://apidocs.klaviyo.com/reference/lists-segments#get-lists
 */
final class ListData extends FlexibleDataTransferObject
{
    public string $list_id;

    public string $list_name;
}
