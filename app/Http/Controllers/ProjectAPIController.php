<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Schemas\SchemaRegistry;

class ProjectAPIController extends Controller
{
    public function index()
    {
        return Project::all();
    }

    public function show(Project $project)
    {
        return $project;
    }

    public function store(Request $request)
    {
        // 1. Resolver schema dinámico
        $schemaClass = (new SchemaRegistry(Project::class))->resolve();
        
        $schema = new $schemaClass;

        // 2. Validar según schema dinámico
        $validatedData = $schema->validate($request->input('data'));

        // 3. Crear proyecto
        $project = Project::create([
            'data' => $validatedData,
        ]);

        return response()->json($project, 201);
    }

    public function update(Request $request, Project $project)
    {
        $schemaClass = (new SchemaRegistry(Project::class))->resolve();
        $schema = new $schemaClass;

        $validatedData = $schema->validate($request->input('data'));

        $project->update([
            'data' => $validatedData,
        ]);

        return $project;
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
