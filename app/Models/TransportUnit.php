<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TransportUnitType; 

class TransportUnit extends Model {
    protected $fillable = ['name', 'type'];
    
    protected $casts = [
        'type' => TransportUnitType::class,
    ];

    public function scopeOfType($query, $type) {
        $value = $type instanceof TransportUnitType ? $type->value : $type;
        return $query->where('type', $value);
    }

    public function getTypeStringAttribute(): string {
        return $this->type->value;
    }
    
    protected $appends = ['type_string'];
}