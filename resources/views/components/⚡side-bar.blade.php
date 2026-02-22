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
        <span class="brand-text font-weight-light">Immunization Tracker</span>
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
                @if (auth()->user()->role === 'admin')
                    <li class="nav-header">Menu Admin</li>
                    <li class="nav-item">
                        <a wire:navigate href="{{ route('admin.vaccine.index') }}" class="nav-link "
                            wire:current="active">
                            <i class="nav-icon fas fa-syringe"></i>
                            <p>
                                Vaccine
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a wire:navigate href="{{ route('admin.facility.index') }}" class="nav-link"
                            wire:current="active">
                            <i class="nav-icon fas fa-building"></i>
                            <p>
                                Facility
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a wire:navigate hr class="nav-link" href="{{ route('admin.child.index') }}" class="nav-link"
                            wire:current="active">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Child
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a wire:navigate hr class="nav-link" href="{{ route('admin.immunization-record.index') }}"
                            class="nav-link" wire:current="active">
                            <i class="nav-icon fas fa-file"></i>

                            <p>
                                Immunization Record </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a wire:navigate hr class="nav-link" href="{{ route('immunization-record-view') }}"
                            class="nav-link" wire:current="active">
                            <i class="nav-icon fas fa-eye"></i>

                            <p>
                                Immunization View</p>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->role === 'operator')
                    <li class="nav-header">Menu Operator</li>
                    {{-- <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-warehouse"></i>
                                <p>
                                    Barang
                                </p>
                            </a>
                        </li> --}}
                @endif

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
