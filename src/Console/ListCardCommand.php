<?php

namespace NovaListCard\Console;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;

class ListCardCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'nova:list-card';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new list card class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'ListCard';

    /**
     * Build the class with the given name.
     *
     * @param string $name
     *
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);

        $key = preg_replace('/[^a-zA-Z0-9]+/', '', $this->argument('name'));

        return str_replace('uri-key', Str::kebab($key), $stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/list-card.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Nova\Metrics';
    }
}
