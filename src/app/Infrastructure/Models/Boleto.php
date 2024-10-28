<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Boleto extends Model
{
    use SoftDeletes;

    protected $table = 'boletos';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'processamento_uuid',
        'name',
        'governmentId',
        'email',
        'debtAmount',
        'debtDueDate',
        'debtId',
        'status',
    ];

    /**
     * Relacionamento: Boleto pertence a Processamento.
     */
    public function processamento()
    {
        return $this->belongsTo(Processamento::class, 'processamento_uuid', 'uuid');
    }
}
