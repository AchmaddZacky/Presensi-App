<div class="input-group" style="position: relative;">
    <input type="password" id="password" name="password" class="form-control-swiss" style="padding-right: 2.5rem; font-size: 0.88rem; padding-top: 0.5rem; padding-bottom: 0.5rem;" required>
    <button class="btn btn-swiss d-flex align-items-center justify-content-center" type="button" id="togglePassword" style="position: absolute; right: 0; top: 0; height: 100%; border-left: none; padding: 0 0.85rem; box-shadow: none; font-size: 0.88rem;">
        <i class="bi bi-eye" id="toggleIcon"></i>
    </button>
</div>

<script>
    document.getElementById('togglePassword')?.addEventListener('click', function () {
        const input = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        icon.classList.toggle('bi-eye');
        icon.classList.toggle('bi-eye-slash');
    });
</script>
