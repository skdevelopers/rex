<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeViewsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:views {name : The name of the resource}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create index, create, edit, and show views for a resource';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');

        $views = ['index', 'create', 'edit', 'show'];

        foreach ($views as $view) {
            $this->createView($name, $view);
        }

        $this->info("Views for {$name} created successfully.");

        return 0;
    }

    /**
     * Create a view file.
     *
     * @param string $name
     * @param string $view
     * @return void
     */
    protected function createView($name, $view)
    {
        $directory = resource_path("views/{$name}");

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $path = "{$directory}/{$view}.blade.php";

        if (File::exists($path)) {
            $this->error("View {$view} already exists for {$name}!");
        } else {
            $content = "@extends('layouts.vertical', ['title' => '', 'sub_title' => ''])\n\n@section('content')\n\n@endsection";
            File::put($path, $content);
            $this->info("Created: {$view}.blade.php");
        }
    }
}
