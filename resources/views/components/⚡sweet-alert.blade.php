<?php

use Livewire\Component;

new class extends Component {
    // successfully added
    // successfully edited
    // successfully deleted

    protected $listeners = [
        'alert', // Method untuk penyampaian biasa
        'confirm', // Method untuk konfirmasi
    ];

    /**
     * Show Alert (untuk penyampaian biasa)
     *
     * @param string $icon - success, error, warning, info, question
     * @param string $title - Judul alert
     * @param string $message - Pesan alert
     */
    public function alert($icon, $title, $message = '')
    {
        $this->dispatch('show-alert', [
            'icon' => $icon,
            'title' => $title,
            'message' => $message,
        ]);
    }

    /**
     * Show Confirm (untuk konfirmasi dengan callback)
     *
     * @param string $icon - success, error, warning, info, question
     * @param string $title - Judul konfirmasi
     * @param string $message - Pesan konfirmasi
     * @param string $confirmText - Text button konfirmasi
     * @param string $cancelText - Text button batal
     * @param string $method - Method yang akan dipanggil jika confirmed
     * @param mixed $params - Parameter untuk method
     */
    public function confirm($icon, $title, $message, $confirmText, $cancelText, $method, $params = null)
    {
        $this->dispatch('show-confirm', [
            'icon' => $icon,
            'title' => $title,
            'message' => $message,
            'confirmText' => $confirmText,
            'cancelText' => $cancelText,
            'method' => $method,
            'params' => $params,
        ]);
    }
};
?>

<div>
    {{-- Order your soul. Reduce your wants. - Augustine --}}
</div>
