<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AdminLayout extends Component
{
    /**
     * Migas de pan: [['name' => ..., 'route' => ...], ...]
     *
     * @var array
     */
    public $breadcrumbs;

    /**
     * Título de la pestaña del navegador.
     *
     * @var string|null
     */
    public $title;

    /**
     * Create a new component instance.
     *
     * @param  array  $breadcrumbs
     * @param  string|null  $title
     * @return void
     */
    public function __construct($breadcrumbs = [], $title = null)
    {
        $this->breadcrumbs = $breadcrumbs;
        // Si la vista no envía un título, se usa la última miga de pan
        $this->title = $title ?? (count($breadcrumbs) ? end($breadcrumbs)['name'] : null);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('layouts.admin');
    }
}
