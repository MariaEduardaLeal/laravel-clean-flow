<?php

namespace GraficaJb\Arquitetura\Providers;

use Illuminate\Support\ServiceProvider;
use GraficaJb\Arquitetura\Commands\MakeServiceCommand;
use GraficaJb\Arquitetura\Commands\MakeRepositoryCommand;
use GraficaJb\Arquitetura\Commands\MakeFlowCommand;

class ArchitectureServiceProvider extends ServiceProvider
{
    /**
     * Registra os serviços e dependências da biblioteca.
     *
     * @return void
     */
    public function register(): void
    {
        // 
    }

    /**
     * Inicializa os recursos da biblioteca, como os comandos Artisan.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeServiceCommand::class,
                MakeRepositoryCommand::class,
                MakeFlowCommand::class,
            ]);
        }
    }
}