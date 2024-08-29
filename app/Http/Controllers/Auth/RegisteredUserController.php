<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
{
    // Validate the incoming request data
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
        'confirmed_password' => 'required|string|same:password',
    ], [
        'confirmed_password.same' => 'The confirmed password does not match the password.',
    ]);

    // Create a new user
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // Trigger the Registered event
    event(new Registered($user));

    // Log the user in
    Auth::login($user);

    // Redirect to the home page
    return redirect(RouteServiceProvider::HOME);
}


    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }
      // Display the specified user
      public function show(User $user)
      {
          return view('users.show', compact('user'));
      }
  
      // Show the form for editing the specified user
      public function edit(User $user)
      {
          return view('users.edit', compact('user'));
      }
  
      // Update the specified user in storage
      public function update(Request $request, User $user)
      {
          $request->validate([
              'name' => 'required|string|max:255',
              'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
              'password' => 'nullable|string|min:8|confirmed',
              'role_id' => 'required|integer',
          ]);
  
          $user->update([
              'name' => $request->name,
              'email' => $request->email,
              'password' => $request->password ? Hash::make($request->password) : $user->password,
              'role_id' => $request->role_id,
          ]);
  
          return redirect()->route('users.index')->with('success', 'User updated successfully.');
      }
  
      // Remove the specified user from storage
      public function destroy(User $user)
      {
          $user->delete();
          return redirect()->route('users.index')->with('success', 'User deleted successfully.');
      }
}
