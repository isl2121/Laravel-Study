<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{

    public function authorize()
    {
	    //사용자가 리 폼 리퀘스트를 주입받는 메서드에 접근할 권한이 있는지 검사한다.
        return true;
    }

    public function rules()
    {
	    
	    //유효성 검사규칙을 정의한다.
        return [
            
	          'title' => ['required'],
	          'content' =>['required','min:10'], 
            
        ];
    }
    
    public function messages()
    {
	    //유효성 검사오류 메시지를 정의한다. :attribute 이런 표시자가 사용되는데 이자리 표시자는 필드 이름으로 교체된다.
	    return [
		    'required' => ':attribute은(는) 필수 입력사항입니다.',
		    'min' => ':attribute은(는) 최소 :min 글자 이상이 필요합니다.',
	    ];
	    
    }
    
    public function attributes()
    {
	    //오류메시지에 표시할 필드 이름을 사용자 친화적인 이름으로 바꿀수 있다. 이 메시지가 없다면 'title' 은 필수입력항목입니다. 라고 뜬다는데
	    // 아무래도 messages 에서 title-> 제목 이런식으로 바꿔서 메시지 해주는거같다.
		return [
			'title' => '제목',
			'content' => '본문',
		];
    }
}
