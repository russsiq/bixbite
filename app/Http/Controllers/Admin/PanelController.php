<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Контроллер одностраничного приложения (панели управления).
 */
class PanelController extends BaseController
{
    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    /**
     * Шаблон одностраничного приложения.
     *
     * @var string
     */
    protected $template = 'panel.app';

    /**
     * Создать экземпляр контроллера.
     */
    public function __construct()
    {
    }

    /**
     * Обработать все маршруты одностраничного приложения.
     *
     * @return Renderable
     */
    public function __invoke(): Renderable
    {
        return view($this->template);
    }
}
