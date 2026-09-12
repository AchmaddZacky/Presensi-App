document.addEventListener('DOMContentLoaded', function () {
    const latInput = document.getElementById('latitude');
    const lonInput = document.getElementById('longitude');
    const status = document.getElementById('locationStatus');

    if (!latInput || !lonInput) return;

    if (!navigator.geolocation) {
        if (status) status.textContent = 'Geolocation tidak didukung browser ini.';
        return;
    }

    navigator.geolocation.getCurrentPosition(
        function (position) {
            latInput.value = position.coords.latitude;
            lonInput.value = position.coords.longitude;
            if (status) status.textContent = 'Lokasi berhasil didapatkan.';
        },
        function () {
            if (status) status.textContent = 'Gagal mendapatkan lokasi. Aktifkan izin lokasi.';
        }
    );
});
