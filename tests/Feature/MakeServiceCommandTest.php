<?php

use Illuminate\Support\Facades\File;

beforeEach(function () {
    // Limpa a pasta Services antes de cada teste
    $servicesPath = app_path('Services');
    if (File::exists($servicesPath)) {
        File::deleteDirectory($servicesPath);
    }
});

it('creates a service successfully', function () {
    $command = $this->artisan('make:service TestingFlow');

    $command->execute();
    $command->assertSuccessful();

    $servicePath = app_path('Services/TestingFlowService.php');

    // Verifica se o arquivo foi criado
    expect(File::exists($servicePath))->toBeTrue();

    // Verifica o conteúdo do arquivo gerado a partir do stub
    $content = File::get($servicePath);

    expect($content)
        ->toContain('namespace App\Services;')
        ->toContain('class TestingFlowService')
        ->toContain('public static function process_testing_flow(array $testing_flow_data): void');
});

it('appends Service suffix if not provided', function () {
    // O nome fornecido não tem 'Service' no final, o comando deve adicionar
    $command = $this->artisan('make:service PaymentProcessing');

    $command->execute();
    $command->assertSuccessful();

    $servicePath = app_path('Services/PaymentProcessingService.php');

    // Verifica se o arquivo foi criado com o sufixo
    expect(File::exists($servicePath))->toBeTrue();

    // Verifica o conteúdo do arquivo
    $content = File::get($servicePath);

    expect($content)
        ->toContain('class PaymentProcessingService')
        ->toContain('public static function process_payment_processing(array $payment_processing_data): void');
});
