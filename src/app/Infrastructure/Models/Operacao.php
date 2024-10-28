<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operacao extends Model
{
    use SoftDeletes;

    protected $table = 'operacoes';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'processamento_uuid',
        'file',
    ];

    /**
     * Relacionamento: Operacao possui muitos Processamentos.
     */
    public function processamentos()
    {
        return $this->hasMany(Processamento::class, 'operacao_uuid', 'uuid');
    }
}
