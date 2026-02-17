<?php

use Livewire\Component;

new class extends Component {
    public $title;

    public function mount($title = null)
    {
        $this->title = $title;
    }
};
?>

<div>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>
                            <i class="fas fa-user mr-1"></i>
                            {{ $title }}

                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('/') }}" wire:current="active" wire:navigate>
                                    <i class="fas fa-home mr-1"></i>
                                    DASHBOARD</a></li>
                            @if ($title == 'DASHBOARD')
                            @else
                                <li class="breadcrumb-item ">
                                    <i class="fas fa-user mr-1"></i>
                                    {{ $title }}
                            @endif


                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="card">
                {{ $slot }}
            </div>
            <!-- /.card-footer-->

            <!-- /.card -->


        </section>

    </div>

</div>
{{-- @include('livewire.superadmin.user.create') --}}
