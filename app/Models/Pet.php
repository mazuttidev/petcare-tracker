<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'especie',
        'raca',
        'data_nascimento',
        'sexo',
        'castrado',
        'peso_atual',
        'cor',
        'microchip',
        'observacoes',
        'status',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'castrado'        => 'boolean',
        'peso_atual'      => 'decimal:2',
    ];

    // Valores aceitos pelo campo especie
    public const ESPECIES = ['Cão', 'Gato', 'Ave', 'Roedor', 'Réptil', 'Peixe', 'Outro'];

    // Valores aceitos pelo campo status
    public const STATUS = ['Ativo', 'Falecido', 'Doado', 'Perdido'];

    // Valores aceitos pelo campo sexo
    public const SEXOS = ['Macho', 'Fêmea'];

    // Pet pertence a um usuário dono
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Um pet pode ter muitas pesagens registradas
    public function pesagens(): HasMany
    {
        return $this->hasMany(Pesagem::class);
    }

    // Retorna a pesagem mais recente do pet
    public function ultimaPesagem(): HasMany
    {
        return $this->hasMany(Pesagem::class)->latestOfMany('data');
    }
}
