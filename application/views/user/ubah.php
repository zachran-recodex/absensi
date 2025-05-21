<div class="card card-primary">
	<div class="card-header">
		<h6 class="m-0 font-weight-bold text-primary">Edit Data User</h6>
	</div>
	<!-- /.card-header -->
	<!-- form start -->
	<form action="" method="post">
		<div class="card-body">
			<input type="hidden" name="id" value="<?= $ubah_admin['id_user'] ?>">

			<div class="form-group">
				<label for="exampleInputEmail1">Nama</label>
				<input type="text" name="nama" value="<?= $ubah_admin['nama'] ?>" class="form-control" required>
			</div>


			<div class="form-group">
				<label for="exampleInputEmail1">Username</label>
				<input type="text" name="username" value="<?= $ubah_admin['username'] ?>" class="form-control" required>
			</div>
			<div class="form-group">
				<label for="exampleInputEmail1">Password</label>
				<input type="password" name="password" class="form-control">
			</div>

			<div class="form-group">
				<label for="hak_akses">Hak Akses</label>
				<div>
					<?php
					// Ambil hak akses dari database
					$hak_akses = $ubah_admin['hak_akses']; // Misalnya 'PBJ', 'PBJ TEAM', atau 'TM'
					
					// Buat array hak akses
					$akses_options = ['Admin', 'Owner'];

					foreach ($akses_options as $akses):
						$checked = ($hak_akses === $akses) ? 'checked' : '';
						?>
						<div class="form-check">
							<input class="form-check-input" type="radio" name="hak_akses"
								id="hak_akses_<?= strtolower(str_replace(' ', '_', $akses)) ?>" value="<?= $akses ?>"
								<?= $checked ?> required>
							<label class="form-check-label"
								for="hak_akses_<?= strtolower(str_replace(' ', '_', $akses)) ?>">
								<?= $akses ?>
							</label>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<!-- /.card-body -->
		<div class="card-footer">
			<button type="submit" name="tambah" class="btn btn-primary"><i class="fa fa-check"></i>&nbsp;Update</button>
		</div>
	</form>
</div>
<!-- /.card -->
