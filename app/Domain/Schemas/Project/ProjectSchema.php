<?php

namespace App\Domain\Schemas\Project;

use App\Domain\Schemas\Base\BaseSchema;
use App\Domain\Schemas\Base\FieldDefinition;

class ProjectSchema extends BaseSchema
{
    public static function baseFields(): array
    {
        return static::removeNullValues([
            'project_code' => FieldDefinition::make('project_code','Código del Proyecto', 'text', true),
            'project_name' => FieldDefinition::make('project_name','Nombre del Proyecto', 'text', true),
            'client'       => FieldDefinition::make('client','Cliente', 'text', false),
            'status'       => FieldDefinition::make('status','Estado', 'select', true, [
                'options' => ['Activo', 'Inactivo']
            ]),
            'start_date'   => FieldDefinition::make('start_date', 'Fecha Inicio', 'date', true),
            'end_date'     => FieldDefinition::make('end_date', 'Fecha Fin', 'date', false),
        ]);
    }
}
