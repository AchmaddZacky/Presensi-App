function updateClock() {
    const clockEl = document.getElementById('live-clock');
    const dateEl = document.getElementById('live-date');
    if (!clockEl) return;

    const now = new Date();
    clockEl.textContent = now.toLocaleTimeString('id-ID');
    if (dateEl) {
        dateEl.textContent = now.toLocaleDateString('id-ID', {
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
        });
    }
}

setInterval(updateClock, 1000);
document.addEventListener('DOMContentLoaded', updateClock);
