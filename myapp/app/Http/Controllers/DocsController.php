<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocsController extends Controller
{
    protected $docs;
    
    public function __construct(\App\Documentation $docs)
    {
	    // \App\Documentation 를 모델 인스턴스에 생성자로 주입하고 $this->docs = $docs 를 대입하며 전체적으로 사용할수있다.
	    $this->docs = $docs;
    }
    
    public function show($file = null)
    {

		/*
		$index = markdown($this->docs->get());
		$content = markdown($this->docs->get($file ?: 'installation.md'));
		
		//뷰의 왼쪽에 표시할 메뉴를 index 변수에 오른쪽에 표시할 본문을 content 변수에 담에 뷰로 넘겼다.
		return view('docs.show',compact('index','content'));
		*/
		
		/*
		-------------------------------------------------------------------------
		20장 다듬질 서버 측 캐싱
		-------------------------------------------------------------------------
		
		캐시 적재를 위해 cache 파사드와 remember() 메서드를 이용했다.
		remeber 메서드는 캐시 키를 첫 번째 인자로, 캐시의 유효기간을 두번째 인자로, 클로저를 세번째 인자로 받는다.
		만약 캐시에 저장된게 있으면 캐시에 있는 내용을 불러오고 키가 없으면 클로저에 값을 받아와 캐시저장소에 저장한다.
		두번째 인자는 분으로 120분이 만료시간이다.
		
		*/
		
		
		$index = \Cache::remember('docs.index', 120 ,function(){
			
			//문서 목록 ($index) 와 문서 본문 ($content) 을 캐시에 각각 저장한다.
			// 문서 목록은 'docs.{$file}' 처럼 파일 이름을 캐시에 각각 저장한다.
			// use 키워드는 클로저에 $file 변수를 바인딩 시키는 문법이다.
			return markdown($this->docs->get());
		});
		
		
		$content = \Cache::remember("docs.{$file}", 120, function() use ($file) {
			// 여기서는 처음에 'docs.{$file}' 로 적었는데 정상적으로 실행이 안되었다 전부 문자열로 인식한거같아서
			// "docs.{$file}" 이라고  ' -> " 변경후 정상적으로 동작하였다.
			// 캐시 초기화는 php artisan cache:clear 
			return markdown($this->docs->get($file ?: 'installation.md'));
		});
		
		return view('docs.show',compact('index','content'));
    }
	
	public function image($file)
	{
		$image = $this -> docs->image($file);
		
		return response($image->encode('png'),200, [
			'Content-Type' => 'image/png'
		]);
	}
    
}
