<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;


use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\Blade;

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
		//
		
		Schema::defaultStringLength(191);
		
		// Cashier::useCurrency('eur', '€');
		// Carbon::setLocale(config('app.locale', 'fr'));
		Carbon::setLocale('fr');
		
		setlocale(LC_ALL, 'fr_FR.UTF-8');
		setlocale(LC_MONETARY, 'fr_FR.UTF-8');
		setlocale(LC_TIME, 'fr_FR.UTF-8');
		
		
		
		
		
		Blade::directive('relativeInclude', function ($args) {
			$args = Blade::stripParentheses($args);
			
			$viewBasePath = Blade::getPath();
			foreach ($this->app['config']['view.paths'] as $path) {
				if (substr($viewBasePath,0,strlen($path)) === $path) {
					$viewBasePath = substr($viewBasePath,strlen($path));
					break;
				}
			}
			
			$viewBasePath = dirname(trim($viewBasePath,'\/'));
			$args = substr_replace($args, $viewBasePath.'.', 1, 0);
			return "<?php echo \$__env->make({$args}, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>";
		});
		
		
		
		
		
		
		
		// SQL debug
		
		\Illuminate\Database\Query\Builder::macro('toRawSql', function(){
			return array_reduce($this->getBindings(), function($sql, $binding){
				return preg_replace('/\?/', is_numeric($binding) ? $binding : "'".$binding."'" , $sql, 1);
			}, $this->toSql());
		});
		
		\Illuminate\Database\Eloquent\Builder::macro('toRawSql', function(){
			return ($this->getQuery()->toRawSql());
		});
		
	}
}
