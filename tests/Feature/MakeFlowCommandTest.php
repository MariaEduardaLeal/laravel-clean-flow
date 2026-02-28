<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

beforeEach(function () {
    //
});

it('creates the complete flow structure based on a name', function () {
    // MakeFlowCommand call
    $command = $this->artisan('make:flow TestingFlow');

    $command->expectsOutput('Iniciando a criação do fluxo arquitetural para: TestingFlow...')
        ->expectsOutput('☑️ Fluxo gerado com sucesso! Arquitetura isolada e pronta para o uso.')
        ->assertSuccessful();

});
