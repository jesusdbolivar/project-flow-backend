<?php

namespace App\Schemas;

use Illuminate\Support\Str;

class SchemaRegistry
{
    /**
     * Retorna la clase del schema solicitada.
     */
    public static function resolve(string $schema): string
    {
        // normalizar a StudlyCase → project → Project
        $class = Str::studly($schema);

        $path = "App\\Schemas\\{$class}\\{$class}Schema";

        if (!class_exists($path)) {
            abort(404, "Schema '{$schema}' not found.");
        }

        return $path;
    }
}
