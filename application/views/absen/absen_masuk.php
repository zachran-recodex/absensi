<style>
	body {
		font-family: 'Arial', sans-serif;
		background-color: #f8f9fa;
		color: #333;
	}

	h2 {
		font-size: 2rem;
		font-weight: bold;
		color: #132d60;
		margin-bottom: 1.5rem;
	}

	.video-container {
		margin: 2rem auto;
		padding: 1.5rem;
		border-radius: 15px;
		background: #ffffff;
		box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
		max-width: 700px;
		text-align: center;
	}

	.video-container img {
		border-radius: 15px;
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
		width: 100%;
		max-width: 600px;
	}

	.btn-absen {
		margin-top: 1.5rem;
		padding: 0.75rem 1.5rem;
		font-size: 1rem;
		font-weight: bold;
		color: #ffffff;
		background-color: #132d60;
		border: none;
		border-radius: 8px;
		cursor: pointer;
		transition: background-color 0.3s ease;
	}

	.btn-absen:hover {
		background-color: #0056b3;
	}
</style>

<div class="container text-center mt-5">
	<h2>Absen Masuk</h2>
	<div class="video-container">
		<img src="http://10.200.4.42:5000/video_feed" alt="Real-Time Face Recognition">
		<button class="btn-absen" onclick="submitAbsensi()">Absen</button>
	</div>
</div>
<script>
	function submitAbsensi() {
		const now = new Date();

		const tanggal = now.toLocaleDateString('en-CA');

		const waktu = now.toTimeString().split(' ')[0];
		fetch("http://10.200.4.42:5000/get_current_id")
			.then(response => {
				if (!response.ok) {
					throw new Error(`HTTP error! status: ${response.status}`);
				}
				return response.json();
			})

			.then(data => {
				console.log(data);
				const idUserVideo = data.id_user;
				const name = data.name;
				const faceEncoding = data.face_encoding;

				if (!idUserVideo) {
					Swal.fire({
						icon: 'error',
						title: 'Gagal',
						text: 'Tidak ada wajah yang terdeteksi!'
					});
					return;
				}

				if (navigator.geolocation) {
					navigator.geolocation.getCurrentPosition(
						(position) => {
							const latitude = position.coords.latitude;
							const longitude = position.coords.longitude;

							fetch('<?= base_url('absen/simpan_absen') ?>', {
								method: 'POST',
								headers: {
									'Content-Type': 'application/json',
								},
								body: JSON.stringify({
									id_user_video: idUserVideo,
									latitude: latitude,
									longitude: longitude,
									face_encoding: faceEncoding,
									tanggal: tanggal,
									waktu: waktu,
								}),
							})
								.then((response) => response.json())
								.then((data) => {
									if (data.success) {
										Swal.fire({
											icon: 'success',
											title: 'Absen Berhasil',
											text: 'Absen berhasil dilakukan!'
										}).then(() => {
											controlVideoFeed("stop");
											window.location.href = '<?= base_url('absen') ?>';
										});
									} else {
										Swal.fire({
											icon: 'error',
											title: 'Gagal',
											text: data.message
										});
									}
								})
								.catch((error) => {
									console.error('Error:', error);
									Swal.fire({
										icon: 'error',
										title: 'Terjadi Kesalahan',
										text: 'Terjadi kesalahan saat memproses data.'
									});
								});
						},
						(error) => {
							Swal.fire({
								icon: 'error',
								title: 'Gagal',
								text: 'Gagal mendapatkan lokasi. Aktifkan GPS Anda.'
							});
						}
					);
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Gagal',
						text: 'Geolocation tidak didukung di perangkat Anda.'
					});
				}
			})
			.catch(error => {
				console.error('Error fetching id_user_video:', error);
				Swal.fire({
					icon: 'error',
					title: 'Error',
					text: 'Wajah Tidak Cocok.'
				});
			});
	}
</script>

<script>

	function controlVideoFeed(action) {
		fetch("http://10.200.4.42:5000/control_video_feed", {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify({action: action}),
		})
			.then(response => response.json())
			.then(data => {
				console.log(data.status || data.error);
			})
			.catch(error => console.error('Error:', error));
	}


	window.onload = function () {
		controlVideoFeed("start");
	};


	window.onbeforeunload = function () {
		controlVideoFeed("stop");
	};
</script>
