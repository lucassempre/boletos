<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Status extends Model
{
    use SoftDeletes;

    protected $table = 'status';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'processamento_uuid',
        'status',
        'status_descricao',
    ];

    /**
     * Relacionamento: Status pertence a Processamento.
     */
    public function processamento()
    {
        return $this->belongsTo(Processamento::class, 'processamento_uuid', 'uuid');
    }
}
