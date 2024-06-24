<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Collection;
use App\Models\General;
use App\Models\Message;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\Paginator as PaginationPaginator;
use Illuminate\Support\Facades\View;
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
        View::composer('components.public.footer', function ($view) {
            // Obtener los datos del footer
            $general = General::all(); // Suponiendo que tienes un modelo Footer y un método footerData() en él
            // Pasar los datos a la vista
            $view->with('general', $general);
        });

        View::composer('components.public.header', function ($view) {
            // Obtener los datos del footer
            $submenucategorias = Category::all(); // Suponiendo que tienes un modelo Footer y un método footerData() en él
            $submenucolecciones = Collection::all();
            $general = General::all();
            // Pasar los datos a la vista
            $view->with('submenucategorias', $submenucategorias)
                 ->with('submenucolecciones', $submenucolecciones)
                 ->with('general', $general);
        });

        View::composer('components.app.sidebar', function ($view) {
            // Obtener los datos del footer
            $mensajes = Message::where('is_read', '!=', 1 )->where('status', '!=', 0)
                                    ->where(function($query) {
                                        $query->where('source', '=', 'Inicio')
                                            ->orWhere('source', '=', 'Contacto');
                                    })->count(); 
            $mensajeslanding = Message::where('is_read', '!=', 1 )->where('status', '!=', 0)
                                        ->whereNotIn('source', ['Inicio', 'Contacto'])
                                        ->count();
            // Pasar los datos a la vista
            $view->with('mensajes', $mensajes)
                 ->with('mensajeslanding', $mensajeslanding);
        });

         PaginationPaginator::useTailwind();   
    }
}
