<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>Absensi</title>


	<!-- Custom fonts for this template-->
	<!-- Full jQuery -->

	<script src="<?= base_url() ?>/assets/models/face-api.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

	<!-- Bootstrap JavaScript -->
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

	<!-- SweetAlert -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<link href="<?= base_url(); ?>assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<link
		href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
		rel="stylesheet">

	<!-- Custom styles for this template-->
	<link href="<?= base_url(); ?>assets/css/sb-admin-2.min.css" rel="stylesheet">
	<link href="<?= base_url(); ?>assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
	<link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/select2/css/select2.min.css">
	<link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

	<style>
		.notification {
			position: fixed;
			bottom: 50px;
			right: 50px;
			background-color: #28a745;
			/* Warna success */
			color: #fff;
			padding: 20px;
			border-radius: 10px;
			display: none;
			width: 350px;
			z-index: 1000;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
			animation: slideIn 0.5s ease-out;
		}

		.notification h2 {
			margin: 0 0 10px;
			font-size: 20px;
		}

		.notification ul {
			list-style: none;
			padding: 0;
			margin: 0 0 10px;
		}

		.notification ul li {
			margin-bottom: 10px;
			padding-bottom: 10px;
			border-bottom: 1px solid #fff;
		}

		.notification ul li p {
			margin: 5px 0 0;
		}

		.notification button {
			background-color: #fff;
			color: #28a745;
			border: none;
			padding: 10px 20px;
			border-radius: 5px;
			cursor: pointer;
			font-size: 14px;
		}

		.notification button:hover {
			background-color: #f8f9fa;
		}

		@keyframes slideIn {
			from {
				opacity: 0;
				transform: translateX(100%);
			}

			to {
				opacity: 1;
				transform: translateX(0);
			}
		}

		.notif-icon {
			position: fixed;
			bottom: 15px;
			right: 15px;
			background-color: #28a745;
			color: white;
			padding: 10px;
			border-radius: 50%;
			cursor: pointer;
			z-index: 1001;
		}

		.notif-icon i {
			color: #fff;
		}


		.gizi-baik {
			background-color: green;
			color: white;
			border: none;
			padding: 5px 10px;
			cursor: pointer;
		}

		.gizi-kurang {
			background-color: yellow;
			color: black;
			border: none;
			padding: 5px 10px;
			cursor: pointer;
		}

		.gizi-beresiko {
			background-color: pink;
			color: black;
			border: none;
			padding: 5px 10px;
			cursor: pointer;
		}

		.gizi-lebih {
			background-color: red;
			color: white;
			border: none;
			padding: 5px 10px;
			cursor: pointer;
		}

		.gizi-tidak-diketahui {
			background-color: grey;
			color: white;
			border: none;
			padding: 5px 10px;
			cursor: pointer;
		}

		.nav-item .nav-link.active {
			background-color: rgba(255, 255, 255, 0.1);
			border-radius: 15px;
		}

		.nav-item .nav-link:hover {
			background-color: rgba(255, 255, 255, 0.2);
			border-radius: 15px;
		}

		.disabled-row {
			background-color: #adadad;
			/* Warna abu-abu gelap */
			pointer-events: none;
			/* Menonaktifkan interaksi pengguna */
			opacity: 0.7;
			/* Membuat baris terlihat lebih redup */
			color: #6c757d;
			/* Membuat baris terlihat kurang menonjol */
		}

		@media print {
			body * {
				visibility: hidden;
			}

			#result-container,
			#result-container * {
				visibility: visible;
			}

			#result-container {
				position: absolute;
				left: 0;
				top: 0;
			}
		}


		.container {
			background-color: white;
			border-radius: 15px;
			box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
			padding: 30px;
			width: 100%;
			max-width: 500px;
		}

		h1 {
			text-align: center;
			font-size: 24px;
			color: #333;
		}
	</style>
	<script>
		$(document).ready(function () {
			$('.nav-item .nav-link').on('click', function () {
				$('.nav-item .nav-link').removeClass('active');
				$(this).addClass('active');
			});
		});
	</script>

	<style>
		.error {
			color: red;
			font-size: 30px;
		}
	</style>
</head>


