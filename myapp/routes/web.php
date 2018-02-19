<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/','WelcomeController@index');
Route::get('auth/login', function(){
	$credentials = [
		'email' => 'john@example.com',
		'password'=>'password'	
	];
	// $credentials 에 고객정보를 하드코딩하였다.
	
	//auth()는 도우미 함수이다. 사용자를 인증할때는 attempt() 메서드를 이용한다.
	//attempt(array $credentials = [] , bool $remember = false) 두번째 인자에 true를 넣으면 remember_toklen과 같이 동작해서 사용자 로그인을 기억할수있다.
	//auth () 도우미 대신 Auth::attempt() 와 같이 파사드를 이용할수있다 attempt() 메서드를 넘겨받으면 $credentials 정보를 데이터 베이스에 정확히 일치하는 사용자가 있는지 확인한다.
	// password 값은 데이터베이스 조회를 하기전에 미리 해싱해서 비교할준비를한다. 만약 데이터베이스가 있으면 세션에 사용자 정보를 기록한다. 
	if(!AUTH()->attempt($credentials)){
		return '로그인 정보가 정확하지 않습니다.';
	}
	
	return redirect('protected');

});


Event::listen('article.created', function ($article){
	var_dump('이벤트를 받았습니다. 받은 데이터의 상태는 다음과 같습니다.');
	var_dump($article->toArray());
});

/*
Route::get('protected', function(){
	dump(session()->all());
	//auth()->chekc() 는 사용자가 로그인했는지 안했는지를 판단한다 만약로그인했으면 true를 반환한다.
	
	if(!auth()->check()){
		return '누구세요?';		
	}
	//auth()->user()는 로그인한 사용자의 정보를 반환한다.
	//check 메서드는 세션과 쿠키로 데이터를 주고받으면서 로그인되어있는지 안되어있는지 판단하다.
	//쿠키에 사용할키는 config/session.php 에서 변경할수있다.
	
	
	//로그인한 경우에는 문제없이 진행되지만 로그인을 하지 않은경우 auth()->user = null이기 때문에 오류가 발생한다.
	return '어서오세요' . auth()->user()->name;
});
*/

/*
DB::listen(function ($query){
	//이벤트 리스너다 이 코드는 데이터베이스 쿼리를 감지할수 있다.
	var_dump($query->sql);
	
});
*/

route::get('protected',['middleware'=>'auth', function(){
		return '어서오세요' . auth()->user()->name;
}]);

route::get('auth/logout',function(){
	//auth()->logout()은 세션을 페기한다.
	auth()->logout();
	return '안녕히가세요.';
	
});

Route::get('mail' ,function(){
	$article = App\Article::with('user')->find(1);
	
	return Mail::send(
		'emails.articles.created',
		compact('article'),
			function ( $message) use ($article) {
				$message->to('isl2121@naver.com');
				$message->subject('새글이 등록되었습니다.'.$article->title);
			}
	);
});


Route::resource('articles','ArticlesController');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

route::get('markdown',function(){
	$text =<<<EOT
# 마크다운 예제1

이 문서는 [마크다운][1]으로 썻습니다. 화면에는 HTML로 변환되어 출력합니다.

## 순서없는 목록

- 첫 번째 목록
- 두 번째 항목[^1]

[1] : http://daringfireball.net/projects/markdown

[^1] : 두 번째 항목_ http://google.com
EOT;
	
	return app(ParsedownExtra::class)->text($text);
});
