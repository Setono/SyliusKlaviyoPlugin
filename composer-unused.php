<?php

declare(strict_types=1);

use ComposerUnused\ComposerUnused\Configuration\Configuration;
use ComposerUnused\ComposerUnused\Configuration\NamedFilter;

return static function (Configuration $config): Configuration {
    return $config
        ->addNamedFilter(NamedFilter::fromString('symfony/http-client'))
        ->addNamedFilter(NamedFilter::fromString('setono/client-id-bundle'))
    ;
};
