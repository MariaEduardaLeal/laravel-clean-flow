<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

beforeEach(function () {
    //
});

it('creates the complete flow structure based on a name (StudlyCase)', function () {
    // MakeFlowCommand call
    $command = $this->artisan('make:flow TestingFlow');

    $command->expectsOutput('Iniciando a criação do fluxo arquitetural...')
        ->expectsOutput('☑️ Fluxo gerado com sucesso! Arquitetura isolada e pronta para o uso.')
        ->assertSuccessful();
});

it('prompts and converts classes to StudlyCase but keeps migration in snake_case when option 1 is chosen', function () {
    $command = $this->artisan('make:flow testing_flow');

    $command->expectsQuestion(
        "O nome 'testing_flow' está em snake_case. Deseja gerar todos os arquivos (classes e migration) usando snake_case, ou manter snake_case APENAS para a migration e usar StudlyCase para as classes?",
        'Apenas Migration (em snake_case) e Classes em StudlyCase'
    )
        ->expectsOutput('Iniciando a criação do fluxo arquitetural...')
        ->expectsOutput('☑️ Fluxo gerado com sucesso! Arquitetura isolada e pronta para o uso.')
        ->assertSuccessful();
});

it('prompts and generates complete flow strictly in snake_case when option 2 is chosen', function () {
    $command = $this->artisan('make:flow testing_flow');

    $command->expectsQuestion(
        "O nome 'testing_flow' está em snake_case. Deseja gerar todos os arquivos (classes e migration) usando snake_case, ou manter snake_case APENAS para a migration e usar StudlyCase para as classes?",
        'Tudo em snake_case (Arquitetura Completa)'
    )
        ->expectsOutput('Iniciando a criação do fluxo arquitetural...')
        ->expectsOutput('☑️ Fluxo gerado com sucesso! Arquitetura isolada e pronta para o uso.')
        ->assertSuccessful();
});
