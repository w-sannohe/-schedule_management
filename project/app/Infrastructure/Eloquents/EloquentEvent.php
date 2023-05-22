<?php

namespace App\Infrastructure\Eloquents;

use Illuminate\Database\Eloquent\Model;

class EloquentEvent extends Model
{
    /**
     * ヒトサラのイベント情報テーブル
     *
     * @var string
     */
    protected $table = 'event';
    protected $primaryKey = 'event_id';
    protected $guarded = ['create_date'];
    // public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'create_date';
    const UPDATED_AT = 'update_date';
}
