<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CrudGeneratorCommand extends Command
{
    protected $signature = 'make:crud {name}';
    protected $description = 'Generate CRUD for Lumen API';

    public function handle()
    {
        $name = $this->argument('name');
        $this->createController($name);
        $this->createRoute($name);
        $this->info('CRUD for ' . $name . ' generated successfully!');
    }

    protected function createController($name)
    {
        $controllerTemplate = "<?php
            
        namespace App\Http\Controllers;
        
        use App\Exceptions\MessageErrors;
        use App\Http\Core\PersonResponse;
        use App\Models\\$name;
        use Illuminate\Http\Request;
        
        class {$name}Controller extends Controller
        {
            protected \$MessageErrors;
            protected \$PersonResponse;
        
            public function __construct(MessageErrors \$MessageErrors, PersonResponse \$PersonResponse)
            {
                \$this->MessageErrors = \$MessageErrors;
                \$this->PersonResponse = \$PersonResponse;
            }
        
            public function index()
            {
                try {
                    \$names = $name::paginate();
                    return \$this->PersonResponse->returnResponsePaginate(\$names);
                } catch (\Throwable \$th) {
                    return \$this->MessageErrors->getMessageError(\$th);
                }
            }
        
            public function show(\$id)
            {
                try {
                    \$name = $name::find(\$id);
                    return \$this->PersonResponse->returnResponseArray([\$name]);
                } catch (\Throwable \$th) {
                    return \$this->MessageErrors->getMessageError(\$th);
                }
            }
        
            public function store(Request \$request)
            {
                try {
                    \$name = $name::create(\$request->all());
                    return response()->json([\"message\" => \"Registro criado com sucesso\", \"data\" => env(\"APP_DEBUG\") ? \$name : \"\"], 200);
                } catch (\Throwable \$th) {
                    return \$this->MessageErrors->getMessageError(\$th);
                }
            }
        
            public function update(Request \$request, \$id)
            {
                try {
                    \$name = $name::find(\$id);
                    \$name->update(\$request->all());
                    return response()->json([\"message\" => \"Registro atualizado com sucesso\", \"data\" => env(\"APP_DEBUG\") ? \$name : \"\"], 200);
                } catch (\Throwable \$th) {
                    return \$this->MessageErrors->getMessageError(\$th);
                }
            }
        
            public function destroy(\$id)
            {
                try {
                    \$name = $name::find(\$id);
                    \$name->delete(); // Use delete() em vez de destroy()
                    return response()->json([\"message\" => \"Registro excluído com sucesso\", \"data\" => env(\"APP_DEBUG\") ? \$name : \"\"], 200);
                } catch (\Throwable \$th) {
                    return \$this->MessageErrors->getMessageError(\$th);
                }
            }
        }
        ";
        File::put(app_path() . "/Http/Controllers/{$name}Controller.php", $controllerTemplate);
    }

    protected function createRoute($name)
    {
        $routeTemplate = "\n\$router->get('/$name', '{$name}Controller@index');\n\$router->get('/$name/{id}', '{$name}Controller@show');\n\$router->post('/$name', '{$name}Controller@store');\n\$router->put('/$name/{id}', '{$name}Controller@update');\n\$router->delete('/$name/{id}', '{$name}Controller@destroy');\n";
        File::append(base_path() . '/routes/api.php', $routeTemplate);
    }
}
