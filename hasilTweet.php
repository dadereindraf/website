<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Data Raw</title>
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
                                    <h5 class="card-title">Hasil Tweet</h5>

                                    <?php

                                    // Sisipkan file koneksi.php
                                    include 'koneksi.php';

                                    // Query untuk mengambil data
                                    $sql = "SELECT actual, predicted FROM probs";
                                    $result = mysqli_query($koneksi, $sql);

                                    if ($result === false) {
                                        // Handle error, contoh:
                                        die("Kueri SQL gagal: " . mysqli_error($koneksi));
                                    }

                                    // Inisialisasi variabel untuk menghitung kesamaan dan perbedaan
                                    $match_count = 0; // Jumlah kesamaan antara nilai aktual dan prediksi
                                    $mismatch_count = 0; // Jumlah perbedaan antara nilai aktual dan prediksi

                                    // Tampilkan data dalam tabel
                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            // Memeriksa apakah nilai aktual sama dengan nilai prediksi
                                            if ($row['actual'] == $row['predicted']) {
                                                $match_count++; // Menginkremenkan jumlah kesamaan
                                            } else {
                                                $mismatch_count++; // Menginkremenkan jumlah perbedaan
                                            }
                                        }
                                    } else {
                                        echo "<p>Tidak ada data ditemukan</p>";
                                    }

                                    // Menampilkan jumlah kesamaan dan perbedaan
                                    echo "<p>Jumlah Kesamaan (Match): <strong>$match_count</strong></p>";
                                    echo "<p>Jumlah Perbedaan (Mismatch): <strong>$mismatch_count</strong></p>";

                                    ?>

                                    <table id="datatable" class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Text</th>
                                                <th>Probabilitas NHS</th>
                                                <th>Probabilitas HS</th>
                                                <th>Actual</th>
                                                <th>Predict</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Sisipkan file koneksi.php
                                            include 'koneksi.php';

                                            // Query untuk mengambil data
                                            $sql = "SELECT u.text AS text, p.actual AS actual, p.predicted AS predicted, p.prob_class_0 AS prob0, p.prob_class_1 AS prob1 
                                                    FROM dataujibungtowel u 
                                                    LEFT JOIN probs p ON u.id = p.id";
                                            $result = mysqli_query($koneksi, $sql);

                                            if ($result === false) {
                                                // Handle error, contoh:
                                                die("Kueri SQL gagal: " . mysqli_error($koneksi));
                                            }

                                            // Tampilkan data dalam tabel
                                            if (mysqli_num_rows($result) > 0) {
                                                $nomor = 1;
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    // Menentukan warna latar belakang berdasarkan nilai kolom 'actual'
                                                    $actual_background_color = $row['actual'] == 1 ? 'red' : 'green';
                                                    // Menentukan warna latar belakang berdasarkan nilai kolom 'predicted'
                                                    $predicted_background_color = $row['predicted'] == 1 ? 'red' : 'green';

                                                    // Membandingkan nilai probabilitas
                                                    $prob0 = $row['prob0'];
                                                    $prob1 = $row['prob1'];

                                                    // Menentukan penanda tebal berdasarkan nilai probabilitas yang lebih besar
                                                    $prob0_bold = $prob0 > $prob1 ? 'font-weight: bold;' : '';
                                                    $prob1_bold = $prob1 > $prob0 ? 'font-weight: bold;' : '';

                                                    echo "<tr>";
                                                    echo "<td>" . $nomor . "</td>";
                                                    // echo "<td>" . $row['username'] . "</td>";
                                                    echo "<td>" . $row['text'] . "</td>";
                                                    echo "<td style='$prob0_bold'>" . $prob0 . "</td>";
                                                    echo "<td style='$prob1_bold'>" . $prob1 . "</td>";
                                                    echo "<td style='background-color: $actual_background_color; color: white; padding: 5px; text-align: center; font-weight: bold;'>" . ($row['actual'] == 1 ? 'Hate Speech' : 'Non Hate Speech') . "</td>";
                                                    echo "<td style='background-color: $predicted_background_color; color: white; padding: 5px; text-align: center; font-weight: bold;'>" . ($row['predicted'] == 1 ? 'Hate Speech' : 'Non Hate Speech') . "</td>";
                                                    echo "</tr>";
                                                    $nomor++;
                                                }
                                            } else {
                                                echo "<tr><td colspan='4'>Tidak ada data ditemukan</td></tr>";
                                            }

                                            ?>
                                        </tbody>
                                    </table>

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