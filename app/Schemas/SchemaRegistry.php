<?php

namespace App\Schemas;

use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\This;

class SchemaRegistry
{
    private $raw;
    private $schema;

    public function __construct(string|object $schema)
    {
        $this->schema = $schema;
        $this->raw = $this->extractRaw($schema);
        
    }
    
    /**
     * Retorna la clase del schema solicitada.
     * Acepta: 'project', 'projects', App\Models\Project::class o una instancia.
     */
    public function resolve(): string
    {
        $this->assertSingular();
        ;
        $this->assertSchemaExists();
        
        $fqcn  = $this->getSchemaFqcn();
        return $fqcn;
    }

    /**
     * Extrae el identificador "raw" en minúsculas desde string|clase|objeto.
     */
    private function extractRaw(string|object $schema): string
    {
        if (is_object($schema) || (is_string($schema) && class_exists($schema))) {
            return Str::lower(class_basename($schema));
        }

        return Str::lower(trim((string) $schema));
    }

    /**
     * Valida que el nombre esté en singular.
     */
    private function assertSingular(): void
    {
        $singular = Str::singular($this->raw);
        if ($singular !== $this->raw) {
            $origLabel = is_object($this->schema) ? get_class($this->schema) : (string) $this->schema;
            abort(400, "El nombre del schema debe estar en singular. Recibido '{$origLabel}', usa '{$singular}'.");
        }
    }

    /**
     * Construye el FQCN del Schema (App\Schemas\Project\ProjectSchema).
     */
    private function getSchemaFqcn(): string
    {
        $class = $this->getClassName();
        return "App\\Schemas\\{$class}\\{$class}Schema";
    }

    /**
     * Convierte el raw a StudlyCase (Project).
     */
    private function getClassName(): string
    {
        return Str::studly($this->raw);
    }

    /**
     * Verifica que el schema exista.
     */
    private function assertSchemaExists(): void
    {
        if (!class_exists($this->getSchemaFqcn())) {
            abort(404, "Schema '{$this->raw}' not found.");
        }
    }
}