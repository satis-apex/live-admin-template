<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Nwidart\Modules\Facades\Module;
#[AsCommand(name: 'make:module')]
class MakeModuleCommand extends Command
{
    protected $name = 'make:module';
    protected $description = 'Create starter CRUD module';
    protected $type = 'Module';
    public function handle()
    {
        $hasModel = $this->option('model');
        $hasController = $this->option('controller');
        $this->container['name'] = ucwords($this->argument('name'));
        if ($hasController) {
            $this->container['controller'] = ucwords($this->argument('controller'));
        } else {
            $this->container['controller'] = ucwords($this->argument('name'));
        }
        //check If Module Exists.
        $targetPath = base_path('Modules/' . $this->container['name']);
        $filesystem = new SymfonyFilesystem();
        if ($filesystem->exists($targetPath)) {
            $this->info('Duplicate Module : ' . $this->container['name'] . '.');
            return false;
        }
        $this->container['model'] = ucwords(Str::singular($this->container['controller']));
        $this->comment('Success!');
        $this->generate();
        $this->info($this->container['name'] . ' module installed successfully.');
        if ($this->option('enable')) {
            Module::enable($this->container['name']);
            $this->info($this->container['name'] . ' module enabled successfully.');
        } else {
            $this->comment('To enable module run command php artisan module:enable ' . $this->container['name']);
        }
    }
    protected function generate()
    {
        $Module = $this->container['name'];
        $model = $this->container['model'];
        $controller = $this->container['controller'];
        $module = strtolower($Module);
        $Model = $model;
        $targetPath = base_path('Modules/' . $Module);
        //copy folders
        $this->copy(base_path('stubs/base-module/Config'), $targetPath . '/Config');
        $this->copy(base_path('stubs/base-module/Http'), $targetPath . '/Http');
        $this->copy(base_path('stubs/base-module/Console'), $targetPath . '/Console');
        $this->copy(base_path('stubs/base-module/Providers'), $targetPath . '/Providers');
        $this->copy(base_path('stubs/base-module/Resources'), $targetPath . '/Resources');
        $this->copy(base_path('stubs/base-module/Services'), $targetPath . '/Services');
        $this->copy(base_path('stubs/base-module/Routes'), $targetPath . '/Routes');
        $this->copy(base_path('stubs/base-module/.gitignore'), $targetPath . '/.gitignore', 'file');
        $this->copy(base_path('stubs/base-module/composer.json'), $targetPath . '/composer.json', 'file');
        $this->copy(base_path('stubs/base-module/module.json'), $targetPath . '/module.json', 'file');
        $this->copy(base_path('stubs/base-module/package.json'), $targetPath . '/package.json', 'file');
        $this->copy(base_path('stubs/base-module/vite.config.js'), $targetPath . '/vite.config.js', 'file');
        //replace contents
        $this->replaceInFile($targetPath . '/Config/config.php');
        $this->replaceInFile($targetPath . '/Http/Controllers/ModuleController.php');
        $this->replaceInFile($targetPath . '/Services/ModuleService.php');
        $this->replaceInFile($targetPath . '/Providers/ModuleServiceProvider.php');
        $this->replaceInFile($targetPath . '/Providers/RouteServiceProvider.php');
        $this->replaceInFile($targetPath . '/Routes/web.php');
        $this->replaceInFile($targetPath . '/Routes/api.php');
        $this->replaceInFile($targetPath . '/composer.json');
        $this->replaceInFile($targetPath . '/module.json');
        $this->replaceInFile($targetPath . '/vite.config.js');
        $this->replaceInFile($targetPath . '/Resources/assets/js/Pages/SubModule/Index.vue');
        $this->replaceInFile($targetPath . '/Resources/assets/js/Pages/SubModule/Components/AddEditForm.vue');
        $this->replaceInFile($targetPath . '/Resources/assets/js/Pages/SubModule/Components/AddByExcelForm.vue');
        $this->replaceInFile($targetPath . '/Resources/assets/js/Pages/SubModule/Components/ViewForm.vue');
        //rename
        $this->rename($targetPath . '/Http/Controllers/ModuleController.php', $targetPath . '/Http/Controllers/' . $controller . 'Controller.php');
        $this->rename($targetPath . '/Services/ModuleService.php', $targetPath . '/Services/' . $controller . 'Service.php');
        $this->rename($targetPath . '/Providers/ModuleServiceProvider.php', $targetPath . '/Providers/' . $Module . 'ServiceProvider.php');
        $this->rename($targetPath . '/Resources/assets/js/Pages/SubModule', $targetPath . '/Resources/assets/js/Pages/' . $controller);
        if ($this->option('model')) {
            //copy folders
            $this->copy(base_path('stubs/base-module/Models'), $targetPath . '/Models');
            $this->copy(base_path('stubs/base-module/Database'), $targetPath . '/Database');
            //replace contents
            $this->replaceInFile($targetPath . '/Database/Factories/ModelFactory.php');
            $this->replaceInFile($targetPath . '/Database/Migrations/create_module_table.php');
            $this->replaceInFile($targetPath . '/Database/Seeders/ModelDatabaseSeeder.php');
            $this->replaceInFile($targetPath . '/Database/Seeders/ModelTableSeeder.php');
            $this->replaceInFile($targetPath . '/Models/Model.php');
            //rename
            $this->rename($targetPath . '/Database/Factories/ModelFactory.php', $targetPath . '/Database/Factories/' . $Model . 'Factory.php');
            $this->rename($targetPath . '/Database/migrations/create_module_table.php', $targetPath . '/Database/migrations/create_' . Str::snake($controller) . 's_table.php', 'migration');
            $this->rename($targetPath . '/Database/Seeders/ModelDatabaseSeeder.php', $targetPath . '/Database/Seeders/' . $Module . 'DatabaseSeeder.php');
            $this->rename($targetPath . '/Database/Seeders/ModelTableSeeder.php', $targetPath . '/Database/Seeders/' . $controller . 'Seeder.php');
            $this->rename($targetPath . '/Models/Model.php', $targetPath . '/Models/' . $Model . '.php');
        }
    }
    protected function rename($path, $target, $type = null)
    {
        $filesystem = new SymfonyFilesystem();
        if ($filesystem->exists($path)) {
            if ($type == 'migration') {
                $timestamp = date('Y_m_d_his_');
                $target = str_replace('create', $timestamp . 'create', $target);
                $filesystem->rename($path, $target, true);
                $this->replaceInFile($target);
            } else {
                $filesystem->rename($path, $target, true);
            }
        }
    }
    protected function copy($path, $target, $type = 'directory')
    {
        $filesystem = new SymfonyFilesystem();
        if ($filesystem->exists($path)) {
            if ($type == 'file') {
                $filesystem->copy($path, $target);
            } else {
                $filesystem->mirror($path, $target);
            }
        }
    }
    protected function replaceInFile($path)
    {
        $name = $this->container['name'];
        $model = $this->container['model'];
        $controller = $this->container['controller'];
        $types = [
            // '{module_}' => Str::snake($name),
            // '{module-}' => Str::kebab($name),
            '{Module}' => $name,
            '{module}' => strtolower($name),
            '{Model}' => $model,
            '{model}' => strtolower($model),
            '{Model_}' => Str::snake($model),
            '{Controller}' => $controller,
            '{Controller-}' => Str::kebab($controller),
            '{routeName}' => Str::camel($controller),
        ];
        foreach ($types as $key => $value) {
            if (file_exists($path)) {
                file_put_contents($path, str_replace($key, $value, file_get_contents($path)));
            }
        }
    }
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name and root of the module.'],
            ['controller', InputArgument::OPTIONAL, 'The controller and other class name for the module.']
        ];
    }
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_NONE, 'Generate a resource controller for the given model'],
            ['enable', 'e', InputOption::VALUE_NONE, 'Enable module after creation'],
            ['controller', 'c', InputOption::VALUE_NONE, 'The controller and other class name for the module.'],
        ];
    }
}
