<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TransportUnitType; 

class TransportUnit extends Model {
    use HasFactory;
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
    

    /* 
    why did i even add this. When the above method 
    is already doing the same thing?
    both return the type the above one returns it 
    as type, and the bottom as type sting. 
    Aka Redundant code.
    */
    protected $appends = ['type_string'];
}