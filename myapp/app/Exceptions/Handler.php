<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
	    //app()->environment('production') 현재 환경설정을 가져온다 지금 환경설정이 같으면 true를 반환한다.
	    
	   // if (app()->environment('production')) {
		    $statusCode = 400;
		    $title = '죄송합니다 :(';
		    $description = '에러가 발생했습니다.';
		    
		    // if ($exception instanceof 는 객체 타입을 검사한다. 이 연산자 앞에 이쓴 객체가 뒤에 있는 클래스의 인스턴스면 true를 반환한다.
		    if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException or $exception instanceof \Symfonu\Component\HttpKernel\Exception\NotFoundHttpException) {
			    //우리가 지금 하는건
				$statusCode = 404;
				$description = $exception->getMessage() ?: '요청하신 페이지가 없습니다.';			    
			}
			    
		    return response(view('errors.notice',[
			    'title' => $title,
			    'description' => $description
		    ]), $statusCode);
		    
	  //  }
        return parent::render($request, $exception);
    }
}
