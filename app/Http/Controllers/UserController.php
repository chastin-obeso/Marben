<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    function login(Request $request) {
        // Validate the request data
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);
        if (Auth::attempt([
            'username' => $credentials['username'],
            'password' => $credentials['password'],
        ])) {
            $request->session()->regenerate();
            return redirect()->intended('/home');
        } else {
            return back()->withErrors([
                'username' => 'The provided credentials do not match our records.',
            ])->onlyInput('username');
        }
    }


    function changePassword(Request $request) {
        $request->validate([
            'username' => ['required'],
            'oldpassword' => ['required'],
            'newpassword' => ['required'],
            'confirmpassword' => ['required', 'same:newpassword'],
        ]);
        $user = User::where('username', $request->username)->first();
        if ($user && password_verify($request->oldpassword, $user->password)) {
            $user->password = bcrypt($request->newpassword);
            $user->save();
            $request->session()->regenerate();
            return redirect()->route('home')->with('status', 'Password changed successfully!');
        } else {
            return redirect()->back()->withErrors(['oldpassword' => 'The provided password does not match our records.']);
        }
    }

    function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function process(Request $request)
    {

        if ($request->action === 'add') {
            try {
                //code...
                $test = $request->validate([
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'contact_number' => 'required',
                    'username' => 'required|string|max:255|unique:users,username',
                    'password' => 'required|string|min:8',
                    'status' => 'required|in:active,inactive',
                    'role' => 'required',
                ]);
                // return dd($test); 
                User::create([
                    'first_name' => $test['first_name'],
                    'last_name' => $test['last_name'],
                    'contact_number' => $test['contact_number'],
                    'username' => $test['username'],
                    'password' => bcrypt($test['password']),
                    'status' => $test['status'],
                    'role' => $test['role'],
                ]);
    
                return redirect()->route('accounts')->with('status', 'Account created successfully!');
            } catch (\Throwable $th) {
                //throw $th;
                // dd($th->getMessage());
                return back()->withInput();
            }
            // return dd($request->all());
        } 

        elseif ($request->action === 'update') {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'contact_number' => 'required|string|max:20',
                'username' => 'required|string|max:255|unique:users,username,' . $request->user_id,
                'password' => 'nullable|string|min:8|confirmed',
                'status' => 'required|in:active,inactive',
                'role' => 'required',
            ]);

            $user = User::find($request->user_id);
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->contact_number = $request->contact_number;
            $user->username = $request->username;
            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }
            $user->status = $request->status;
            $user->role = $request->role;
            $user->save();
            return redirect()->route('accounts')->with('status', 'Account updated successfully!');
        }

        else {
        return back()->withErrors(['action' => 'Invalid action submitted.']);
        }

    }

    public function editProfile(Request $request){
        // return($request->all());
        $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'contactnumber' => 'required|string|max:20',
                'username' => 'required|string|max:255|unique:users,username,',
            ]);
        $user = Auth::user();
        $user->first_name = $request->firstname;
        $user->last_name = $request->lastname;
        $user->contact_number = $request->contactnumber;
        $user->username = $request->username;
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function getUser($id){
        $user = User::findOrFail($id);
        return response()->json($user);
    }
}

