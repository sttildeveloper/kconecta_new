<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('property', function (Blueprint $table) {
            $table->collation = 'utf8mb4_general_ci';
            $table->charset = 'utf8mb4';

            $table->string('reference')->nullable()->unique('reference');
            $table->integer('id', true);
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->string('title', 150)->nullable();
            $table->text('description')->nullable();
            $table->integer('meters_built')->nullable();
            $table->integer('useful_meters')->nullable();
            $table->integer('plot_meters')->nullable();
            $table->integer('m_long')->nullable();
            $table->integer('m_wide')->nullable();
            $table->string('land_size', 15)->nullable();
            $table->string('plant', 150)->nullable();
            $table->integer('number_of_plants')->nullable();
            $table->string('emissions_consumption')->nullable();
            $table->string('energy_consumption', 10)->nullable();
            $table->integer('sale_price')->nullable();
            $table->integer('rental_price')->nullable();
            $table->integer('garage_price')->nullable();
            $table->integer('community_expenses')->nullable();
            $table->string('year_of_construction', 10)->nullable();
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('rooms')->nullable();
            $table->integer('elevator')->nullable();
            $table->integer('appropriate_for_children')->nullable();
            $table->integer('pet_friendly')->nullable();
            $table->string('linear_meters_of_facade', 10)->nullable();
            $table->string('stays', 200)->nullable();
            $table->integer('number_of_shop_windows')->nullable();
            $table->integer('has_tenants')->nullable();
            $table->string('max_num_tenants', 15)->nullable();
            $table->integer('bank_owned_property')->nullable();
            $table->integer('guarantee')->nullable();
            $table->string('ibi', 15)->nullable();
            $table->string('mortgage_rate', 15)->nullable();
            $table->string('parking', 15)->nullable();
            $table->integer('interior_wheelchair')->nullable();
            $table->integer('outdoor_wheelchair')->nullable();
            $table->integer('wheelchair_accessible_elevator')->nullable();
            $table->string('locality')->nullable();
            $table->string('address', 200)->nullable();
            $table->string('number', 100)->nullable();
            $table->string('esc_block', 200)->nullable();
            $table->string('name_urbanization', 200)->nullable();
            $table->string('door', 200)->nullable();
            $table->string('zip_code', 10)->nullable();
            $table->string('close_to', 150)->nullable();
            $table->string('page_url')->nullable();
            $table->integer('state_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('type_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('features_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('province_id')->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('typology_id')->nullable();
            $table->integer('orientation_id')->nullable();
            $table->integer('type_heating_id')->nullable();
            $table->integer('emissions_rating_id')->nullable();
            $table->integer('energy_class_id')->nullable();
            $table->integer('state_conservation_id')->nullable();
            $table->integer('visibility_in_portals_id')->nullable();
            $table->integer('rental_type_id')->nullable();
            $table->integer('contact_option_id')->nullable();
            $table->integer('power_consumption_rating_id')->nullable();
            $table->integer('reason_for_sale_id')->nullable();
            $table->integer('plant_id')->nullable();
            $table->integer('door_id')->nullable();
            $table->integer('facade_id')->nullable();
            $table->integer('plaza_capacity_id')->nullable();
            $table->integer('type_of_terrain_id')->nullable();
            $table->integer('wheeled_access_id')->nullable();
            $table->integer('nearest_municipality_distance_id')->nullable();
            $table->integer('heating_fuel_id')->nullable();
            $table->integer('location_premises_id')->nullable();
            $table->integer('garage_price_category_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property');
    }
};
