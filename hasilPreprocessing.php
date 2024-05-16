<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Hasil Preprocessing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">>
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/logoUBL.png">

    <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

</head>

<body data-sidebar="dark">

    <!-- Loader -->
    <div id="preloader">
        <div id="status">
            <div class="spinner"></div>
        </div>
    </div>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <!-- Navbar Header -->
        <?php include 'navbar.php' ?>
        <!-- End of Navbar -->

        <!-- Left Sidebar-->
        <?php include 'leftSidebar.php' ?>
        <!-- End of Left Sidebar -->

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Hasil Preprocessing</h5>
                                    <form action="function/prosesSql.php" method="post">
                                        <button type="submit" class="btn btn-primary" name="submit">Cek Hasil Preprocessing</button>
                                    </form>

                                    <?php
                                    include 'koneksi.php';

                                    // Tombol hapus (delete)
                                    if (isset($_POST["truncateHasil"])) {
                                        $sql_truncate = "TRUNCATE TABLE hasil";
                                        if ($koneksi->query($sql_truncate) === TRUE) {
                                            echo "Semua baris berhasil dihapus dari tabel.";
                                        } else {
                                            echo "Error: " . $sql_truncate . "<br>" . $koneksi->error;
                                        }
                                    }
                                    ?>

                                    <button type="submit" name="truncateHasil" class="btn btn-danger float-right" data-toggle="modal" data-target="#myModal">Hapus Hasil Preprocessing</button>

                                    <!-- sample modal content -->
                                    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title mt-0" id="myModalLabel">Modal Heading</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                </div>
                                                <div class="modal-body">
                                                    <h5>Apakah yakin untuk menghapus data?</h5>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Tidak</button>
                                                    <form action="" method="POST">
                                                        <button type="submit" name="truncateHasil" class="btn btn-primary waves-effect waves-light">Ya, yakin</button>
                                                    </form>
                                                </div>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->

                                    <table id="datatable" class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Username</th>
                                                <th>Text</th>
                                                <th>Label</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Sisipkan file koneksi.php
                                            include 'koneksi.php';

                                            // Tentukan jumlah data per halaman
                                            $data_per_halaman = 50;

                                            // Hitung offset untuk query
                                            $halaman = isset($_GET['halaman']) ? $_GET['halaman'] : 1;
                                            $offset = ($halaman - 1) * $data_per_halaman;

                                            // Query untuk mengambil data
                                            $sql = "SELECT username, text, label FROM hasil LIMIT $offset, $data_per_halaman";
                                            $result = mysqli_query($koneksi, $sql);

                                            // Periksa apakah kueri berhasil dijalankan
                                            if ($result === false) {
                                                // Handle error, contoh:
                                                die("Kueri SQL gagal: " . mysqli_error($koneksi));
                                            }

                                            // Tampilkan data dalam tabel
                                            if (mysqli_num_rows($result) > 0) {
                                                $nomor = $offset + 1;
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $background_color = $row['label'] == 1 ? 'red' : 'green';

                                                    echo "<tr>";
                                                    echo "<td>" . $nomor . "</td>";
                                                    echo "<td>" . $row['username'] . "</td>";
                                                    echo "<td>" . $row['text'] . "</td>";
                                                    // Tambahkan style untuk warna latar belakang
                                                    echo "<td style='background-color: $background_color; color: white; padding: 5px; text-align: center; font-weight: bold;'>" . ($row['label'] == 1 ? 'Hate Speech' : 'Non Hate Speech') . "</td>";
                                                    echo "</tr>";
                                                    $nomor++;
                                                }
                                            } else {
                                                echo "<tr><td colspan='4'>Tidak ada data ditemukan</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <!-- Tombol "Previous" dan "Next" -->
                                    <div class="row mt-3">
                                        <div class="col-md-6 offset-md-3 text-center">
                                            <?php
                                            $sql_count = "SELECT COUNT(*) AS total FROM hasil";
                                            $result_count = mysqli_query($koneksi, $sql_count);
                                            $row_count = mysqli_fetch_assoc($result_count);
                                            $total_data = $row_count['total'];
                                            $total_halaman = ceil($total_data / $data_per_halaman);

                                            // Tombol "Previous"
                                            if ($halaman > 1) {
                                                $prev_halaman = $halaman - 1;
                                                echo "<a href='?halaman=$prev_halaman' class='btn btn-primary mr-2'>Previous</a>";
                                            }

                                            // Tombol nomor halaman
                                            for ($i = 1; $i <= $total_halaman; $i++) {
                                                $active_class = ($halaman == $i) ? 'active' : ''; // Tambahkan kelas 'active' jika halaman sedang aktif
                                                echo "<a href='?halaman=$i' class='btn btn-secondary $active_class'>$i</a>";
                                            }

                                            // Tombol "Next"
                                            if ($halaman < $total_halaman) {
                                                $next_halaman = $halaman + 1;
                                                echo "<a href='?halaman=$next_halaman' class='btn btn-primary ml-2'>Next</a>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- End Page-content -->


                <!-- ============== FOOTER ================-->

                <?php include 'footer.php' ?>

                <!-- ============== END OF FOOTER ================-->
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Right Sidebar -->
        <div class="right-bar">
            <div data-simplebar class="h-100">
                <div class="rightbar-title px-3 py-4">
                    <a href="javascript:void(0);" class="right-bar-toggle float-right">
                        <i class="mdi mdi-close noti-icon"></i>
                    </a>
                    <h5 class="m-0">Settings</h5>
                </div>

                <!-- Settings -->
                <hr class="mt-0" />
                <h6 class="text-center mb-0">Choose Layouts</h6>

                <div class="p-4">
                    <div class="mb-2">
                        <img src="assets/images/layouts/layout-1.jpg" class="img-fluid img-thumbnail" alt="">
                    </div>
                    <div class="custom-control custom-switch mb-3">
                        <input type="checkbox" class="custom-control-input theme-choice" id="light-mode-switch" checked />
                        <label class="custom-control-label" for="light-mode-switch">Light Mode</label>
                    </div>

                    <div class="mb-2">
                        <img src="assets/images/layouts/layout-2.jpg" class="img-fluid img-thumbnail" alt="">
                    </div>
                    <div class="custom-control custom-switch mb-3">
                        <input type="checkbox" class="custom-control-input theme-choice" id="dark-mode-switch" data-bsStyle="assets/css/bootstrap-dark.min.css" data-appStyle="assets/css/app-dark.min.css" />
                        <label class="custom-control-label" for="dark-mode-switch">Dark Mode</label>
                    </div>
                </div>

            </div> <!-- end slimscroll-menu-->
        </div>
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- JAVASCRIPT -->
        <script src="assets/libs/jquery/jquery.min.js"></script>
        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/libs/metismenu/metisMenu.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/node-waves/waves.min.js"></script>

        <!-- Required datatable js -->
        <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <!-- Datatable init js -->
        <script src="assets/js/pages/datatables.init.js"></script>

        <script src="assets/libs/jquery-knob/jquery.knob.min.js"></script>

        <script src="assets/js/pages/dashboard.init.js"></script>

        <script src="assets/js/app.js"></script>

</body>

</html>