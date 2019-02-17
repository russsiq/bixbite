<?php

namespace BBCMS\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use \Symfony\Component\HttpKernel\Exception\HttpException as HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    // /**
    //  * Report or log an exception.
    //  *
    //  * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
    //  *
    //  * @param  \Exception  $e
    //  * @return void
    //  */
    // public function report(Exception $e)
    // {
    //     parent::report($e);
    // }
    //
    // /**
    //  * Render an exception into an HTTP response.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  \Exception  $e
    //  * @return \Illuminate\Http\Response
    //  */
    // public function render($request, \Exception $e)
    // {
    //     if ($e instanceof ModelNotFoundException) {
    //         // Методы findOrFail() и firstOrFail()
    //         // Ваша логика для ненайденной модели...
    //     }
    //     if ($this->isHttpException($e)) {
    //         return $this->renderHttpException($e);
    //     }
    //
    //     return parent::render($request, $e);
    // }
    //
    // /**
    //  * Render the given HttpException.
    //  *
    //  * @param  \Symfony\Component\HttpKernel\Exception\HttpException  $e
    //  * @return \Illuminate\Http\Response
    //  */
    // protected function renderHttpException(HttpException $e)
    // {
    //     if (view()->exists('errors')) {
    //         return response()->view('errors', [
    //         'title' => $e->getStatusCode(),
    //         'message' => $e->getMessage()
    //         ],
    //         $e->getStatusCode());
    //     }
    //
    //     return parent::renderHttpException($e);
    // }
}
