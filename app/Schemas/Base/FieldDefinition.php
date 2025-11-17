<?php

namespace App\Schemas\Base;

class FieldDefinition
{
    public static function baseStructure(): array
    {
        return [
            'name' => null,
            'label' => null,
            'type'  => null,
            'required' => false,
            'visible' => true,
            'placeholder' => null,
            'layout' => [
                'row' => 1,
                'col' => 1,
            ],
            'options' => null, 
        ];
    }

    public static function make(
        string $name,
        string $label,
        string $type,
        bool $required = false,
        array $extra = []
    ): array {
        
        $field = self::baseStructure();

        $field['name'] = $name;
        $field['label'] = $label;
        $field['type'] = $type;
        $field['required'] = $required;

        return array_replace_recursive($field, $extra);
    }
}
