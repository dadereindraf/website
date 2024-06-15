<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Hasil Pengujian</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/logoUBL.png">

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

        .table-borderless td,
        .table-borderless th {
            border: none;
        }

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
                                    <h5 class="card-title">Hasil Evaluasi</h5>

                                    <?php
                                    include 'koneksi.php';

                                    // Mengambil data hasil pengujian dari tabel
                                    $sql = "SELECT * FROM pengujian";
                                    $result = $koneksi->query($sql);

                                    if ($result->num_rows > 0) {
                                        // Menampilkan data hasil pengujian
                                        while ($row = $result->fetch_assoc()) {
                                            // Menghitung nilai evaluasi
                                            $TP = $row["confusion_matrix_11"];
                                            $TN = $row["confusion_matrix_00"];
                                            $FP = $row["confusion_matrix_01"];
                                            $FN = $row["confusion_matrix_10"];

                                            $accuracy = ($TP + $TN) / ($TP + $TN + $FP + $FN);
                                            $precision = $TP / ($TP + $FP);
                                            $recall = $TP / ($TP + $FN);
                                            $f1_score = 2 * ($precision * $recall) / ($precision + $recall);

                                            echo "<p><strong>Confusion Matrix:</strong></p>";
                                            echo "<table class='table table-bordered'>";
                                            echo "<thead><tr><th></th><th>Prediksi Bukan Ujaran Kebencian</th><th>Prediksi Ujaran Kebencian</th></tr></thead>";
                                            echo "<tbody>";
                                            echo "<tr><td>Aktual Bukan Ujaran Kebencian</td><td>" . $row["confusion_matrix_00"] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>(TN)</strong></td><td>" . $row["confusion_matrix_01"] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>(FP)</td></tr>";
                                            echo "<tr><td>Aktual Ujaran Kebencian</td><td>" . $row["confusion_matrix_10"] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>(FN)</td><td>" . $row["confusion_matrix_11"] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>(TP)</td></tr>";
                                            echo "</tbody></table>";

                                            // Menampilkan evaluasi dengan formula LaTeX menggunakan MathJax
                                            echo "<table class='table table-borderless'>";
                                            echo "<tr><td><strong>Accuracy</strong></td><td>:</td><td>\( \\frac{TP+TN}{TP+TN+FP+FN} = \\frac{" . $TP . " + " . $TN . "}{" . $TP . " + " . $TN . " + " . $FP . " + " . $FN . "} = " . number_format($accuracy * 100, 2) . "\\% \)</td></tr>";
                                            echo "<tr><td><strong>Precision</strong></td><td>:</td><td>\( \\frac{TP}{TP+FP} = \\frac{" . $TP . "}{" . $TP . " + " . $FP . "} = " . number_format($precision * 100, 2) . "\\% \)</td></tr>";
                                            echo "<tr><td><strong>Recall</strong></td><td>:</td><td>\( \\frac{TP}{TP+FN} = \\frac{" . $TP . "}{" . $TP . " + " . $FN . "} = " . number_format($recall * 100, 2) . "\\% \)</td></tr>";
                                            echo "<tr><td><strong>F1-Score</strong></td><td>:</td><td>\( 2 \\times \\frac{Precision\\times Recall}{Precision+Recall} = 2 \\times \\frac{" . number_format($precision, 2) . " \\times " . number_format($recall, 2) . "}{" . number_format($precision, 2) . " + " . number_format($recall, 2) . "} = " . number_format($f1_score * 100, 2) . "\\% \)</td></tr>";
                                            echo "</table>";
                                        }
                                    } else {
                                        echo "<div class='row mt-4'>
                                            <div class='col-md-12'>
                                                <div class='alert alert-warning text-center' role='alert'>
                                                    Tidak ada data evaluasi yang ditemukan.
                                                </div>
                                            </div>
                                        </div>";
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

    <!-- MathJax -->
    <script type="text/javascript" async src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.7/MathJax.js?config=TeX-MML-AM_CHTML">
    </script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>

</body>

</html>