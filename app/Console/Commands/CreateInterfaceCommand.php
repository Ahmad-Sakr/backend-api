<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class CreateInterfaceCommand extends GeneratorCommand
{
    protected $name = 'make:interface';

    protected $description = 'Generate new interface.';

    protected $type = 'Interface';

    protected function getStub()
    {
        return  base_path() . '/stubs/interface.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Interfaces';
    }
}
