<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider{
    public function register()
    {
        $this->app->bind(
            'App\Interfaces\MaskapaiInt',
            'App\Repositories\MaskapaiRepository'
        );

        $this->app->bind(
            'App\Interfaces\HotelInt',
            'App\Repositories\HotelRepository'
        );

        $this->app->bind(
            'App\Interfaces\OperationalInt',
            'App\Repositories\OperationalRepository'
        );

        $this->app->bind(
            'App\Interfaces\TipeInt',
            'App\Repositories\TipePaketRepository'
        );
    }
}
