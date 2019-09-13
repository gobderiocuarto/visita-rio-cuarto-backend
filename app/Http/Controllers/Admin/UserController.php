<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Caffeinated\Shinobi\Models\Role;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $user = User::find($id);

        // return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::get();

        $array_roles_id = $user->roles->pluck('id')->toArray();
        
        // echo ('<pre>');print_r($array_roles_id);echo ('</pre>'); exit();

        return view('admin.users.edit', compact('user', 'roles', 'array_roles_id'));
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
        
        // echo ('<pre>');print_r($request->all());echo ('</pre>'); exit();
        
        $user = User::findOrFail($id);
        $user->update($request->all());

        $user->roles()->sync($request->get('roles'));

        return redirect()->route('users.edit', $user->id)
            ->with('message', 'Usuario guardado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id)->delete();

        return back()->with('message', 'Usuario eliminado correctamente');
    }
}
