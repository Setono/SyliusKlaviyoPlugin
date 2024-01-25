# Klaviyo plugin for Sylius

[![Latest Version][ico-version]][link-packagist]
[![Latest Unstable Version][ico-unstable-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-github-actions]][link-github-actions]
[![Code Coverage][ico-code-coverage]][link-code-coverage]

Use this plugin to integrate your store with [Klaviyo](https://www.klaviyo.com).

## Installation

### Step 1: Download the plugin

Open a command console, enter your project directory and execute the following command to download the latest stable version of this plugin:

```bash
$ composer require setono/sylius-klaviyo-plugin
```

### Step 2: Enable the plugin

Then, enable the plugin by adding the following to the list of registered plugins/bundles
in the `config/bundles.php` file of your project:

```php
<?php

return [
    // ...
    
    Setono\SyliusKlaviyoPlugin\SetonoSyliusKlaviyoPlugin::class => ['all' => true],
    
    // It is important to add plugin before the grid bundle
    Sylius\Bundle\GridBundle\SyliusGridBundle::class => ['all' => true],
        
    // ...
];
```

**NOTE** that you must instantiate the plugin before the grid bundle, else you will see an exception like
`You have requested a non-existent parameter "setono_sylius_klaviyo.model.member_list.class".`

### Step 3: Import routing

```yaml
# config/routes/setono_sylius_klaviyo.yaml
setono_sylius_klaviyo:
    resource: "@SetonoSyliusKlaviyoPlugin/Resources/config/routes.yaml"
```

If you don't use localized URLs, use this routing file instead: `@SetonoSyliusKlaviyoPlugin/Resources/config/routes_no_locale.yaml`

### Step 4: Configure plugin

```yaml
# config/packages/setono_sylius_klaviyo.yaml
imports:
    - { resource: "@SetonoSyliusKlaviyoPlugin/Resources/config/app/config.yaml" }

setono_sylius_klaviyo:
    credentials:
        public_token: "%env(KLAVIYO_PUBLIC_TOKEN)%"
        private_token: "%env(KLAVIYO_PRIVATE_TOKEN)%"
```

Then remember to set these environment variables: `KLAVIYO_PUBLIC_TOKEN` and `KLAVIYO_PRIVATE_TOKEN` with the respective tokens.

### Step 5: Update database schema

Use Doctrine migrations to create a migration file and update the database.

```bash
$ bin/console doctrine:migrations:diff
$ bin/console doctrine:migrations:migrate
```

### Step 6: Using asynchronous transport (optional, but recommended)

All commands in this plugin will extend the [CommandInterface](src/Message/Command/CommandInterface.php).
Therefore you can route all commands easily by adding this to your [Messenger config](https://symfony.com/doc/current/messenger.html#routing-messages-to-a-transport):

```yaml
# config/packages/messenger.yaml
framework:
    messenger:
        routing:
            # Route all command messages to the async transport
            # This presumes that you have already set up an 'async' transport
            # See docs on how to setup a transport like that: https://symfony.com/doc/current/messenger.html#transports-async-queued-messages
            'Setono\SyliusKlaviyoPlugin\Message\Command\CommandInterface': async
```

## Usage
After setup you want to associate channels with lists in Klaviyo. Go to `/admin/klaviyo/member-lists/`
and synchronize the lists. After that edit the lists and enable channels on the lists.

//todo:
Import lists using `setono:sylius-klaviyo:synchronize:lists` command


[ico-version]: https://poser.pugx.org/setono/sylius-klaviyo-plugin/v/stable
[ico-unstable-version]: https://poser.pugx.org/setono/sylius-klaviyo-plugin/v/unstable
[ico-license]: https://poser.pugx.org/setono/sylius-klaviyo-plugin/license
[ico-github-actions]: https://github.com/Setono/SyliusKlaviyoPlugin/workflows/build/badge.svg
[ico-code-coverage]: https://codecov.io/gh/Setono/SyliusKlaviyoPlugin/branch/master/graph/badge.svg

[link-packagist]: https://packagist.org/packages/setono/sylius-klaviyo-plugin
[link-github-actions]: https://github.com/Setono/SyliusKlaviyoPlugin/actions
[link-code-coverage]: https://codecov.io/gh/Setono/SyliusKlaviyoPlugin
