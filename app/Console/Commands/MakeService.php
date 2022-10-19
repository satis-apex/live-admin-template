<?php
namespace App\Console\Commands;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;

// use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(name: 'make:service')]
class MakeService extends GeneratorCommand
{
    use CreatesMatchingTest;

    protected $name = 'make:service';

    protected $description = 'Create a new service class';

    protected $type = 'Service';

    protected function getStub()
    {
        return  base_path() . '/stubs/service.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Services';
    }

    protected function buildClass($name)
    {
        $replace = [];
        $replace = $this->buildServiceReplacements($replace);

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }

    protected function buildServiceReplacements(array $replace)
    {
        $serviceClass = Str::studly(class_basename($this->argument('name')));
        return array_merge($replace, [
            '{{ service }}' => $serviceClass,
            '{{service}}' => $serviceClass,
        ]);
    }
}
