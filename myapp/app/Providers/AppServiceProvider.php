<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // 말그래도 라라벨에 뭔가를 등록하기 위한 메서드다. 이 메서드 안에서 라라벨의 다른 서비스를 쓰지 않도록 주의
        // 예로들어 이벤트 처리 로직을 여기 쓰면 안된다. 이벤트 서비스가 아직 초기화 되지 않았을수도 있어서
        
        // $this->app->environment('local') app() 함수가 반환하는것과 같은 라라벨 애플리케이션(Illuminate\Foundation\Application) 인스턴스가 담겨있다.
        // $this->app() 은 부모 클래스로부터 상속받은 프로퍼티다.
        
        
        if ($this->app->environment('local')) {
	        //fkfkqpfdptj
	       // $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class)
        }
    }
}
