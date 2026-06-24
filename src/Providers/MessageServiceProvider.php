<?php

declare(strict_types=1);

namespace Panelis\Message\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class MessageServiceProvider extends ServiceProvider
{
    private const string NAMESPACE = 'message';

    public function register(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        $this->loadTranslationsFrom(__DIR__.'/../../lang', self::NAMESPACE);

        $this->loadViewsFrom(__DIR__.'/../../resources/views', self::NAMESPACE);

        Route::middleware('web')
            ->group(__DIR__.'/../../routes/web.php');
    }
}
