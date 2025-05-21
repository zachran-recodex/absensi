<style>
    #map { height: 500px; width: 100%; }
    .employee-list {
        max-height: 500px;
        overflow-y: auto;
    }
    .employee-item {
        cursor: pointer;
        padding: 10px;
        border-bottom: 1px solid #eee;
        transition: background-color 0.3s;
    }
    .employee-item:hover {
        background-color: #f5f5f5;
    }
    .employee-item.active {
        background-color: #e0f7fa;
    }
    .location-form {
        margin-top: 20px;
    }
    .radius-control {
        margin-top: 15px;
    }
</style>

<!-- Peta Lokasi Absen -->

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $judul; ?></h1>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Karyawan</h6>
                </div>
                <div class="card-body">
                    <div class="employee-list">
                        <?php if (empty($employees)): ?>
                            <div class="alert alert-info">Tidak ada data karyawan</div>
                        <?php else: ?>
                            <?php foreach ($employees as $employee): ?>
                                <div class="employee-item" data-id="<?= $employee['id_user']; ?>" 
                                     data-lat="<?= $employee['location_latitude'] ?? ''; ?>" 
                                     data-lng="<?= $employee['location_longitude'] ?? ''; ?>" 
                                     data-radius="<?= $employee['location_radius'] ?? '100'; ?>">
                                    <h5><?= $employee['name']; ?></h5>
                                    <p class="mb-0 text-muted"><?= $employee['username']; ?></p>
                                    <small class="location-status">
                                        <?php if (!empty($employee['location_latitude']) && !empty($employee['location_longitude'])): ?>
                                            <span class="text-success">Lokasi telah diatur</span>
                                        <?php else: ?>
                                            <span class="text-danger">Lokasi belum diatur</span>
                                        <?php endif; ?>
                                    </small>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pengaturan Lokasi</h6>
                </div>
                <div class="card-body">
                    <form id="locationForm" class="location-form">
                        <input type="hidden" id="id_user" name="id_user">
                        <div class="form-group">
                            <label for="latitude">Latitude</label>
                            <input type="text" class="form-control" id="latitude" name="latitude" readonly>
                        </div>
                        <div class="form-group">
                            <label for="longitude">Longitude</label>
                            <input type="text" class="form-control" id="longitude" name="longitude" readonly>
                        </div>
                        <div class="form-group radius-control">
                            <label for="radius">Radius (meter)</label>
                            <input type="number" class="form-control" id="radius" name="radius" min="10" max="1000" value="100">
                            <small class="form-text text-muted">Jarak maksimum yang diizinkan untuk absensi</small>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block mt-3" id="saveLocation" disabled>Simpan Lokasi</button>
                    </form>
                    <div class="mt-3" id="locationInfo"></div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Peta Lokasi</h6>
                </div>
                <div class="card-body">
                    <div id="map"></div>
                    <div class="mt-3">
                        <p class="mb-0"><small>Petunjuk: Klik pada peta untuk menentukan lokasi absen atau geser marker untuk menyesuaikan lokasi.</small></p>
                        <p><small>Radius menentukan jarak maksimum karyawan dapat melakukan absensi dari titik lokasi yang ditentukan.</small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script untuk mengelola peta lokasi absen -->

