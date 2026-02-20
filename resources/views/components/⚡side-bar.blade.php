<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{ asset('adminlte3/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- SidebarSearch Form -->


        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <li class="nav-item">
                    <a wire:navigate href="{{ route('dashboard') }}" class="nav-link " wire:current.exact="active">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <li class="nav-header">Menu Super Admin</li>
                <li class="nav-item">
                    <a wire:navigate href="{{ route('superadmin.vaccine.index') }}" class="nav-link "
                        wire:current="active">
                        <i class="nav-icon fas fa-user "></i>
                        <p>
                            Vaccine
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a wire:navigate href="{{ route('superadmin.facility.index') }}" class="nav-link"
                        wire:current="active">
                        <i class="nav-icon fas fa-list"></i>
                        <p>
                            Facility
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a wire:navigate hr class="nav-link" href="{{ route('superadmin.child.index') }}" class="nav-link"
                        wire:current="active">
                        <i class="nav-icon fas fa-warehouse"></i>
                        <p>
                            Child
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a wire:navigate hr class="nav-link" href="{{ route('superadmin.immunization-record.index') }}"
                        class="nav-link" wire:current="active">
                        <i class="nav-icon fas fa-warehouse"></i>
                        <p>
                            Immunization Record </p>
                    </a>
                </li>

                <li class="nav-header">Admin</li>
                {{-- <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-warehouse"></i>
                                <p>
                                    Barang
                                </p>
                            </a>
                        </li> --}}
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
