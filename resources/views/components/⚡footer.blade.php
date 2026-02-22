<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<footer class="main-footer">
    <div class="float-right d-none d-sm-inline">
        Version {{ config('app.version') }} • Developed by <b>Valendion Pradana Pasalu</b>
    </div>
    <strong>&copy; {{ date('Y') }} <a href="#">Immunization Track</a>.</strong> All rights reserved.
</footer>
