<?php

namespace App\Traits;
use App\Services\AuditLogger;


trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(fn($model) => AuditLogger::log(
            strtolower(class_basename($model)) . '.created', $model, ['name' => $model->project_title ?? $model->project->project_title ?? null]
        ));

        static::updated(fn($model) => AuditLogger::log(
            strtolower(class_basename($model)) . '.updated', $model, ['name' => $model->project_title ?? null]
        ));

        static::deleted(fn($model) => AuditLogger::log(
            strtolower(class_basename($model)) . '.deleted', $model, ['name' => $model->project_title ?? null]
        ));

        
    }
}
