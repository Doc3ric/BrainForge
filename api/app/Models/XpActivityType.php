<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class XpActivityType extends Model {
    
    use HasUuids;

    protected $fillable = ['type_key', 'display_name', 'default_xp_amount'];
    
    public function newUniqueId(): string
    {
        return (string) \Symfony\Component\Uid\Uuid::v7();
    }

}
