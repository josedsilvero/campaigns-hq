<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TikTokCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'campaign_name',
        'primary_status',
        'account_id',
        'conversions',
        'cost_per_conversion',
        'cost',
        'ctr',
        'clicks',
        'cpc',
        'frequency',
        'currency'
    ];
}
