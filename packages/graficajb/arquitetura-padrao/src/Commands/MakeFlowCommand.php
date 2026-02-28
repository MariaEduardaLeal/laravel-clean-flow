<?php

namespace GraficaJb\Arquitetura\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeFlowCommand extends Command
{
    protected $signature = 'make:flow {name}';
    protected $description = 'Gera a estrutura completa: Model, Repository, Service e Controller';

    public function handle()
    {
        $base_name = trim($this->argument('name'));
        
        $service_name = $base_name . 'Service';
        $repository_name = $base_name . 'Repository';
        $controller_name = $base_name . 'Controller';

        $this->info("Iniciando a criação do fluxo arquitetural para: {$base_name}...");

        // 1. Usa o gerador nativo do Laravel para criar o Model
        $this->call('make:model', ['name' => $base_name]);

        // 2. Chama os nossos geradores customizados da biblioteca
        $this->call('make:repository', ['name' => $repository_name]);
        $this->call('make:service', ['name' => $service_name]);

        // 3. Gera o nosso Controller customizado e interligado
        $this->generate_controller($controller_name, $service_name);

        $this->info("Fluxo gerado com sucesso! Arquitetura isolada e pronta para o uso.");
    }

    /**
     * Lê o stub do controller, faz a substituição dos nomes em snake_case
     * e salva o arquivo final na pasta App\Http\Controllers.
     *
     * @param string $controller_name
     * @param string $service_name
     * @return void
     */
    private function generate_controller(string $controller_name, string $service_name): void
    {
        $stub_path = __DIR__ . '/../../stubs/controller.stub';
        
        if (!file_exists($stub_path)) {
            $this->error("Stub do controller não encontrado.");
            return;
        }

        $stub_content = file_get_contents($stub_path);

        // Transforma o nome do serviço para a variável em snake_case (ex: user_service)
        $service_variable = Str::snake($service_name);
        
        $stub_content = str_replace(
            ['{{ class }}', '{{ serviceName }}', '{{ serviceVariable }}'],
            [$controller_name, $service_name, $service_variable],
            $stub_content
        );

        $controller_path = app_path("Http/Controllers/{$controller_name}.php");

        // Cria a pasta Controllers caso não exista
        if (!file_exists(app_path('Http/Controllers'))) {
            mkdir(app_path('Http/Controllers'), 0777, true);
        }

        file_put_contents($controller_path, $stub_content);
        $this->line("<info>Controller criado:</info> app/Http/Controllers/{$controller_name}.php");
    }
}