<?php

namespace App\Schemas\Base;

abstract class BaseSchema
{
    /**
     * Campos base que TODOS los módulos deben tener.
     */
    abstract protected static function baseFields(): array;

    /**
     * Campos permitidos para personalizar (ej: required, label, etc).
     */
    public static function allowedOverrides(): array
    {
        $base = FieldDefinition::baseStructure();

        $keys = array_keys($base);

        return $keys;
    }

    /**
     * Retorna el schema completo (base + overrides).
     */
     public function getSchema(): array
    {
        return static::baseFields();
    }

    /**
     * Valida un JSON de schema enviado por el cliente.
     */
    public static function validate(array $schema): array
    {
        $base = static::baseFields();
        $allowed = static::allowedOverrides();

        foreach ($schema as $fieldName => $fieldData) {

            // --- Validación 1: el campo debe existir en la base
            if (!array_key_exists($fieldName, $base)) {
                throw new \Exception("El campo '{$fieldName}' no está permitido en este esquema.");
            }

            // --- Validación 2: revisar que solo modifiquen propiedades permitidas
            foreach ($fieldData as $prop => $value) {
                if (!in_array($prop, $allowed)) {
                    throw new \Exception("La propiedad '{$prop}' no está permitida en el campo '{$fieldName}'.");
                }
            }

            // --- Validación 3: si es select, debe tener options
            if (($fieldData['type'] ?? $base[$fieldName]['type']) === 'select') {
                if (!isset($fieldData['options']) || !is_array($fieldData['options'])) {
                    throw new \Exception("El campo '{$fieldName}' es select y requiere 'options'.");
                }
            }
        }

        return $schema;
    }

    /**
     * Limpia los valores nulos de un array (recursivo).
     */
    protected static function removeNullValues(array $data): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $clean = static::removeNullValues($value);
                if (!empty($clean)) {
                    $result[$key] = $clean;
                }
            } elseif ($value !== null) {
                $result[$key] = $value;
            }
        }

        return $result;
    }
        
}