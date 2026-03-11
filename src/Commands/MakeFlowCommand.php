<?php

namespace EduardaLeal\CleanFlow\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeFlowCommand extends Command
{
    protected $signature = 'make:flow {name}';
    protected $description = 'Gera a estrutura completa: Model, Repository, Service e Controller';

    /**
     * Executa o comando para gerar o fluxo completo da arquitetura.
     *
     * @return void
     */
    public function handle()
    {
        $base_name = trim($this->argument('name'));

        // Verifica se o nome está em snake_case (e.g. contendo underscore e apenas minúsculas)
        $is_snake_case = $base_name === Str::snake($base_name) && Str::contains($base_name, '_');

        if ($is_snake_case) {
            $choice = $this->choice(
                "O nome '{$base_name}' está em snake_case. Deseja gerar todos os arquivos (classes e migration) usando snake_case, ou manter snake_case APENAS para a migration e usar StudlyCase para as classes?",
                ['Apenas Migration (em snake_case) e Classes em StudlyCase', 'Tudo em snake_case (Arquitetura Completa)'],
                0
            );

            if ($choice === 'Apenas Migration (em snake_case) e Classes em StudlyCase') {
                $class_base_name = Str::studly($base_name);
                $migration_base_name = $base_name;
            } else {
                $class_base_name = $base_name;
                $migration_base_name = $base_name;
            }
        } else {
            $class_base_name = $base_name;
            $migration_base_name = $base_name;
        }

        $service_name = $class_base_name . 'Service';
        $repository_name = $class_base_name . 'Repository';
        $controller_name = $class_base_name . 'Controller';

        $this->info("Iniciando a criação do fluxo arquitetural...");

        // Gera nosso model customizado a partir do stub
        $this->generate_model($class_base_name, $migration_base_name);

        // Chama os nossos geradores customizados da biblioteca
        $this->call('make:repository', ['name' => $repository_name]);
        $this->call('make:service', ['name' => $service_name]);

        // Gera o nosso Controller customizado e interligado
        $this->generate_controller($controller_name, $service_name, $class_base_name);

        // Gera a migration baseada no stub customizado
        $this->generate_migration($migration_base_name);

        $this->info("☑️ Fluxo gerado com sucesso! Arquitetura isolada e pronta para o uso.");
    }

    /**
     * Lê o stub do controller, faz a substituição dos nomes em snake_case
     * e salva o arquivo final na pasta App\Http\Controllers.
     *
     * @param string $controller_name
     * @param string $service_name
     * @return void
     */
    private function generate_controller(string $controller_name, string $service_name, string $base_name): void
    {
        $stub_path = __DIR__ . '/../stubs/controller.stub';

        if (!file_exists($stub_path)) {
            $this->error("Stub do controller não encontrado.");
            return;
        }

        $stub_content = file_get_contents($stub_path);

        $base_variable = Str::snake($base_name);

        $stub_content = str_replace(
            ['{{ class }}', '{{ serviceName }}', '{{ baseVariable }}'],
            [$controller_name, $service_name, $base_variable],
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

    /**
     * Lê o stub de migration, faz a substituição do nome da tabela em snake_case plural
     * e salva o arquivo final na pasta database/migrations.
     *
     * @param string $base_name
     * @return void
     */
    private function generate_migration(string $base_name): void
    {
        $stub_path = __DIR__ . '/../stubs/migration.create.stub';

        if (!file_exists($stub_path)) {
            $this->error("Stub de migration não encontrado no caminho: {$stub_path}");
            return;
        }

        $stub_content = file_get_contents($stub_path);

        // Converte "User" para "users", "UserPost" para "user_posts"
        $table_name = Str::snake(Str::pluralStudly($base_name));

        $stub_content = str_replace(
            ['{{ table }}'],
            [$table_name],
            $stub_content
        );

        $date_prefix = date('Y_m_d_His');
        $migration_file_name = "{$date_prefix}_create_{$table_name}_table.php";
        $migration_path = database_path("migrations/{$migration_file_name}");

        // Cria a pasta migrations caso não exista
        if (!file_exists(database_path('migrations'))) {
            mkdir(database_path('migrations'), 0777, true);
        }

        file_put_contents($migration_path, $stub_content);
        $this->line("<info>Migration criada:</info> database/migrations/{$migration_file_name}");
    }

    /**
     * Lê o stub do model, substitui os nomes e gera o arquivo do Model.
     *
     * @param string $class_base_name
     * @param string $migration_base_name
     * @return void
     */
    private function generate_model(string $class_base_name, string $migration_base_name): void
    {
        $stub_path = __DIR__ . '/../stubs/model.stub';

        if (!file_exists($stub_path)) {
            $this->error("Stub do model não encontrado.");
            return;
        }

        $stub_content = file_get_contents($stub_path);

        $table_name = Str::snake(Str::pluralStudly($migration_base_name));

        $stub_content = str_replace(
            ['{{ namespace }}', '{{ class }}', '{{ table }}'],
            ['App\Models', $class_base_name, $table_name],
            $stub_content
        );

        $model_path = app_path("Models/{$class_base_name}.php");

        // Cria a pasta Models caso não exista
        if (!file_exists(app_path('Models'))) {
            mkdir(app_path('Models'), 0777, true);
        }

        file_put_contents($model_path, $stub_content);
        $this->line("<info>Model criado:</info> app/Models/{$class_base_name}.php");
    }
}
