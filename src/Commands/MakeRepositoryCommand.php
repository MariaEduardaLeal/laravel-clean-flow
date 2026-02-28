<?php

namespace GraficaJb\Arquitetura\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeRepositoryCommand extends GeneratorCommand
{
    protected $signature = 'make:repository {name}';
    protected $description = 'Cria uma nova classe de Repository focada em persistência';
    protected $type = 'Repository';

    protected function getStub(): string
    {
        return __DIR__ . '/../../stubs/repository.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Repositories';
    }

    protected function getNameInput(): string
    {
        $input_name = trim($this->argument('name'));

        if (!Str::endsWith($input_name, 'Repository')) {
            $input_name .= 'Repository';
        }

        return $input_name;
    }

    /**
     * Sobrescreve a montagem da classe para injetar as variáveis em snake_case.
     */
    protected function buildClass($name): string
    {
        // Pega o conteúdo original do stub (já com namespace e class preenchidos pelo Laravel)
        $stub = parent::buildClass($name);

        // Extrai o nome base (ex: Se $name for App\Repositories\UsuarioRepository, pega "Usuario")
        $class_name = class_basename($name);
        $base_name = str_replace('Repository', '', $class_name);
        
        // Aplica as regras de snake_case e pluralização
        $model_name = $base_name;
        $model_variable_plural = Str::plural(Str::snake($base_name)); // Ex: usuario -> usuarios

        // Substitui no texto do arquivo
        return str_replace(
            ['{{ modelName }}', '{{ modelVariablePlural }}'],
            [$model_name, $model_variable_plural],
            $stub
        );
    }
}