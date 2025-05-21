<!-- general form elements -->
<div class="card card-secondary">
	<div class="card-header">
		<h3 class="card-title">Halaman Tambah Data</h3>
	</div>
	<!-- /.card-header -->
	<!-- form start -->
	<?php if ($this->session->flashdata('message')): ?>
		<div class="alert alert-warning">
			<?php echo $this->session->flashdata('message'); ?>
		</div>
	<?php endif; ?>
	<?php echo form_open_multipart('admin/simpan'); ?>
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">

				<div class="form-group">
					<label for="nama">Nama</label>
					<input type="text" class="form-control" id="name" name="name" required>
				</div>
				<div class="form-group">
					<label for="username">Username</label>
					<input type="text" class="form-control" id="username" name="username" required>
				</div>
				<div class="form-group">
					<label for="username">Password</label>
					<input type="password" class="form-control" id="password" name="password" required>
				</div>

				<div class="form-group">
					<label for="hak_akses">Role</label>
					<div>
						<div class="form-check">
							<input class="form-check-input" type="radio" name="role" id="hak_akses_admin" value="Admin"
								required>
							<label class="form-check-label" for="hak_akses_admin">
								Admin
							</label>
						</div>
						<div class="form-check">
							<input class="form-check-input" type="radio" name="role" id="hak_akses_pbj_owner"
								value="Karyawan" required>
							<label class="form-check-label" for="hak_akses_pbj_owner">
								Karyawan
							</label>
						</div>

					</div>
				</div>
				<div class="form-group">
					<label for="photo">Foto</label>
					<input type="file" class="form-control" id="photo" name="photo" required>
				</div>




			</div>


		</div>
	</div>
	<!-- /.card-body -->
	<div class="card-footer">
		<button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
	</div>
	<?php echo form_close(); ?>
</div>
