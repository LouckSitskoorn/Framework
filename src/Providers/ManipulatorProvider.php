<?php
  namespace SB\Providers;

  use Illuminate\Support\ServiceProvider;

  class ManipulatorProvider
  extends ServiceProvider {
    /**
     * Register ManipulatorService class with the Laravel IoC container.
     *
     * @return void
     */
    public function register()
    {
      $this->app->bind('ManipulatorService', function() {
        return new \SB\Services\ManipulatorService();
      });

      $this->app->bind('manipulator', function() {
          return new \SB\Services\ManipulatorService();
        });
    }

  }