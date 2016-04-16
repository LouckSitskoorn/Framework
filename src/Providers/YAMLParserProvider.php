<?php
  namespace SB\Providers;

  use SB\Providers\AppServiceProvider;

  class YAMLParserProvider
  extends AppServiceProvider {
    /**
     * Register YAMLParserService class with the Laravel IoC container.
     *
     * @return void
     */
    public function register() {
      $this->app->bind('YAMLParserService', function() {
        return new \SB\Services\YAMLParserService();
      });

      $this->app->bind('yamlparser', function() {
        return new \SB\Services\YAMLParserService();
      });
    }

  }