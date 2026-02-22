<?php
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::auth')] class extends Component {
    public $email;
    public $password;

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            return session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Login failed',
                'message' => 'Please check username and password',
                'timer' => 2000,
            ]);
        }
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Success!',
            'message' => 'Login successful',
            'timer' => 2000,
        ]);
        return $this->redirect(url: '/');
    }
};
?>

<div>
    <div class="login-box">
        <div class="login-logo">
            <a><b>Immunization
                </b>Tracker</a>
        </div>

        <div class="card">
            <div class="card-body login-card-body">

                <p class="login-box-msg">Sign in to start your session</p>

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form wire:submit.prevent="login">

                    <div class="input-group mb-3">
                        <input type="email" wire:model="email" class="form-control" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                        </div>
                    </div>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                    <div class="input-group mb-3">
                        <input type="password" wire:model="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-lock"></span></div>
                        </div>
                    </div>
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">Remember Me</label>
                            </div>
                        </div>

                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">
                                Sign In
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
