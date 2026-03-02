<?php

namespace EduardaLeal\CleanFlow\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;

class MakeViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:view {name} {--vue} {--component} {--template} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cria um novo arquivo de view (Blade ou Vue) para o Laravel e Inertia.js';

    /**
     * @var Filesystem
     */
    protected $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = trim($this->argument('name'));
        $isVue = $this->option('vue');
        
        $path = $this->getViewPath($name, $isVue);
        $stub = $this->getStubContent($isVue);
        
        $stubContent = str_replace('{{ name }}', class_basename($name), $stub);

        if ($this->files->exists($path) && !$this->option('force')) {
            $this->error("View {$path} já existe!");
            return false;
        }

        $this->makeDirectory($path);
        
        $this->files->put($path, $stubContent);
        
        $viewType = $isVue ? 'Vue' : 'Blade';
        $this->info("{$viewType} view criada com sucesso: {$path}");
        
        return true;
    }

    /**
     * Obtem o caminho onde a view deve ser criada com base nas opções e estrutura de pastas.
     */
    protected function getViewPath(string $name, bool $isVue): string
    {
        // Se houver subdiretorios, ex: make:view Profile/Edit --vue
        $namePath = str_replace('\\', '/', $name);
        
        if ($isVue) {
            $basePath = resource_path('js');
            $extension = '.vue';
            
            if ($this->option('component')) {
                $dir = 'Components';
            } elseif ($this->option('template')) {
                $dir = 'Templates';
            } else {
                $dir = 'Pages';
            }
        } else {
            $basePath = resource_path('views');
            $extension = '.blade.php';
            // Converte nome para snake-ish / kebab path ou mantem case?
            // Laravel views costumam ser snake ou kebab case ou camelCase dependendo do padrão.
            // Para Views, vamos manter a lógica do nome como passado. 
            // O usuário controla se é `profile.edit` ou `Profile/Edit`
            $namePath = str_replace('.', '/', $namePath);
            
            if ($this->option('component')) {
                $dir = 'components';
            } elseif ($this->option('template')) {
                $dir = 'templates';
            } else {
                $dir = '';
            }
        }
        
        // Concatenação condicional para lidar com base path sem $dir quando for Blade puro
        $path = rtrim($basePath . '/' . $dir . '/' . $namePath, '/');
        
        return $path . $extension;
    }

    /**
     * Cria o diretorio caso nao exista
     */
    protected function makeDirectory(string $path)
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }
    }

    /**
     * Obtem o conteudo do stub de acordo com a extensão da view
     */
    protected function getStubContent(bool $isVue): string
    {
        $stubFile = $isVue ? 'view.vue.stub' : 'view.blade.stub';
        return $this->files->get(__DIR__.'/../stubs/'.$stubFile);
    }
}
