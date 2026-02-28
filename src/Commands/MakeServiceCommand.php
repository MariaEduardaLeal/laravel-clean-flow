<?php

namespace EduardaLeal\CleanFlow\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeServiceCommand extends GeneratorCommand
{
    protected $signature = 'make:service {name}';
    protected $description = 'Cria uma nova classe de Service com as regras arquiteturais da GraficaJB';
    protected $type = 'Service';

    protected function getStub(): string
    {
        return __DIR__ . '/../stubs/service.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Services';
    }

    protected function getNameInput(): string
    {
        $input_name = trim($this->argument('name'));

        if (!Str::endsWith($input_name, 'Service')) {
            $input_name .= 'Service';
        }

        return $input_name;
    }

    /**
     * Sobrescreve a montagem da classe para injetar as variáveis em snake_case.
     */
    protected function buildClass($name): string
    {
        $stub = parent::buildClass($name);

        $class_name = class_basename($name);
        $base_name = str_replace('Service', '', $class_name);

        // Ex: Se baseName for "ProcessamentoPagamento", vira "processamento_pagamento"
        $base_variable = Str::snake($base_name);

        return str_replace(
            ['{{ baseName }}', '{{ baseVariable }}'],
            [$base_name, $base_variable],
            $stub
        );
    }
}
