<?php

namespace App\Infrastructure\Eloquents;

use Illuminate\Database\Eloquent\Model;

class EloquentMainType extends Model
{
    /**
     * ヒトサラのメインジャンル情報テーブル
     *
     * @var string
     */
    protected $table = 'main_type';
    protected $primaryKey = 'main_type_id';
    protected $guarded = ['create_date'];
    // public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'create_date';
    const UPDATED_AT = 'update_date';
}
