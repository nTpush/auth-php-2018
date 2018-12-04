<?php

namespace App\Exceptions;
use App\Components\Traits\ResponseTrait;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Foundation\Testing\HttpException;

class Handler extends ExceptionHandler
{
    use ResponseTrait;
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

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $message = $exception->getMessage();
        if (method_exists($exception, 'errors')) {
            $errors = $exception->errors();
            $message = implode(',', array_first($errors));
            if ($request->ajax()) {
                return response()->json([
                    'code' => false,
                    'data' => [],
                    'tag'  => '表单验证错误',
                    'message' => $message
                ]);
            }
        }
        if($request->ajax() && $exception instanceof AuthenticationException) {

            return response()->json([
                'code' => false,
                'data' => [],
                'tag'  => 'null',
                'message' => '无效的token'
            ]);
        }
            if($message == "Too Many Attempts.") {
                return response()->json([
                    'code' => false,
                    'data' => [],
                    'tag'  => 'null',
                    'message' => '休息一下'
                ]);

            }
          return parent::render($request, $exception);
    }
}
