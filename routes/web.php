<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
        return redirect()->route('filament.admin.pages.dashboard');
})->name('login');

// Route::get('/login', function () {
//     return view('login');
// })->name('login');

Route::get('/home', function () {
    return view('dashboard');
})->name('home')->middleware('auth');

Route::get('/accounts', function () {
    $users = \App\Models\User::all();
    return view('accounts', ['users' => $users]);
})->name('accounts')->middleware('auth');;

Route::get('/billing', function () {
    return view('billing');
})->name('billing')->middleware('auth');;

Route::get('/calendar', function () {
    return view('calendar');
})->name('calendar')->middleware('auth');;

Route::get('/change-password', function () {
    return view('change-password');
})->name('change-password');

Route::get('/joborders', function () {
    return view('joborders');
})->name('job orders');

Route::get('/view-profile', function () {
    return view('view-profile',[Auth::user()]);
})->name('view-profile');

Route::get('/customers', function () {
    return view('customers');
})->name('customers')->middleware('auth');

Route::post('/edit-user', [UserController::class,'editProfile'])->name('edit-user-details');

Route::post('/login', [UserController::class, 'login'])->name('login.post');

Route::post('/account-crud', [UserController::class, 'process'])->name('account-crud');

Route::post('/change-password', [UserController::class, 'changePassword'])->name('change-password');

Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('user/{id}', [UserController::class, 'getUser']);

// Route::post('/account-crud', function () {
//     //naa two values, either add or update depends sa button value, btw bawal mag add if naay naka select na employee (naay unod ang id)
//     //and bawal mag update if wala unod ang id (walay naka select), kuhaa lang ang input value if "add" or "update" then handle na accordingly
//     //sa controller.
//     return redirect()->route('accounts')->with('status', 'Account updated successfully!');
// })->name('account-crud');

Route::prefix('test')->group(function() {
    Route::get('create', function() {
        return view('test');
    });

    Route::post('store', function() {
        request()->validate([
            'name' => 'required'
        ]);

        return back();
    })->name('test.store');
});