<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RetentionRun extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['command', 'dry_run', 'counts', 'started_at', 'finished_at'];

    protected function casts(): array
    {
        return [
            'dry_run' => 'boolean',
            'counts' => 'array',
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }
}
