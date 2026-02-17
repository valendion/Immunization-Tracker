<script>
    // resources/js/sweetalert-config.js

    document.addEventListener('livewire:init', function() {

        // CEK FLASH SESSION DARI REDIRECT
        @if (session()->has('swal'))
            Swal.fire({
                icon: '{{ session('swal.icon') }}',
                title: '{{ session('swal.title') }}',
                text: '{{ session('swal.message') }}',
                timer: {{ session('swal.timer', 3000) }},
                showConfirmButton: false,
                position: 'center',
                didOpen: () => {
                    // Hapus flash session setelah ditampilkan
                    @php session()->forget('swal'); @endphp
                }
            });
        @endif

        // Event listener untuk alert biasa (tanpa redirect)
        Livewire.on('show-alert', (data) => {

            console.log('Data received:', data); // Lihat di console browser
            console.log('Type:', typeof data);
            console.log('Is array:', Array.isArray(data));
            // const {
            //     icon,
            //     title,
            //     message = '',
            //     timer = 3000
            // } = data;

            Swal.fire({
                icon: data[0].icon,
                title: data[0].title,
                text: data[0].message,
                timer: data[0].timer,
                showConfirmButton: false
            });
        });

        Livewire.on('confirm', (data) => {
            // Data akan selalu tersedia di data[0]
            const payload = data[0] || data;

            Swal.fire({
                title: payload.title || 'Apakah Anda yakin?',
                text: payload.text || '',
                icon: payload.icon || 'warning',
                showCancelButton: true,
                confirmButtonColor: payload.confirmColor || '#d33',
                cancelButtonColor: payload.cancelColor || '#3085d6',
                confirmButtonText: payload.confirmText || 'Ya, lanjutkan!',
                cancelButtonText: payload.cancelText || 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Eksekusi function jika ada
                    if (payload.onConfirm && typeof payload.onConfirm === 'function') {
                        payload.onConfirm();
                    }

                    // Dispatch event Livewire jika ada
                    if (payload.event) {
                        Livewire.dispatch(payload.event, payload.params || {});
                    }

                    // Tampilkan toast sukses (opsional)
                    if (payload.showToast !== false) {
                        Swal.fire({
                            icon: 'success',
                            title: payload.successTitle || 'Berhasil!',
                            text: payload.successMessage ||
                                'Tindakan berhasil dilakukan',
                            timer: 2000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    }
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Eksekusi function jika batal
                    if (payload.onCancel && typeof payload.onCancel === 'function') {
                        payload.onCancel();
                    }
                }
            });
        });

        Livewire.on('confirm-delete', (data) => {
            Swal.fire({

                title: data[0].title,
                text: data[0].text,
                icon: data[0].icon,
                showCancelButton: true,
                confirmButtonColor: "red",
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('confirmed-delete', {
                        id: data[0].id
                    });
                }
            });
        });

    });
</script>
