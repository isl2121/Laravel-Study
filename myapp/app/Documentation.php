<?php

namespace App;

use File;

class Documentation
{
    public function get($file = 'documentation.md')
    {
	    $content = File::get($this->path($file));
	    return $this->replaceLinks($content);
    }
    
    protected function path($file, $dir = 'docs')
    {
		$file = ends_with($file, ['.md', '.png']) ? $file : $file . '.md';
		$path = base_path($dir . DIRECTORY_SEPARATOR . $file);
	    
	   
	    if (! File::exists($path)) {
		    abort(404, '요청하신파일이 없습니다.');
		    
	    }
	    
	    return $path;
	    
    }
    
    public function image($file)
    {	
	    //http 요청에 달려온 if - non-math 값과 우리가 만든 etag 값을 비교하여 
	    //같으면 304 not modified http응답을 다르면 새로운 etag가 달린 이미지 응답을 제공하는 컨트롤러 로직을 만든다.
	    $reqEtag = \Reqest::getEtags();
	    $getEtag = $this->docs->etag($file);
	    
	    //해당 코드로 이미지가 캐시되어있는지 아닌지 헤더로 비교한다.
	    //만약 일치한다면 304로 반환
	    if (isset($reqEtag[0])) {
		    if ($reqEtag[0] === $genEtag) {
			    return response('',304);
		    }
	    }
	    
	    
//	    return \Image::make($this->path($file,'docs/images'));


	    $image = $this->docs->image($file);
	    //만약 일치하는게 없이 새로 요청하는거면 etag에 고유값을 같이 전달한다.
	    return response($image->encode('png'),200,[
		    'Content-Type' => 'image/png',
		    'Cache-Control' => 'public, max-age=0',
		    'Etag' => $genEtag,
	    ]);
    }
    
  	public function etag($file)
  	{
	  	$lasgModified = File::lastModified($this->path($file,'docs/images'));
	  	
	  	// $lasgModified = File::lastModified 해당파일의 마지막 수정일
	  	// $file 파일이름
	  	// 파일이름이랑 마지막 파일변경일을 합쳐서 고유성과 변경여부를 모두 담았다.. 진짜 머리좋으시다.. 이런생각은 한번도 못해봤는데..
	  	// 이값을 etag키로 사용한다 참고로 etag 헤더가 있어야 브라우저 캐시 로직이 동작한다.
	  	
	  	
	  	return md5($file . $lastModified);
  	}
  	
  	    
    protected function replaceLinks($content)
    {
	    return str_replace('/docs/{{version}}', '/docs', $content);
    }
}
