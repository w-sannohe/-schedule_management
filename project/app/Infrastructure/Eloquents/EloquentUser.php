<?php

namespace App\Infrastructure\Eloquents;

use Illuminate\Database\Eloquent\Model;

class EloquentUser extends Model
{
    /**
     * user情報テーブル
     *
     * @var string
     */
    protected $table = 'user';
    protected $primaryKey = 'user_id';
    protected $guarded = ['create_date'];
    // public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'create_date';
    const UPDATED_AT = 'update_date';
}
