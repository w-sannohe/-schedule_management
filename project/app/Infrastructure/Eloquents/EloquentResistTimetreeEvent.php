<?php

namespace App\Infrastructure\Eloquents;

use Illuminate\Database\Eloquent\Model;

class EloquentResistTimetreeEvent extends Model
{
    /**
     * タイムツリーに登録しているイベント(スケジュール)情報テーブル
     *
     * @var string
     */
    protected $table = 'resist_timetree_event';
    protected $primaryKey = 'resist_id';
    protected $guarded = ['create_date'];
    // public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'create_date';
    const UPDATED_AT = 'update_date';
}
