</div>



<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Absensi</span>
        </div>
    </div>
</footer>
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->




<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="<?= base_url(); ?>assets/vendor/jquery/jquery.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?= base_url(); ?>assets/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="<?= base_url(); ?>assets/js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->

<!-- Page level custom scripts -->
<!-- data tabel -->
<script src="<?= base_url(); ?>assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Page level custom scripts -->
<script src="<?= base_url(); ?>assets/js/demo/datatables-demo.js"></script>

<script src="<?= base_url(); ?>assets/js/sweetalert2.all.min.js"></script>
<script src="<?= base_url(); ?>assets/js/myscript.js"></script>
<script src="<?= base_url(); ?>assets/vendor/select2/js/select2.full.min.js"></script>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


   
<script>
    function data_login() {
        // var tahun = $('#id_siswa option:selected').data('tahun_angkatan');
        var nml = $('#id_siswa option:selected').data('nis');
        // if (!isNaN(tahun)) {
        //     document.getElementById('id_tahun').value = tahun;
        // } else {
        //     document.getElementById('id_tahun').value = 0;
        // }

        if (!isNaN(nml)) {
            document.getElementById('username').value = nml;
        } else {
            document.getElementById('username').value = 0;
        }
    }
</script>



</body>

</html>
