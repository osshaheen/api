<?php

namespace App\Providers;

use App\Http\Resources\Api\V1\Countries\indexMethodResource;
use App\Http\Resources\Api\V1\Countries\showMethodResource;
use App\Http\Resources\Api\V1\Countries\storeMethodResource;
use App\Http\Resources\Api\V1\Countries\updateMethodResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        indexMethodResource::withoutWrapping();
        showMethodResource::withoutWrapping();
        storeMethodResource::withoutWrapping();
        updateMethodResource::withoutWrapping();

        \App\Http\Resources\Api\V1\Cities\indexMethodResource::withoutWrapping();
        \App\Http\Resources\Api\V1\Cities\showMethodResource::withoutWrapping();
        \App\Http\Resources\Api\V1\Cities\storeMethodResource::withoutWrapping();
        \App\Http\Resources\Api\V1\Cities\updateMethodResource::withoutWrapping();

        \App\Http\Resources\Api\V1\newsletter\showMethodResource::withoutWrapping();
        \App\Http\Resources\Api\V1\newsletter\storeMethodResource::withoutWrapping();
        \App\Http\Resources\Api\V1\newsletter\updateMethodResource::withoutWrapping();

        \App\Http\Resources\Api\V1\billing_addresses\showMethodResource::withoutWrapping();
        \App\Http\Resources\Api\V1\billing_addresses\storeMethodResource::withoutWrapping();
        \App\Http\Resources\Api\V1\billing_addresses\updateMethodResource::withoutWrapping();

        \App\Http\Resources\Api\V1\categories\showMethodResource::withoutWrapping();
        \App\Http\Resources\Api\V1\categories\storeMethodResource::withoutWrapping();
        \App\Http\Resources\Api\V1\categories\updateMethodResource::withoutWrapping();

    }
}
