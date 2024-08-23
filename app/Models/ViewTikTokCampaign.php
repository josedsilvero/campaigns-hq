<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ViewTikTokCampaign extends Model
{
    use HasFactory;
    public $table = "v_match_tiktok";

    protected static function booted(): void
    {
        static::creating(function (ViewTablaMatchCampaign $campaign) {
            $user_name = Auth::user()->user_name;
            $campaign->user_name = $user_name;
        });

        static::addGlobalScope(function (Builder $builder) {
            $user_name = Auth::user()->user_name;
            $builder->where('user_name', $user_name);
        });
    }
}