<script>
    // Inisialisasi variabel global
    let map, marker, circle;
    let currentUserId = null;
    
    // Koordinat default (pusat Indonesia)
    const defaultLat = -0.789275;
    const defaultLng = 113.921327;
    
    $(document).ready(function() {
        // Inisialisasi peta
        initMap();
        
        // Event handler untuk klik pada item karyawan
        $('.employee-item').on('click', function() {
            // Hapus kelas active dari semua item
            $('.employee-item').removeClass('active');
            // Tambahkan kelas active ke item yang diklik
            $(this).addClass('active');
            
            // Ambil data karyawan
            currentUserId = $(this).data('id');
            const lat = $(this).data('lat');
            const lng = $(this).data('lng');
            const radius = $(this).data('radius');
            
            // Isi form dengan data karyawan
            $('#id_user').val(currentUserId);
            $('#radius').val(radius || 100);
            
            // Jika lokasi sudah diatur, tampilkan di peta
            if (lat && lng) {
                $('#latitude').val(lat);
                $('#longitude').val(lng);
                updateMapLocation(lat, lng, radius);
                $('#saveLocation').prop('disabled', false);
            } else {
                // Jika belum, reset form dan peta
                $('#latitude').val('');
                $('#longitude').val('');
                resetMap();
                $('#saveLocation').prop('disabled', true);
            }
        });
        
        // Event handler untuk submit form
        $('#locationForm').on('submit', function(e) {
            e.preventDefault();
            
            if (!currentUserId) {
                alert('Pilih karyawan terlebih dahulu');
                return;
            }
            
            // Ambil data form
            const formData = {
                id_user: $('#id_user').val(),
                latitude: $('#latitude').val(),
                longitude: $('#longitude').val(),
                radius: $('#radius').val()
            };
            
            // Validasi data
            if (!formData.latitude || !formData.longitude || !formData.radius) {
                alert('Semua field harus diisi');
                return;
            }
            
            // Kirim data ke server
            $.ajax({
                url: '<?= base_url("location/save"); ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Update UI
                        const activeItem = $('.employee-item.active');
                        activeItem.data('lat', formData.latitude);
                        activeItem.data('lng', formData.longitude);
                        activeItem.data('radius', formData.radius);
                        activeItem.find('.location-status').html('<span class="text-success">Lokasi telah diatur</span>');
                        
                        // Tampilkan pesan sukses
                        $('#locationInfo').html('<div class="alert alert-success">'+response.message+'</div>');
                        
                        // Update peta
                        updateMapLocation(formData.latitude, formData.longitude, formData.radius);
                    } else {
                        // Tampilkan pesan error
                        $('#locationInfo').html('<div class="alert alert-danger">'+response.message+'</div>');
                    }
                },
                error: function() {
                    $('#locationInfo').html('<div class="alert alert-danger">Terjadi kesalahan. Silakan coba lagi.</div>');
                }
            });
        });
        
        // Event handler untuk perubahan radius
        $('#radius').on('change', function() {
            const lat = $('#latitude').val();
            const lng = $('#longitude').val();
            const radius = $(this).val();
            
            if (lat && lng && radius) {
                updateCircleRadius(radius);
            }
        });
    });
    
    // Fungsi untuk inisialisasi peta
    function initMap() {
        // Buat peta dengan koordinat default
        map = L.map('map').setView([defaultLat, defaultLng], 5);
        
        // Tambahkan layer peta
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        // Event handler untuk klik pada peta
        map.on('click', function(e) {
            if (!currentUserId) {
                alert('Pilih karyawan terlebih dahulu');
                return;
            }
            
            // Update form dan peta
            $('#latitude').val(e.latlng.lat.toFixed(6));
            $('#longitude').val(e.latlng.lng.toFixed(6));
            updateMapLocation(e.latlng.lat, e.latlng.lng, $('#radius').val());
            $('#saveLocation').prop('disabled', false);
        });
    }
    
    // Fungsi untuk memperbarui lokasi di peta
    function updateMapLocation(lat, lng, radius) {
        // Hapus marker dan circle yang ada
        if (marker) map.removeLayer(marker);
        if (circle) map.removeLayer(circle);
        
        // Tambahkan marker baru
        marker = L.marker([lat, lng], {draggable: true})
            .addTo(map)
            .bindPopup('Lokasi Absen')
            .on('dragend', function(e) {
                // Update form saat marker digeser
                const position = e.target.getLatLng();
                $('#latitude').val(position.lat.toFixed(6));
                $('#longitude').val(position.lng.toFixed(6));
                updateCirclePosition(position.lat, position.lng);
            });
        
        // Tambahkan circle untuk menunjukkan radius
        circle = L.circle([lat, lng], {
            radius: parseInt(radius),
            color: '#4e73df',
            fillColor: '#4e73df',
            fillOpacity: 0.2
        }).addTo(map);
        
        // Zoom ke lokasi
        map.setView([lat, lng], 16);
    }
    
    // Fungsi untuk memperbarui posisi circle
    function updateCirclePosition(lat, lng) {
        if (circle) {
            circle.setLatLng([lat, lng]);
        }
    }
    
    // Fungsi untuk memperbarui radius circle
    function updateCircleRadius(radius) {
        if (circle) {
            circle.setRadius(parseInt(radius));
        }
    }
    
    // Fungsi untuk reset peta
    function resetMap() {
        if (marker) map.removeLayer(marker);
        if (circle) map.removeLayer(circle);
        map.setView([defaultLat, defaultLng], 5);
    }
</script>
