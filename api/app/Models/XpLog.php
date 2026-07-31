<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class XpLog extends Model {
    
    use HasUuids;

    protected $fillable = ['user_id', 'activity_type_id', 'amount', 'source_type', 'source_id'];
    
    public function newUniqueId(): string
    {
        return (string) \Symfony\Component\Uid\Uuid::v7();
    }

    public function source()
    {
        return $this->morphTo();
    }

}
