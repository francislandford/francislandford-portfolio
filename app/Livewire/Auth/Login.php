<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function login()
    {
        $credentials = $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $this->remember)) {
            $this->addError('email', 'These credentials do not match our records.');

            return;
        }

        session()->regenerate();

        return redirect()->intended(route('elearning.dashboard'));
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('components.layouts.app', [
            'title' => 'Sign In',
            'robots' => 'noindex, follow',
        ]);
    }
}
