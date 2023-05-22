<?php

namespace App\Infrastructure\Eloquents;

use Illuminate\Database\Eloquent\Model;

class EloquentTimetreeLogin extends Model
{
    /**
     * タイムツリーのユーザー情報テーブル
     *
     * @var string
     */
    protected $table = 'timetree_login';
    protected $primaryKey = 'timetree_login_id';
    protected $guarded = ['create_date'];
    // public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'create_date';
    const UPDATED_AT = 'update_date';
}
