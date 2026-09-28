@if (session('success'))
<script>
    (function() {
        function trigger() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: @json(session('success')),
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK',
                    timer: 3500,
                    timerProgressBar: true
                });
            } else {
                setTimeout(trigger, 50);
            }
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', trigger);
        } else {
            trigger();
        }
    })();
</script>
@endif

@if (session('info'))
<script>
    (function() {
        function trigger() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'info',
                    title: 'Informasi',
                    text: @json(session('info')),
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            } else {
                setTimeout(trigger, 50);
            }
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', trigger);
        } else {
            trigger();
        }
    })();
</script>
@endif

@if (session('warning'))
<script>
    (function() {
        function trigger() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: @json(session('warning')),
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            } else {
                setTimeout(trigger, 50);
            }
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', trigger);
        } else {
            trigger();
        }
    })();
</script>
@endif

@if (session('error'))
<script>
    (function() {
        function trigger() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: @json(session('error')),
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Tutup'
                });
            } else {
                setTimeout(trigger, 50);
            }
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', trigger);
        } else {
            trigger();
        }
    })();
</script>
@endif

@if (isset($errors) && $errors->any())
<script>
    (function() {
        function trigger() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    html: {!! json_encode('<div style="text-align: left; padding: 0 1rem;"><ul style="margin: 0; padding-left: 1.2rem;">' . implode('', array_map(fn($e) => "<li>{$e}</li>", $errors->all())) . '</ul></div>') !!},
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            } else {
                setTimeout(trigger, 50);
            }
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', trigger);
        } else {
            trigger();
        }
    })();
</script>
@endif
