<div class="swiss-card text-center mb-4">
    <div class="digital-clock-widget tabular-nums" id="live-clock" style="min-height: 1.1em; line-height: 1;">{{ now()->format('H:i:s') }}</div>
    <div class="font-mono text-dim mt-2" id="live-date" style="letter-spacing: 2px; min-height: 1.5em; font-size: 0.85rem;">{{ strtoupper(now()->locale('id')->translatedFormat('l, d F Y')) }}</div>
</div>

<script>
    (function() {
        const pad = n => (n < 10 ? '0' : '') + n;
        const clockEl = document.getElementById('live-clock');
        const dateEl = document.getElementById('live-date');
        let lastDay = -1;

        function updateClock() {
            const now = new Date();
            if (clockEl) {
                clockEl.textContent = pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
            }
            const currentDay = now.getDate();
            if (currentDay !== lastDay && dateEl) {
                lastDay = currentDay;
                dateEl.textContent = now.toLocaleDateString('id-ID', {
                    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
                }).toUpperCase();
            }
        }
        setInterval(updateClock, 1000);
        updateClock();
    })();
</script>
