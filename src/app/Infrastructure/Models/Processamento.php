<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Processamento extends Model
{
    use SoftDeletes;

    protected $table = 'processamentos';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'operacao_uuid',
        'hash_file',
    ];

    /**
     * Relacionamento: Processamento pertence a Operacao.
     */
    public function operacao()
    {
        return $this->belongsTo(Operacao::class, 'operacao_uuid', 'uuid');
    }

    /**
     * Relacionamento: Processamento possui muitos Status.
     */
    public function statuses()
    {
        return $this->hasMany(Status::class, 'processamento_uuid', 'uuid');
    }

    /**
     * Relacionamento: Processamento possui muitos Boletos.
     */
    public function boletos()
    {
        return $this->hasMany(Boleto::class, 'processamento_uuid', 'uuid');
    }
}
