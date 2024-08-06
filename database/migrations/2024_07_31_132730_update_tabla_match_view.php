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
    n.observation
   FROM prod.v_facebook_campaign f
     FULL JOIN prod.v_crossroads_campaign c ON lower(f.domain) = lower(c.revenue_domain_name::text)
     FULL JOIN public.campaign_notes n ON f.campaign_id = n.campaign_id
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
