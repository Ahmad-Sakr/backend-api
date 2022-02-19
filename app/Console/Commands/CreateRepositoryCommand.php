<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CreateRepositoryCommand extends GeneratorCommand
{
    protected $name = 'make:repo';

    protected $description = 'Generate new repository.';

    protected $type = 'Repository';

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     *
     * @throws FileNotFoundException
     */
    protected function buildClass($name)
    {
        if ($this->option('interface')) {
            $interface = Str::remove('Repository', Str::studly(class_basename($this->argument('name'))));
            return str_replace(
                '{{ interface }}',
                "{$interface}Interface",
                parent::buildClass($name)
            );
        }
        return parent::buildClass($name);
    }

    public function handle()
    {
        if (parent::handle() === false && ! $this->option('force')) {
            return false;
        }

        if ($this->option('interface')) {
            $this->createInterface();
        }
    }

    /**
     * Create an interface for the repository.
     *
     * @return void
     */
    protected function createInterface()
    {
        $interface = Str::remove('Repository', Str::studly(class_basename($this->argument('name'))));

        $this->call('make:interface', [
            'name' => "{$interface}Interface",
        ]);
    }

    protected function getStub()
    {
        return  base_path() . '/stubs/repository.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Repositories';
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the repository.']
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['interface', 'i', InputOption::VALUE_NONE, 'Create a new interface for the repository'],
        ];
    }
}
