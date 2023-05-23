<?php

namespace App\Infrastructure\Eloquents;

use Illuminate\Database\Eloquent\Model;

class EloquentGeo extends Model
{
    /**
     * ヒトサラのGEO情報テーブル
     *
     * @var string
     */
    protected $table = 'geo';
    protected $primaryKey = 'geo_id';
    protected $guarded = ['create_date'];
    // public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'create_date';
    const UPDATED_AT = 'update_date';
}
