<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $articles = \App\Article::latest()->paginate(2);
       
       //latest() = 쿼리 결과 날짜 역순으로 정렬하는 도우미 메서드다. orderby('created_at', 'desc') 와 같다.
	   //paginate(int $perpage = null) 은 한페이지당 몇개의 메서드를 표시할지다.
       
       return view('articles.index', compact('articles'));
    }

    public function create()
    {
		return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\ArticleRequest $request)
    {
	    
	    
	    /*
	    	$rules = [] 유효성 검사 규칙이다. '필드' => '검사 조건' 과 같이 키-값 쌍으로 표현한다.
			중첩배열 대신 여러번 검사해야한다면
			'content' => 'required|min:10' 처럼 써도된다.
			라라벨은 40개 이상의 검사규칙을 가지고 있고 더 다양한 유효성 검사 규칙은 공식문서를 참고한다.
			https://laravel.kr/docs/validation
	    
	    */
/*
	    $rules = [
		    'title' => ['required'],
		    'content' => 'required|min:10'
	    ];
	    
	    
	    $msg = [
		    'title.required' => '제목은 필수 입력사항입니다.',
		    'content.required' => '내용은 필수 입력사항입니다.',
		    'content.min' => '본문은 최소 :min 글자 이상이 필요합니다.',
	    ];
*/
	    
	    /*
		    make 3번째 인자로 메시지를 지정할수있다.
		    
			기존방식
			$validator = \Validator::make($request->all(),$rules,$msg);
			
			
			validate() 메서드는 어디서 왔나 이것은 부모 클래스에서 찾을수 있다. 
			부모 클래스는 다시 Illuminate\Foundation\Validation\ValidatesRequets 트레이트(trait) 를 임포트하고 있다.
			
			트레이트 메서드 이용
	    */
	    
	    //requst 를 이용하게 되면서 여기서 따로 유효성검사를 안하게 되었다.
	    //그래서 아래내용을 주석처리했다.
	    //전체적으로 주석처리한게 많아지면서 주석을 제외하게 되면 되게 깔끔해질수 있을듯하다.
	    //$this->validate($request->all(),$rules,$msg);
	    
	    //유효성 검사를 한다. fails() 랑 passes()랑 두개를 사용할수있는데 fails 는 유효성 검사못하면 true 를 반환한다.
	    
	    // if ($validator->fails()) {
		    /*-------------------------------------------------------------------------------------------------------------
			    back 은 이전 페이지로 리디렉션하는 도우미 함수다.
			    redirect(route('articles.crate')) 와 같다.
			    ->withErrors() 메서드는 유표섬 검사가 실패한 이유를 세션에다가 굽는일을 한다 뷰에서 본 $errors 변수는 이 메서드가 넘긴것이다.
			    ->withinput() 메서드는 폼데이터를 세션에 굽는 일을 한다. 뷰의 old()함수는 이 메서드가 구운값을 읽는다.
		    -------------------------------------------------------------------------------------------------------------*/
		//    return back()->withErrors($validator)->withInput();
	    // }
	    
	    /*-------------------------------------------------------------------------------------------------------------
		    실전에서는 로그인한 사용자에다가 집어넣겠지만 지금은 실습의 간결성을 위해 사용자 인스턴스를 쿼리로 가져와 집어넣었다.
		    $request 는 이 메서드에 주입된 인스턴스다. $reqest->all()은 사용자가 요청한 쿼리스트링 또는 폼데이터 전체를 연관 배열로 반환한다.
			성공적으로 저장되면 $articles  변수에 새로운 Article 인스턴스를 대입한다.
	    -------------------------------------------------------------------------------------------------------------*/
	    $article = \App\User::find(1)->articles()->create($request->all());
	    
	    if (!$article) {
			/*-------------------------------------------------------------------------------------------------------------
				with(string $key, mixed $value = null)메서드는 인자로 받은 키-값 쌍을 세션에서 저장한다. 이것은 사용자에게 피드백을 제공하기 위해 사용한다.		 
		    -------------------------------------------------------------------------------------------------------------*/
		    return back()->with('flash_message', '글이 저장되지 않았습니다.')->withInput();
	    }
	    
	    var_dump('이벤트를 던집니다.');
	    event('article.created', [$article]);
	    var_dump('이벤트를 던졌습니다.');
	    /*-------------------------------------------------------------------------------------------------------------
			성공적으로 등록이 끝났을때 이동한다.
	    -------------------------------------------------------------------------------------------------------------*/
	   // return redirect(route('articles.index'))->with('flash_message','작성하신 글이 저장되었습니다.');
	    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return __METHOD__ . '는 다음 기본 키를 가진 Article 모델을 조회합니다. :: '. $id;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return __METHOD__ . '는 다음 기본키를 가진 Article 모델을 수정하기 위한 폼을 담은 뷰를 반환합니다. : '.$id;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return __METHOD__ . '는 다음 사용자의 입력한 폼 데이터로 다음 기본 키를 가진 모델을 수정합니다.' . $id;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return __METHOD__ . '는 다음 기본키를 가진 모델을 삭제합니다.'.$id;
    }
}
