@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: @json(session('success')),
            timer: 2500,
            showConfirmButton: false,
        });
    });
</script>
@endif

@if (session('info'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'info',
            title: 'Informasi',
            text: @json(session('info')),
            confirmButtonColor: '#2563eb',
        });
    });
</script>
@endif

@if (session('warning'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian',
            text: @json(session('warning')),
            confirmButtonColor: '#f59e0b',
        });
    });
</script>
@endif

@if (session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: @json(session('error')),
            confirmButtonColor: '#d33',
        });
    });
</script>
@endif
@if (isset($errors) && $errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'error',
            title: 'Terjadi kesalahan',
            html: @json(implode('<br>', $errors->all())),
        });
    });
</script>
@endif
