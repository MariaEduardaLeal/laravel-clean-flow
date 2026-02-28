<?php

use Illuminate\Support\Facades\File;

beforeEach(function () {
    // Limpa a pasta Repositories antes de cada teste
    $repositoriesPath = app_path('Repositories');
    if (File::exists($repositoriesPath)) {
        File::deleteDirectory($repositoriesPath);
    }
});

it('creates a repository successfully', function () {
    $command = $this->artisan('make:repository TestingFlow');

    $command->execute();
    $command->assertSuccessful();

    $repositoryPath = app_path('Repositories/TestingFlowRepository.php');

    // Verifica se o arquivo foi criado
    expect(File::exists($repositoryPath))->toBeTrue();

    // Verifica o conteúdo do arquivo gerado a partir do stub
    $content = File::get($repositoryPath);

    expect($content)
        ->toContain('namespace App\Repositories;')
        ->toContain('class TestingFlowRepository')
        ->toContain('use App\Models\TestingFlow;')
        ->toContain('public static function get_paginated_testing_flows(int $per_page = 15)')
        ->toContain('TestingFlow::paginate($per_page)');
});

it('appends Repository suffix if not provided', function () {
    // O nome fornecido não tem 'Repository' no final, o comando deve adicionar
    $command = $this->artisan('make:repository Order');

    $command->execute();
    $command->assertSuccessful();

    $repositoryPath = app_path('Repositories/OrderRepository.php');

    // Verifica se o arquivo foi criado com o sufixo
    expect(File::exists($repositoryPath))->toBeTrue();

    // Verifica o conteúdo do arquivo
    $content = File::get($repositoryPath);

    expect($content)
        ->toContain('class OrderRepository')
        ->toContain('use App\Models\Order;')
        ->toContain('public static function get_paginated_orders(int $per_page = 15)');
});