<body id="page-top">

	<!-- Page Wrapper -->
	<div id="wrapper">

		<!-- Sidebar -->
		<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #132d60;">

			<!-- Sidebar - Brand -->
			<a class="sidebar-brand d-flex align-items-center justify-content-center" href="">
				<div class="sidebar-brand-icon rotate-n-15">

				</div>
				<div class="sidebar-brand-icon">
					<!-- Add the logo image here -->
					<img src="<?= base_url('assets/foto/logo.jpeg'); ?>" alt="Logo"
						style="height: 40px; margin-right: 10px;">
				</div>
				<div class="sidebar-brand-text mx-3">Absensi</div>
			</a>

			<!-- Divider -->
			<hr class="sidebar-divider my-0">

			<!-- Nav Item - Dashboard -->
			<?php if ($this->session->userdata('role') == 'Admin'): ?>
				<li class="nav-item active">
					<a class="nav-link" href="<?= base_url('home'); ?>">
						<i class="fas fa-fw fa-tachometer-alt"></i>
						<span>Dashboard</span></a>
				</li>



				<li class="nav-item">
					<a class="nav-link" href="<?= base_url('admin'); ?>">
						<i class="fas fa-fw fa-user-tie"></i>
						<span>Data User</span></a>
				</li>
			<?php endif; ?>
			<li class="nav-item">
				<a class="nav-link" href="<?= base_url('absen'); ?>">
					<i class="fas fa-fw fa-camera-alt"></i>
					<span>Absen</span></a>
			</li>
			<?php if ($this->session->userdata('role') == 'Admin'): ?>
				<li class="nav-item">
					<a class="nav-link" href="<?= base_url('history'); ?>">
						<i class="fas fa-fw fa-history"></i>
						<span>History Absensi</span></a>
				</li>
			<?php endif; ?>




			<hr class="sidebar-divider d-none d-md-block">

			<li class="nav-item">
				<a class="nav-link" href="<?= base_url('Auth/LogoutController'); ?>">
					<i class="fas fa-fw fa-sign-out"></i>
					<span>Logout</span></a>
			</li>

			<!-- Sidebar Toggler (Sidebar) -->
			<div class="text-center d-none d-md-inline">
				<button class="rounded-circle border-0" id="sidebarToggle"></button>
			</div>

			<!-- Sidebar Message -->


		</ul>

		<!-- End of Sidebar -->

		<!-- Content Wrapper -->
		<div id="content-wrapper" class="d-flex flex-column">

			<!-- Main Content -->
			<div id="content">

				<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

					<!-- Topbar Navbar -->

					<ul class="navbar-nav ml-auto">

						<!-- Nav Item - Alerts -->

						<li class="nav-item dropdown no-arrow mx-1">

							<a class="nav-link dropdown-toggle" style="color: black;" href="#" id="alertsDropdown"
								role="button" aria-haspopup="true" aria-expanded="false">

								Hi, <?= $this->session->userdata('name') ?>&nbsp;&nbsp;

								<i class="fas fa-user" style="margin-right : 8px"></i>
								<!-- Ikon orang dari Font Awesome -->
							</a>
							<!-- Dropdown - Alerts -->
							<div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
								aria-labelledby="alertsDropdown">
								<h6 class="dropdown-header">
									Notifikasi
								</h6>
								<a class="dropdown-item d-flex align-items-center" href="#">
									<div class="mr-3">
										<div class="icon-circle bg-primary">
											<i class="fas fa-file-alt text-white"></i>
										</div>
									</div>

								</a>
								<a class="dropdown-item d-flex align-items-center" href="#">
									<div class="mr-3">
										<div class="icon-circle bg-success">
											<i class="fas fa-check-circle text-white"></i>
										</div>
									</div>

								</a>
								<a class="dropdown-item d-flex align-items-center" href="#">
									<div class="mr-3">
										<div class="icon-circle bg-warning">
											<i class="fas fa-exclamation-triangle text-white"></i>
										</div>
									</div>

								</a>
								<a class="dropdown-item text-center small text-gray-500" href="#">Tampilkan semua
									notifikasi</a>
							</div>
						</li>

					</ul>

				</nav>
				<!-- End of Topbar -->

				<div class="container-fluid">


					<script>
						document.addEventListener("DOMContentLoaded", function () {
							// Menangkap elemen badge-counter
							var badgeCounter = document.querySelector('.badge-counter');

							// Menambahkan event listener untuk menghilangkan notifikasi ketika notifikasi di-klik
							badgeCounter.addEventListener('click', function () {
								// Mengubah teks badge menjadi kosong
								badgeCounter.innerHTML = '';
								// Atau Anda bisa menghapus seluruh elemen span badge-counter jika ingin menghilangkannya sepenuhnya
								// badgeCounter.parentNode.removeChild(badgeCounter);
							});
						});
					</script>





					<!-- /.container-fluid -->
