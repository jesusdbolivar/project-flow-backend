<?php

namespace App\Http\Controllers;

use App\Schemas\SchemaRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SchemaController extends Controller
{
    /**
     * GET /api/schema?schema=project
     * Devuelve el schema final (base + overrides)
     */
    public function show(Request $request)
    {
        $schemaName = Str::singular(last($request->segments()));

        if (!$schemaName) {
            return response()->json(['error' => 'Missing schema parameter'], 400);
        }

        $schemaClass = SchemaRegistry::resolve($schemaName);

        $schema = new $schemaClass;

        return response()->json([
            'schema' => $schema->getSchema(),
        ]);
    }

    /**
     * PUT /api/schema?schema=project
     * Actualiza overrides para ese schema
     */
    public function update(Request $request)
    {
        $schemaName = Str::singular(last($request->segments()));

        if (!$schemaName) {
            return response()->json(['error' => 'Missing schema parameter'], 400);
        }

        $schemaClass = SchemaRegistry::resolve($schemaName);
        $schema = new $schemaClass;

        // TODO: Aquí irá la lógica para guardar overrides en BD,
        // por ahora devolvemos lo recibido:

        $validated = $schema->validate($request->all());

        return response()->json([
            'message' => 'Schema updated successfully',
            'received' => $validated
        ]);
    }
}
