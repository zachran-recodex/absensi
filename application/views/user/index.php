<style>
    .video-container {
        position: relative;
        width: 100%;
        padding-top: 56.25%; /* Aspect ratio 16:9 (height = 9 / 16 * width) */
        background-color: black;
        overflow: hidden;
        border-radius: 10px;
    }

    video, canvas {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 10px;
    }

    /* Tambahkan efek tombol capture */
    #capture {
        display: block;
        width: 100%;
        max-width: 200px;
        margin: 10px auto;
        font-size: 16px;
        font-weight: bold;
        border-radius: 50px;
        transition: background-color 0.3s;
    }

    #capture:hover {
        background-color: #218838;
    }

    #capture:focus {
        outline: none;
    }
</style>
<div class="card">
    <div class="card-header">
        <!-- Page Heading -->
        <h1 class="h3 mb-3 text-gray-800">Kelola Data User</h1>

        <div class="card shadow mb-4">
            <div class="card-header py-2">
                <div class="my-2"></div>
                <?php if ($this->session->flashdata('message')): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $this->session->flashdata('message'); ?>
                    </div>
                <?php endif; ?>
                <a href="<?= base_url('admin/tambah'); ?>" class="btn btn-primary mt-1">Tambah Data</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $no = 1; foreach ($data_admin as $cs) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $cs['name']; ?></td>
                                    <td><?= $cs['username']; ?></td>
                                    <td><?= $cs['role']; ?></td>
                                    <td>
                                        <a href="<?= base_url() ?>admin/hapus/<?= $cs['id_user']; ?>" onclick="return confirm('Yakin Data Akan dihapus..?');" class="btn-small text-danger"><i class="fas fa-trash"></i> Hapus</a>
                                       
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Face Registration -->


<script defer src="<?= base_url() ?>/assets/models/face-api.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


