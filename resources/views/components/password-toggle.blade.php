<div class="input-group" style="position: relative;">
    <input type="password" id="password" name="password" class="form-control-swiss" style="padding-right: 3rem;" required>
    <button class="btn btn-swiss" type="button" id="togglePassword" style="position: absolute; right: 0; top: 0; height: 100%; border-left: none; padding: 0 1rem; box-shadow: none;">
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
