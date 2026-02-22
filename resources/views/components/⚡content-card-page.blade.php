<?php

use Livewire\Component;

new class extends Component {
    public $title;

    public $icon;

    public function mount($title = null, $icon = null)
    {
        $this->title = $title;
        $this->icon = $icon;
    }
};
?>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-{{ $icon }} mr-1"></i>
                        {{ $title }}

                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">

                        @if ($title == 'DASHBOARD')
                        @else
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" {{-- class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" wire:navigate> --}}
                                    wire:navigate>
                                    <i class="fas fa-home mr-1"></i>
                                    DASHBOARD</a></li>
                            <li class="breadcrumb-item ">

                                <i class="fas fa-{{ $icon }} mr-1"></i>
                                {{ $title }}
                        @endif


                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            {{ $slot }}
        </div>
    </section>

</div>
