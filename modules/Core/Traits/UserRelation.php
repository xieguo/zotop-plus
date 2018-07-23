<?php
namespace Modules\Core\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

trait UserRelation
{
    /**
     * Boot the UserId trait for a model.
     *
     * @return void
     */
    public static function bootUserId()
    {
        static::updating(function ($model) {
            $model->user_id = \Auth::User()->id;
        });

        static::creating(function ($model) {
            $model->user_id = \Auth::User()->id;
        });

        static::addGlobalScope('user', function (Builder $builder) {
            $builder->with('user');
        });                
    }

    public function user()
    {
        return $this->belongsTo('Modules\Core\Models\User')->withDefault();
    }    
}