<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Hasil Pengujian</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">>
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/logoUBL.png">

    <!-- C3 Chart css -->
    <link href="assets/libs/c3/c3.min.css" rel="stylesheet" type="text/css" />

    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

    <style>
        /* CSS untuk mengubah warna tombol saat dihover */
        .btn-primary:hover {
            background-color: #0056b3 !important;
            border-color: #0056b3 !important;
        }
    </style>

    <style>
        .table-borderless td:first-child {
            width: 150px;
            /* Atur lebar kolom pertama sesuai keinginan */
            white-space: nowrap;
        }

        .table-borderless td:nth-child(2) {
            width: 10px;
            /* Atur lebar kolom kedua sesuai keinginan */
        }
    </style>

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
                        <div class="col-md-12 col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Hasil Pengujian</h5>
                                    <form method="post" action="function/prosesHasilPengujian.php?aksi=pengujian" style="display: inline;">
                                        <button type="submit" class="btn btn-primary" style="background-color: #007bff; border-color: #007bff;">Mulai Pengujian</button>
                                    </form>

                                    <?php
                                    include 'koneksi.php';

                                    // Tombol hapus (delete)
                                    if (isset($_POST["truncateHasilPengujian"])) {
                                        $sql_truncate = "TRUNCATE TABLE pengujian";
                                        if ($koneksi->query($sql_truncate) === TRUE) {
                                            echo "Semua baris berhasil dihapus dari tabel.";
                                        } else {
                                            echo "Error: " . $sql_truncate . "<br>" . $koneksi->error;
                                        }
                                    }
                                    ?>

                                    <button type="submit" name="truncateHasilPengujian" class="btn btn-danger float-right" data-toggle="modal" data-target="#myModal">Hapus Hasil Pengujian</button>

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
                                                        <button type="submit" name="truncateHasilPengujian" class="btn btn-primary waves-effect waves-light">Ya, yakin</button>
                                                    </form>
                                                </div>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->

                                    <?php
                                    include 'koneksi.php';

                                    // Mengambil data hasil pengujian dari tabel
                                    $sql = "SELECT * FROM pengujian ORDER BY id DESC LIMIT 1";
                                    $result = $koneksi->query($sql);

                                    if ($result->num_rows > 0) {
                                        // Menampilkan data hasil pengujian
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<table class='table table-borderless'>";
                                            echo "<tr><td><strong>Akurasi</strong></td><td>:</td><td>" . $row["akurasi"] . "</td></tr>";
                                            echo "<tr><td><strong>Precision 0</strong></td><td>:</td><td>" . $row["precision_0"] . "</td></tr>";
                                            echo "<tr><td><strong>Precision 1</strong></td><td>:</td><td>" . $row["precision_1"] . "</td></tr>";
                                            echo "<tr><td><strong>Average Precision</strong></td><td>:</td><td>" . $row["avg_precision"] . "</td></tr>";
                                            echo "<tr><td><strong>Recall 0</strong></td><td>:</td><td>" . $row["recall_0"] . "</td></tr>";
                                            echo "<tr><td><strong>Recall 1</strong></td><td>:</td><td>" . $row["recall_1"] . "</td></tr>";
                                            echo "<tr><td><strong>Average Recall</strong></td><td>:</td><td>" . $row["avg_recall"] . "</td></tr>";
                                            echo "<tr><td><strong>F1-Score 0</strong></td><td>:</td><td>" . $row["f1_score_0"] . "</td></tr>";
                                            echo "<tr><td><strong>F1-Score 1</strong></td><td>:</td><td>" . $row["f1_score_1"] . "</td></tr>";
                                            echo "<tr><td><strong>Average F1-Score</strong></td><td>:</td><td>" . $row["avg_f1_score"] . "</td></tr>";
                                            echo "</table>";

                                            echo "<p><strong>Confusion Matrix:</strong></p>";
                                            echo "<table class='table table-bordered'>";
                                            echo "<thead><tr><th></th><th>Prediksi Bukan Ujaran Kebencian</th><th>Prediksi Ujaran Kebencian</th></tr></thead>";
                                            echo "<tbody>";
                                            echo "<tr><td>Aktual Bukan Ujaran Kebencian</td><td>" . $row["confusion_matrix_00"] . "</td><td>" . $row["confusion_matrix_01"] . "</td></tr>";
                                            echo "<tr><td>Aktual Ujaran Kebencian</td><td>" . $row["confusion_matrix_10"] . "</td><td>" . $row["confusion_matrix_11"] . "</td></tr>";
                                            echo "</tbody></table>";
                                        }
                                    } else {
                                        echo "<p>Tidak ada hasil pengujian.</p>";
                                    }

                                    // Menutup koneksi
                                    $koneksi->close();
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- container-fluid -->
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
    <?php include 'rightSidebar.php' ?>
    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>


    <!-- Peity chart-->
    <script src="assets/libs/peity/jquery.peity.min.js"></script>

    <!--C3 Chart-->
    <script src="assets/libs/d3/d3.min.js"></script>
    <script src="assets/libs/c3/c3.min.js"></script>

    <script src="assets/libs/jquery-knob/jquery.knob.min.js"></script>

    <script src="assets/js/pages/dashboard.init.js"></script>

    <script src="assets/js/app.js"></script>

</body>

</html>