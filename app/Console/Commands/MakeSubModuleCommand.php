<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Nwidart\Modules\Facades\Module;
#[AsCommand(name:'make:subModule')]
class MakeSubModuleCommand extends Command
{
    protected $name = 'make:subModule';
    protected $description = 'Create starter CRUD sub module';
    protected $type = 'Module';
    public function handle()
    {
        $hasModel = $this->option('model');
        $this->container['name'] = ucwords($this->argument('name'));
        $this->container['controller'] = ucwords($this->argument('controller'));
        //check If Module Exists.
        $targetPath = base_path('Modules/' . $this->container['name']);
        $filesystem = new SymfonyFilesystem();
        if ($filesystem->exists($targetPath)) {
            $this->container['model'] = ucwords(Str::singular($this->container['controller']));
            $this->comment('Success!');
            $this->generate();
            $this->info($this->container['controller'] . ' sub module installed successfully.');
            return;
        }
        $this->info('Module not found: ' . $this->container['name'] . '.');
        return false;
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
        $this->copy(base_path('stubs/base-module/Routes/web.php'), $targetPath . '/Routes/web' . $controller . '.php', 'file');
        $this->copy(base_path('stubs/base-module/Services/ModuleService.php'), $targetPath . '/Services/' . $controller . 'Service.php', 'file');
        $this->copy(base_path('stubs/base-module/Http/Controllers/ModuleController.php'), $targetPath . '/Http/Controllers/' . $controller . 'Controller.php', 'file');
        $this->copy(base_path('stubs/base-module/Resources/assets/js/Pages/SubModule'), $targetPath . '/Resources/assets/js/Pages/SubModule');
        $this->replaceInFile($targetPath . '/Routes/web' . $controller . '.php');
        $this->replaceInFile($targetPath . '/Services/' . $controller . 'Service.php');
        $this->replaceInFile($targetPath . '/Http/Controllers/' . $controller . 'Controller.php');
        $this->replaceInFile($targetPath . '/Resources/assets/js/Pages/SubModule/Index.vue');
        $this->replaceInFile($targetPath . '/Resources/assets/js/Pages/SubModule/Components/AddEditForm.vue');
        $this->replaceInFile($targetPath . '/Resources/assets/js/Pages/SubModule/Components/AddByExcelForm.vue');
        $this->replaceInFile($targetPath . '/Resources/assets/js/Pages/SubModule/Components/ViewForm.vue');
        //rename
        $this->rename($targetPath . '/Resources/assets/js/Pages/SubModule', $targetPath . '/Resources/assets/js/Pages/' . $controller);
        //add sub module generated route to web.php
        $file_name = $targetPath . '/Routes/web.php';
        $string = "require module_path('{$Module}' ,'Routes/web{$controller}.php');
        ";
        File::append($file_name, $string, null);
        if ($this->option('model')) {
            //copy files
            $this->copy(base_path('stubs/base-module/Models/Model.php'), $targetPath . '/Models/' . $Model . '.php', 'file');
            $this->copy(base_path('stubs/base-module/Database/Factories/ModelFactory.php'), $targetPath . '/Database/Factories/' . $Model . 'Factory.php', 'file');
            $this->copy(base_path('stubs/base-module/Database/Migrations/create_module_table.php'), $targetPath . '/Database/Migrations/create_module_table.php', 'file');
            $this->copy(base_path('stubs/base-module/Database/Seeders/ModelTableSeeder.php'), $targetPath . '/Database/Seeders/' . $controller . 'Seeder.php', 'file');
            //replace contents
            $this->replaceInFile($targetPath . '/Models/' . $Model . '.php');
            $this->replaceInFile($targetPath . '/Database/Factories/' . $Model . 'Factory.php');
            $this->replaceInFile($targetPath . '/Database/Seeders/' . $controller . 'Seeder.php');
            $this->replaceInFile($targetPath . '/Database/Migrations/create_module_table.php');
            //rename
            $this->rename($targetPath . '/Database/migrations/create_module_table.php', $targetPath . '/Database/migrations/create_' . Str::snake($controller) . 's_table.php', 'migration');
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
            ['controller', InputArgument::REQUIRED, 'The controller and other class name for the module.']
        ];
    }
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_NONE, 'Generate a resource controller for the given model'],
            ['controller', 'c', InputOption::VALUE_NONE, 'The controller and other class name for the module.'],
        ];
    }
}
