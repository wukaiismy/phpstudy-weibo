<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

class SessionsController extends Controller
{
    public function create()
    {
        return view('sessions.create');
    }
    /**
     * Auth::attempt() 
     * 方法可接收两个参数，第一个参数为需要进行用户身份认证的数组，第二个参数为是否为用户开启『记住我』功能的布尔值。
     * 
     *  */
    public function store(Request $request)
    {
       $credentials = $this->validate($request, [
           'email' => 'required|email|max:255',
           'password' => 'required'
       ]);
       if (Auth::attempt($credentials, $request->has('remember'))) {
        session()->flash('success', '欢迎回来！');
        $fallback = route('users.show', Auth::user());
        return redirect()->intended($fallback);
    } else {
        session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
        return redirect()->back()->withInput();
    }
       return;
    }

    public function destroy()
    {
        Auth::logout();
        session()->flash('success', '您已成功退出！');
        return redirect('login');
    }


    /**
     * 未登录用户只允许访问登录注册
     * 中间件
     */
    public function __construct(){
        $this->middleware('guest',[
            'only'=>['create']
        ]);
    }
}
