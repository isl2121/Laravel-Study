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
		$index = markdown($this->docs->get());
		$content = markdown($this->docs->get($file ?: 'installation.md'));
		
		//뷰의 왼쪽에 표시할 메뉴를 index 변수에 오른쪽에 표시할 본문을 content 변수에 담에 뷰로 넘겼다.
		return view('docs.show',compact('index','content'));
    }
}
