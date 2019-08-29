<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store', 'index']
        ]);
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }
    /**
     * 删除用户操作
     * @param \App\Models\User $user
     * @return void
     */
    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }
    /**
     * 首页显示用户列表
     * @return void
     */
    public function index()
    {
        // $users = User::all();
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }
    /**
     * 注册->page
     * @return void
     */
    public function create()
    {
        return view('users.create');
    }
    /**
     * 显示用户信息
     * @param \App\Models\User $user
     * @return void
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }
    /**
     * 创建用户->方法
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        Auth::login($user);
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show', [$user]);
    }
    /**
     * 编辑用户->page
     * @param \App\Models\User $user
     * @return void
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }
    public function update(User $user, Request $request)
    {
        $this->authorize('update', $user);
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);
        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrype($request->password);
        }

        $user->update($data);
        session()->flash('success', '个人资料更新成功！');
        return redirect()->route('users.show', $user->id);
    }
}
