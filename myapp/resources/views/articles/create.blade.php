@extends('layouts.app')

@section('content')
	<div class="container">
		<h1>새 포럼 글쓰기</h1>
		<hr/>
		<form action="{{route('articles.store')}}" method='POST'> 
			
			<!-- 전송할때는 post 를 쓰고 보내는곳은 route('articles.store') 로 명시된곳으로 한다.-->
				
			{!!csrf_field() !!} 
			
			<!-- csrf 공격을 막기위해  cross site request forgery  공격을 막기 위해 _token 키를 가진 숨은 필드를 만드는 도우미함수다. -->
			
			<div class="form-group {{ $errors->has('title') ? 'has-error' : '' }} " >
				
				<!-- title  키에 해당하는 유효성 검사 오류가 있는지 확인한다. 있으면 has-error를 출력한다.
					 has-error 는 오류를 시각화 하기 위한 트위터 부트스트랩 클래스다. -->
				
				<label for="title">제목</label>
				<input type="text" name="title" id="title" value="{{old('title')}}" class="form-control">
				
				{{--  설명적는중 @if 부분에서 라라벨 주석이 아니다보니 실행하려고 하여서 blade 주석으로 처리
					 <!--  $errors 변수는 @if(isset($errors)) 체크 없이 뷰에서 항상 안전하게 쓸수있다. 유효성 검사 오류가 발생하면 컨트롤러는 lluminate\Support\MessageBag 인스턴스를 만들어 이 변수에 담아두어 뷰에서 꺼내쓸수있다.
					 이 인스턴스에 있는 first(string $key = null,string $format = null) 메서느는 $key 에 있는 메시지중 첫번째를 $format 서식에 맞추어 반환한다.-->
				--}}
				
				{!! $errors->first('title', '<span class="form-error">:message</span>') !!}	
			</div>
				<div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
					<label for="content"> 본문 </label>
					<textarea name="content" id="content" rows="10" class="form-control"> {{ old('content') }} </textarea>
					{!! $errors->first('content' , '<span class="form-error">:message</span>')!!}
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary">
						저장하기
					</button>
				</div>
			<div>
		</form>
	</div>
@stop