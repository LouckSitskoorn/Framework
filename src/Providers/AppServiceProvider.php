<?php
  namespace SB\Providers;

  use Blade;
  use Illuminate\Support\ServiceProvider;
  use SB\Functions as sb;

  class AppServiceProvider
  extends ServiceProvider {
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
      Blade::directive('datetime', function($expression) {
        return "<?php echo with{$expression}->format('d F Y H:i'); ?>";
      });

      /*
       |--------------------------------------------------------------------------
       | Extend blade so we can define a variable
       | <code>
       | @define $variable = "whatever"
       | </code>
       |--------------------------------------------------------------------------
       */

      Blade::extend(function($value, $compiler) {
          //@switch
          $value = preg_replace('/(?<=\s)\|\s*@switch\s*\((.*)\)(\s*)@case\s*\((.*)\)(?=\s)/', '<?php switch($1){$2case $3: ?>', $value);
          $value = preg_replace('/(?<=\s)@switch\s*\((.*)\)(\s*)@case\s*\((.*)\)(?=\s)/', '<?php switch($1){$2case $3: ?>', $value);
          $value = preg_replace('/(?<=\s)@endswitch(?=\s*)/', '<?php } ?>'.PHP_EOL, $value);

          $value = preg_replace('/(?<=\s)@case\s*\((.*)\)(?=\s)/', '<?php case $1: ?>', $value);
          $value = preg_replace('/(?<=\s)@default(?=\s)/', '<?php default: ?>', $value);
          $value = preg_replace('/(?<=\s)@break(?=\s)/', '<?php break; ?>', $value);

          //@define
          $value = preg_replace('/\@define(.+)/', '<?php ${1}; ?>', $value);

          //@includeroot
          $value = preg_replace('/\@includeroot.*\((.+)\)/', '<?php echo PHP_EOL; include base_path(). "/" . SB\Functions\stripfirstslash(${1}); ?>', $value);

          return $value;
      });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
      //
    }
  }
