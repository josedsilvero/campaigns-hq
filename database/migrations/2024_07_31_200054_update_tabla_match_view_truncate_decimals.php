<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
       CREATE OR REPLACE VIEW prod.v_tabla_match_campaign
AS SELECT f.campaign_id AS facebook_id,
    c.campaign_id AS crossroads_id,
    COALESCE(f.campaign_name, c.campaign_name) AS campaign_name,
    f.spend,
    f.purchase_value,
    c.revenue,
    c.lander_visitors,
    c.revenue_events,
    f.domain,
    c.revenue_domain_name,
    COALESCE(substring(substring(f.campaign_name, '-[A-Z]{3}-'::text), 2, 3), substring(substring(c.campaign_name, '_[A-Z]{3}'::text), 2, 3), 'CCH'::text) AS user_name,
    f.account_id,
    COALESCE(c.date, f.date_start) AS created_at,
    n.cpa,
    n.budget,
    n.observation,
    n.id AS note_id,
    c.revenue - f.spend AS net_revenue,
    trunc(((c.revenue - f.spend)*100)/nullif (f.spend,0),2) as net_revenue_perc,
    trunc(f.spend /nullif (f.purchase_value,0),3) as ecpa,
    round((c.revenue_events*100)/nullif(f.purchase_value,0),0) as conv_valid,
    round((c.revenue_events*100)/nullif(c.lander_visitors,0),0) as ctr_serp,
    trunc(c.revenue / nullif(c.revenue_events,0),4) as avg_rpc
   FROM v_facebook_campaign f
     FULL JOIN v_crossroads_campaign c ON lower(f.domain) = lower(c.revenue_domain_name::text)
     FULL JOIN campaign_notes n ON f.campaign_id = n.campaign_id
  ORDER BY c.campaign_id;
      ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('v_tabla_match_campaign');
    }
};
