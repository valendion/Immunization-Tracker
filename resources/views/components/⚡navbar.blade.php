<?php

use Livewire\Component;

new class extends Component {
    public function logout()
    {
        Auth::logout();
        return $this->redirect(url: '/login');
    }
};
?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <!-- User Dropdown -->
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                <img src="{{ asset('adminlte3/dist/img/user2-160x160.jpg') }}" class="user-image img-circle"
                    alt="User Image">
                <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
            </a>

            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

                <!-- User image -->
                <li class="user-header">
                    <img src="{{ asset('adminlte3/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
                    <p>
                        {{ auth()->user()->name }}
                    <div>
                        <span class="badge badge-success text-uppercase">
                            {{ auth()->user()->role }}
                        </span>
                    </div>
                    </p>
                </li>

                <!-- Menu Footer-->
                <li class="user-footer">
                    <button wire:click="logout" class="btn btn-sm btn-danger float-right">
                        <i class="fas fa-sign-out-alt"></i>
                        Sign out
                    </button>
                </li>

            </ul>
        </li>

        <!-- Fullscreen Button -->
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

    </ul>
</nav>
