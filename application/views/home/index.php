<style>
	table {
		width: 100%;
		max-width: 1000px;
		margin: 20px auto;
		border-collapse: collapse;
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
		text-align: center;
		border-radius: 10px;
		overflow: hidden;
	}

	th,
	td {
		padding: 12px 15px;
		border: 1px solid #ddd;
	}

	th {
		background-color: #ff4d6d;
		color: #fff;
		font-size: 16px;
		text-transform: uppercase;
	}

	td {
		background-color: #ffe3e9;
		color: #333;
		font-size: 14px;
	}

	tr:nth-child(even) td {
		background-color: #ffc0cb;
	}

	/* Button Styling */
	.btn {
		display: inline-block;
		padding: 12px 20px;
		border: none;
		border-radius: 8px;
		color: #fff;
		font-size: 16px;
		text-align: center;
		text-decoration: none;
		transition: all 0.3s ease;
		width: 100%;
	}

	.btn-primary {
		background-color: #007bff;
	}

	.btn-primary:hover {
		background-color: #0056b3;
	}

	.btn-danger {
		background-color: #dc3545;
	}

	.btn-danger:hover {
		background-color: #b02a37;
	}

	/* Icon Container */
	.icon-container {
		display: inline-block;
		padding: 12px;
		border: 2px solid #ccc;
		border-radius: 10px;
		transition: border-color 0.3s ease, transform 0.3s ease;
	}

	.icon-container:hover {
		border-color: #007bff;
		transform: scale(1.1);
	}

	/* Card Styling */
	.card {
		border: none;
		border-radius: 10px;
		box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
		overflow: hidden;
	}

	.card-body {
		padding: 20px;
	}

	/* Video Styling */
	.video-feed img {
		border-radius: 10px;
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
		width: 100%;
		max-width: 600px;
	}

	/* Responsive Design */
	@media (max-width: 768px) {
		table {
			font-size: 12px;
		}

		th,
		td {
			padding: 8px;
		}

		.btn {
			font-size: 14px;
		}
	}
</style>


<div class="row">
	<!-- Earnings (Monthly) Card Example -->
	<div class="col-xl-4 col-md-4 mb-3">
		<div class="card border-left-primary shadow h-100 py-2" style="border-radius: 8px;">
			<div class="card-body">
				<div class="row no-gutters align-items-center">
					<div class="col mr-2">
						<div class="text-xxs font-weight-bold text-primary text-uppercase mb-1">
							Jumlah User</div>
						<div class="h6 mb-0 font-weight-bold text-gray-800"><?php echo $jumlah_user; ?></div>
					</div>
					<div class="col-auto">
						<i class="fas fa-user-tie fa-2x text-gray-300"></i>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Earnings (Monthly) Card Example -->
	<div class="col-xl-4 col-md-4 mb-3">
		<div class="card border-left-success shadow h-100 py-2" style="border-radius: 8px;">
			<div class="card-body">
				<div class="row no-gutters align-items-center">
					<div class="col mr-2">
						<div class="text-xxs font-weight-bold text-success text-uppercase mb-1">
							Jumlah Absen Hari ini</div>
						<div class="h6 mb-0 font-weight-bold text-gray-800"><?php echo $jumlah_absen; ?></div>
					</div>
					<div class="col-auto">
						<i class="fas fa-camera-alt fa-2x text-gray-300"></i>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="col-xl-4 col-md-4 mb-3">
		<div class="card border-left-warning shadow h-100 py-2" style="border-radius: 8px;">
			<div class="card-body">
				<div class="row no-gutters align-items-center">
					<div class="col mr-2">
						<div class="text-xxs font-weight-bold text-warning text-uppercase mb-1">
							Jumlah Terlambat Hari ini</div>
						<div class="h6 mb-0 font-weight-bold text-gray-800"><?php echo $jumlah_telat; ?></div>
					</div>
					<div class="col-auto">
						<i class="fas fa-clock fa-2x text-gray-300"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
