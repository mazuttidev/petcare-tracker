<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pesagem extends Model
{
    use HasFactory;

    // Nome explícito da tabela — o plural de "Pesagem" não é inferido corretamente pelo Laravel
    protected $table = 'pesagens';

    protected $fillable = [
        'pet_id',
        'data',
        'peso_kg',
        'fonte',
        'observacoes',
    ];

    protected $casts = [
        'data'    => 'date',
        'peso_kg' => 'decimal:2',
    ];

    // Fontes de registro aceitas
    public const FONTES = ['Manual', 'Balança', 'Clínica'];

    // Cada pesagem pertence a um pet
    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }
}
