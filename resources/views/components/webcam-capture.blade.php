@props(['lokasi' => null])

<div class="webcam-container mb-4">
    <div class="card bg-light border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="card-body p-0 position-relative text-center bg-dark" style="min-height: 220px; max-height: 48vh; display: flex; align-items: center; justify-content: center;">
            <video id="webcam-video" autoplay playsinline style="width: 100%; height: 100%; max-height: 48vh; object-fit: cover;"></video>
            <canvas id="webcam-canvas" style="display: none; width: 100%; height: 100%; max-height: 48vh; object-fit: cover;"></canvas>
            
            <div id="camera-loading" class="position-absolute top-50 start-50 translate-middle text-white">
                <div class="spinner-border text-light mb-2" role="status"></div>
                <div>Mengakses Kamera...</div>
            </div>
            <div id="camera-error" class="position-absolute top-50 start-50 translate-middle text-white text-center" style="display: none;">
                <i class="bi bi-camera-video-off fs-1 text-danger"></i>
                <div class="mt-2 text-danger font-monospace" style="font-size: 0.85rem;">KAMERA TIDAK TERSEDIA</div>
            </div>
        </div>
        <div class="card-footer bg-white p-3 border-0">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-2">
                <div class="d-flex flex-column justify-content-center">
                    <div class="d-flex align-items-center mb-1">
                        <div id="location-indicator" class="spinner-grow spinner-grow-sm text-warning me-2" role="status"></div>
                        <small id="location-status" class="text-muted font-monospace" style="font-size: 0.8rem;">Mencari Lokasi GPS...</small>
                    </div>
                    <div id="distance-info" style="display: none; text-align: left;">
                        <span class="badge" id="distance-badge" style="font-size: 0.75rem;">Jarak: -</span>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" id="btn-capture" class="btn-swiss py-2 px-3 flex-grow-1 flex-sm-grow-0" style="font-size: 0.9rem;">
                        <i class="bi bi-camera me-1"></i> Ambil Foto
                    </button>
                    <button type="button" id="btn-retake" class="btn-swiss-danger py-2 px-3 flex-grow-1 flex-sm-grow-0" style="font-size: 0.9rem; display: none;">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Ulang
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Hidden Inputs for Form Submission -->
    <input type="hidden" name="foto_base64" id="foto_base64">
    <input type="hidden" name="latitude" id="latitude">
    <input type="hidden" name="longitude" id="longitude">
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const video = document.getElementById('webcam-video');
    const canvas = document.getElementById('webcam-canvas');
    const btnCapture = document.getElementById('btn-capture');
    const btnRetake = document.getElementById('btn-retake');
    const loadingUI = document.getElementById('camera-loading');
    const errorUI = document.getElementById('camera-error');
    const inputFoto = document.getElementById('foto_base64');
    
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    const locStatus = document.getElementById('location-status');
    const locIndicator = document.getElementById('location-indicator');
    
    const distanceInfo = document.getElementById('distance-info');
    const distanceBadge = document.getElementById('distance-badge');

    const officeLat = {{ $lokasi && $lokasi->latitude ? $lokasi->latitude : 'null' }};
    const officeLon = {{ $lokasi && $lokasi->longitude ? $lokasi->longitude : 'null' }};
    const officeRadius = {{ $lokasi && $lokasi->radius ? $lokasi->radius : '0' }};
    
    let stream = null;

    // Haversine formula in JS
    function calculateDistance(lat1, lon1, lat2, lon2) {
        if (!lat1 || !lon1 || !lat2 || !lon2) return null;
        const R = 6371e3; // metres
        const \u03c61 = lat1 * Math.PI/180;
        const \u03c62 = lat2 * Math.PI/180;
        const \u0394\u03c6 = (lat2-lat1) * Math.PI/180;
        const \u0394\u03bb = (lon2-lon1) * Math.PI/180;

        const a = Math.sin(\u0394\u03c6/2) * Math.sin(\u0394\u03c6/2) +
                Math.cos(\u03c61) * Math.cos(\u03c62) *
                Math.sin(\u0394\u03bb/2) * Math.sin(\u0394\u03bb/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

        return R * c;
    }

    // 1. Initialize Webcam
    async function initCamera() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            console.error("MediaDevices API not available.");
            loadingUI.style.display = 'none';
            errorUI.innerHTML = '<i class="bi bi-shield-lock fs-1 text-warning"></i><div class="mt-2 text-warning" style="font-size: 0.85rem;">Kamera diblokir. Akses web ini menggunakan <b>localhost</b> atau <b>HTTPS</b>.</div>';
            errorUI.style.display = 'block';
            return;
        }

        try {
            stream = await navigator.mediaDevices.getUserMedia({ 
                video: { facingMode: "user", width: { ideal: 640 }, height: { ideal: 480 } },
                audio: false 
            });
            video.srcObject = stream;
            loadingUI.style.display = 'none';
        } catch (err) {
            console.error("Camera access denied or error:", err);
            loadingUI.style.display = 'none';
            errorUI.innerHTML = '<i class="bi bi-camera-video-off fs-1 text-danger"></i><div class="mt-2 text-danger" style="font-size: 0.85rem;">IZIN KAMERA DITOLAK</div>';
            errorUI.style.display = 'block';
        }
    }

    // 2. Initialize Geolocation
    function initLocation() {
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const userLat = position.coords.latitude;
                    const userLon = position.coords.longitude;
                    
                    latInput.value = userLat;
                    lngInput.value = userLon;
                    
                    locStatus.innerText = "Lokasi Ditemukan (" + userLat.toFixed(4) + ", " + userLon.toFixed(4) + ")";
                    locStatus.classList.remove('text-muted');
                    locStatus.classList.add('text-success');
                    locIndicator.classList.remove('spinner-grow', 'text-warning');
                    locIndicator.classList.add('bi', 'bi-geo-alt-fill', 'text-success');
                    
                    // Hitung dan tampilkan jarak
                    if (officeLat !== null && officeLon !== null) {
                        const distance = Math.round(calculateDistance(userLat, userLon, officeLat, officeLon));
                        distanceInfo.style.display = 'block';
                        
                        if (distance <= officeRadius) {
                            distanceBadge.className = 'badge bg-success';
                            distanceBadge.innerText = `Jarak: ${distance}m (Aman)`;
                        } else if (distance <= officeRadius + 100) {
                            distanceBadge.className = 'badge bg-warning text-dark';
                            distanceBadge.innerText = `Jarak: ${distance}m (Peringatan)`;
                        } else {
                            distanceBadge.className = 'badge bg-danger';
                            distanceBadge.innerText = `Jarak: ${distance}m (Terlalu Jauh)`;
                        }
                    }
                },
                function(error) {
                    console.error("Geolocation error:", error);
                    locStatus.innerText = "Akses Lokasi Ditolak/Gagal";
                    locStatus.classList.remove('text-muted');
                    locStatus.classList.add('text-danger');
                    locIndicator.classList.remove('spinner-grow', 'text-warning');
                    locIndicator.classList.add('bi', 'bi-x-circle-fill', 'text-danger');
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        } else {
            locStatus.innerText = "Browser tidak mendukung GPS";
        }
    }

    // Start everything
    initCamera();
    initLocation();

    // 3. Handle Capture
    btnCapture.addEventListener('click', function() {
        if (!stream) return;
        
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
        
        // Convert to base64
        const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
        inputFoto.value = dataUrl;
        
        // Toggle UI
        video.style.display = 'none';
        canvas.style.display = 'block';
        btnCapture.style.display = 'none';
        btnRetake.style.display = 'inline-block';
    });

    // 4. Handle Retake
    btnRetake.addEventListener('click', function() {
        inputFoto.value = '';
        canvas.style.display = 'none';
        video.style.display = 'block';
        btnRetake.style.display = 'none';
        btnCapture.style.display = 'inline-block';
    });
    
    // Validate form on submit
    const form = btnCapture.closest('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!inputFoto.value) {
                e.preventDefault();
                alert("Silakan ambil foto selfie terlebih dahulu.");
                return;
            }
            if (!latInput.value || !lngInput.value) {
                e.preventDefault();
                alert("Menunggu lokasi GPS... Pastikan izin lokasi (location) pada browser Anda telah diaktifkan.");
                return;
            }
            // Release camera hardware immediately
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
        });
    }

    // Release camera stream when navigating away
    window.addEventListener('beforeunload', function() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
    });
});
</script>
@endpush
