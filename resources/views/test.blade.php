<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\Profileuser;
use App\Models\Keranjang;

class AdminController extends Controller
{
    public function dashboard()
    {
        $barang = Barang::get();
        return view('admin.dashboard',[
            'barang' => $barang,
        ]);
    }
    public function registeradmin()
    {
        return view('admin.registeradmin');
    }
    public function tambahuserProcess(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'role_id' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role_id = $request->role_id;
        $user->save();

        Session::flash('status', 'success');
        Session::flash('message', 'Akun User baru sukses ditambahkan');
        return redirect('/registeradmin');
    }
    public function datauser()
    {
        $users = User::get();
        $profileuser = Profileuser::get();
        return view('admin.datauser',[
            'profileuser' => $profileuser,
            'users' => $users,
        ]);
    }
    public function edituser($id)
    {
        $users = User::find($id);
        $profileuser = Profileuser::where('users_id', $id)->get();
        return view('admin.edituser',compact('users','profileuser'));
    }
    public function updateuser($id, Request $request)
    {
        $users = User::find($id);
        $users->name = $request->name;
        $users->email = $request->email;
        $users->save();
        if ($users->profileuser) {
            $profileuser = Profileuser::where('users_id', $id)->first();
            $profileuser->alamat = $request->alamat;
            $profileuser->alamat_kirim = $request->alamat_kirim;
            $profileuser->no_telpon = $request->no_telpon;
            $profileuser->nama_pic = $request->nama_pic;
            $profileuser->users_id = $id;
            $profileuser->save();
        }
        else {
            $profileuser = new Profileuser();
            $profileuser->alamat = $request->alamat;
            $profileuser->alamat_kirim = $request->alamat_kirim;
            $profileuser->no_telpon = $request->no_telpon;
            $profileuser->nama_pic = $request->nama_pic;
            $profileuser->users_id = $id;
            $profileuser->save();
        }

        Session::flash('status', 'success');
        Session::flash('message', 'Akun User sukses diupdate');
        return redirect('/datauser');
    }
   
}
