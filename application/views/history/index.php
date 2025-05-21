<div class="card">
    <div class="card-header">

        <!-- Page Heading -->
        <h1 class="h3 mb-3 text-gray-800">History Absensi</h1>

        <div class="card shadow mb-4">
            <div class="card-header py-2">
                <div class="my-2"></div>
                <?php echo $this->session->flashdata('message'); ?>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
								<th>Tanggal Absen</th>
								<th>Nama User</th>
                                <th>Role</th>
								<th>Jam Masuk</th>
								<th>Jam Keluar</th>
                            </tr>
                        </thead>

                        <tbody>
                          
                            <?php
                            $no = 1;
                            foreach ($absen as $cs) :
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
									<td><?= $cs['absen_time']; ?></td>
									<td><?= $cs['name']; ?></td>
                                    <td><?= $cs['role']; ?></td>
                                    <td><?= $cs['jam_masuk']; ?></td>
									<td><?= $cs['jam_keluar']; ?></td>
									
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>   
</div>
</div>


