<?php

declare(strict_types=1);

namespace Panelis\Message\Plugins;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Panelis\Message\Panel\Resources\MessageResource;

class MessagePlugin implements Plugin
{
    public function getId(): string
    {
        return 'message';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            MessageResource::class,
        ]);
    }

    public function boot(Panel $panel): void {}
}
