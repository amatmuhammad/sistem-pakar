<style>
    .toast-container-global {
        z-index: 10000 !important; 
        position: fixed !important;
    }
    
    /* Membuat header sedikit lebih tinggi dan rapi */
    .toast-header {
        padding: 0.75rem 1rem !important;
    }

    /* MEMBERIKAN RUANG PADA PESAN */
    .toast-body-custom {
        padding: 1.2rem 1rem !important; /* Menambah ruang atas bawah agar tidak rapat */
        font-size: 0.95rem;
        line-height: 1.5;
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
    }

    .toast-header i {
        font-size: 1.4rem !important;
    }
</style>

<div class="toast-container toast-container-global top-0 end-0 p-3">
    @if(session('success') || session('error'))
        @php
            $type = session('success') ? 'success' : 'danger';
            $icon = session('success') ? 'bx-bxs-check-circle' : 'bx-bxs-x-circle';
            $title = session('success') ? 'Success' : 'Error';
            $message = session('success') ?? session('error');
        @endphp

        <div id="globalToast" 
             class="bs-toast toast fade show animate__animated animate__backInRight" 
             role="alert" aria-live="assertive" aria-atomic="true">
            
            <div class="toast-header border-0">
                <i class="bx {{ $icon }} me-2 text-{{ $type }}"></i>
                <div class="me-auto fw-bold text-dark" style="font-size: 1.1rem;">{{ $title }}</div>
                <small class="text-muted">Baru Saja</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            
            <div class="toast-body-custom bg-{{ $type }} text-white">
                {{ $message }}
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toastEl = document.getElementById('globalToast');
        if (toastEl) {
            const bsToast = new bootstrap.Toast(toastEl, { autohide: true, delay: 5000 });
            bsToast.show();
        }
    });
</script>