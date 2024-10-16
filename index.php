<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">>

    <link rel="shortcut icon" href="assets/images/logoUBL.png">

    <link href="assets/libs/c3/c3.min.css" rel="stylesheet" type="text/css" />

    <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />

    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />

    <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

</head>

<body data-sidebar="dark">

    <!-- Loader -->
    <div id="preloader">
        <div id="status">
            <div class="spinner"></div>
        </div>
    </div>
    <!-- End of Loader -->

    <!-- Begin page -->
    <div id="layout-wrapper">

        <!-- Navbar Header -->
        <?php include 'navbar.php' ?>
        <!-- End of Navbar -->

        <!-- Left Sidebar-->
        <?php include 'leftSidebar.php' ?>
        <!-- End of Left Sidebar -->

        <!-- ============================================================== -->
        <!--                          ISI KONTEN                            -->
        <!-- ============================================================== -->
        <div class="main-content">

            <!-- Page Content -->
            <div class="page-content">

                <!-- Container -->
                <div class="container-fluid">

                    <div class="row">

                        <!-- Data Raw-->
                        <?php
                        include 'koneksi.php';

                        $query = "SELECT COUNT(*) AS total_rows FROM dataraw";
                        $result = mysqli_query($koneksi, $query);

                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            $total_rows_dataraw = $row['total_rows'];
                        } else {
                            $total_rows_dataraw = "Error: " . mysqli_error($koneksi);
                        }

                        echo "<div class='col-md-6 col-xl-2'>
                            <div class='card' >
                                <a href='dataRaw.php' style='text-decoration: none; color: inherit;'>
                                    <div class='card-body'>
                                        <h5 class='card-title'>Data Raw</h5>
                                        <p class='text-danger'><strong style='font-size: 30px;'>$total_rows_dataraw</strong></p>
                                    </div>
                                </a>
                            </div>
                        </div>";
                        ?>
                        <!-- End of Data Raw-->

                        <!-- Data Preprocessing-->
                        <?php
                        include 'koneksi.php';

                        $query_preprocessing = "SELECT COUNT(*) AS total_rows FROM hasil";
                        $result_preprocessing = mysqli_query($koneksi, $query_preprocessing);

                        if ($result_preprocessing) {
                            $row_preprocessing = mysqli_fetch_assoc($result_preprocessing);
                            $total_rows_preprocessing = $row_preprocessing['total_rows'];
                        } else {
                            $total_rows_preprocessing = "Error: " . mysqli_error($koneksi);
                        }

                        echo "<div class='col-md-6 col-xl-3'>
                                <div class='card'>
                                    <a href='dataRaw.php' style='text-decoration: none; color: inherit;'>
                                        <div class='card-body'>
                                            <h5 class='card-title'>Data Preprocessing</h5>
                                            <p class='text-warning'><strong style='font-size: 30px;'>$total_rows_preprocessing</strong></p>
                                        </div>
                                    </a>
                                </div>
                            </div>";
                        ?>
                        <!-- End of Data Preprocessing -->

                        <!-- Data Undersampling-->
                        <?php
                        include 'koneksi.php';

                        $query_undersampling = "SELECT COUNT(*) AS total_rows FROM undersampling";
                        $result_undersampling = mysqli_query($koneksi, $query_undersampling);

                        if ($result_undersampling) {
                            $row_undersampling = mysqli_fetch_assoc($result_undersampling);
                            $total_rows_undersampling = $row_undersampling['total_rows'];
                        } else {
                            $total_rows_undersampling = "Error: " . mysqli_error($koneksi);
                        }

                        echo "<div class='col-md-6 col-xl-3'>
                                <div class='card'>
                                    <div class='card-body'>
                                        <h5 class='card-title'>Data Setelah Undersampling</h5>
                                        <p class='text-success'><strong style='font-size: 30px;'>$total_rows_undersampling</strong></p>
                                    </div>
                                </div>
                            </div>";
                        ?>
                        <!-- End of Data Undersampling -->

                        <!-- Data Latih-->
                        <?php
                        include 'koneksi.php';

                        $query_latih = "SELECT COUNT(*) AS total_rows FROM datalatihbungtowel";
                        $result_latih = mysqli_query($koneksi, $query_latih);

                        if ($result_latih) {
                            $row_latih = mysqli_fetch_assoc($result_latih);
                            $total_rows_latih = $row_latih['total_rows'];
                        } else {
                            $total_rows_latih = "Error: " . mysqli_error($koneksi);
                        }

                        echo "<div class='col-md-6 col-xl-2'>
                                <div class='card'>
                                    <div class='card-body'>
                                        <h5 class='card-title'>Data Latih</h5>
                                        <p><strong style='font-size: 30px;'>$total_rows_latih</strong></p>
                                    </div>
                                </div>
                            </div>";
                        ?>
                        <!-- End of Data Latih -->

                        <!-- Data Uji-->
                        <?php
                        include 'koneksi.php';

                        $query_uji = "SELECT COUNT(*) AS total_rows FROM dataujibungtowel";
                        $result_uji = mysqli_query($koneksi, $query_uji);

                        if ($result_uji) {
                            $row_uji = mysqli_fetch_assoc($result_uji);
                            $total_rows_uji = $row_uji['total_rows'];
                        } else {
                            $total_rows_uji = "Error: " . mysqli_error($koneksi);
                        }

                        echo "<div class='col-md-6 col-xl-2'>
                                <div class='card'>
                                    <div class='card-body'>
                                        <h5 class='card-title'>Data Uji</h5>
                                        <p><strong style='font-size: 30px;'>$total_rows_uji</strong></p>
                                    </div>
                                </div>
                            </div>";
                        ?>
                        <!-- End of Data Uji -->

                    </div>

                    <div class="row">

                        <!-- Label Data Sebelum Undersampling -->
                        <?php
                        include 'koneksi.php';

                        $query_labelData = "SELECT label, COUNT(*) AS jumlah
                        FROM hasil
                        GROUP BY label;";
                        $result_labelData = mysqli_query($koneksi, $query_labelData);

                        $total_rows_label_0 = 0;
                        $total_rows_label_1 = 0;

                        if ($result_labelData) {
                            while ($row_labelData = mysqli_fetch_assoc($result_labelData)) {
                                if ($row_labelData['label'] == 0) {
                                    $total_rows_label_0 = $row_labelData['jumlah'];
                                } elseif ($row_labelData['label'] == 1) {
                                    $total_rows_label_1 = $row_labelData['jumlah'];
                                }
                            }
                        } else {
                            $total_rows_label_0 = "Error: " . mysqli_error($koneksi);
                            $total_rows_label_1 = "Error: " . mysqli_error($koneksi);
                        }
                        
                        $total = $total_rows_label_0 + $total_rows_label_1;
                        

                        echo "<div class='col-md-4'>
                                <div class='card'>
                                    <div class='card-body'>
                                        <h5 class='card-title'>Label Data</h5>
                                        <p>Ujaran Kebencian      : <strong>$total_rows_label_1</strong></p>
                                        <p>Bukan Ujaran Kebencian: <strong>$total_rows_label_0</strong></p>
                                        <p>Total: <strong>$total</strong></p>
                                    </div>
                                </div>
                            </div>";
                        ?>
                        <!-- End of Label Data Sebelum Undersampling -->

                        <!-- Label Data Setelah Undersampling -->
                        <?php
                        include 'koneksi.php';

                        $query_labelDataUndersampling = "SELECT label, COUNT(*) AS jumlah
                        FROM undersampling
                        GROUP BY label;";
                        $result_labelDataUndersampling = mysqli_query($koneksi, $query_labelDataUndersampling);

                        $total_rows_label_0 = 0;
                        $total_rows_label_1 = 0;

                        if ($result_labelDataUndersampling) {
                            while ($row_labelDataUndersampling = mysqli_fetch_assoc($result_labelDataUndersampling)) {
                                if ($row_labelDataUndersampling['label'] == 0) {
                                    $total_rows_label_0 = $row_labelDataUndersampling['jumlah'];
                                } elseif ($row_labelDataUndersampling['label'] == 1) {
                                    $total_rows_label_1 = $row_labelDataUndersampling['jumlah'];
                                }
                            }
                        } else {
                            $total_rows_label_0 = "Error: " . mysqli_error($koneksi);
                            $total_rows_label_1 = "Error: " . mysqli_error($koneksi);
                        }

                        echo "<div class='col-md-4'>
                                <div class='card'>
                                    <div class='card-body'>
                                        <h5 class='card-title'>Label Data Setelah Undersampling</h5>
                                        <p>Ujaran Kebencian     : <strong>$total_rows_label_1</strong></p>
                                        <p>Bukan Ujaran Kebencian: <strong>$total_rows_label_0</strong></p>
                                    </div>
                                </div>
                            </div>";
                        ?>
                        <!-- End of Label Data Setelah Undersampling -->
                    </div>

                    <!-- Hasil Pengujian -->
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Hasil Pengujian</h5>
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
                                        echo "<p>Tidak ada hasil pengujian.</p>";
                                    }

                                    // Menutup koneksi
                                    $koneksi->close();
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of Hasil Pengujian -->

                </div>
                <!-- End of Container -->

            </div>
            <!-- End of Page Content -->

            <!-- Footer -->
            <?php include 'footer.php' ?>
            <!-- End of Footer -->

        </div>
        <!-- ============================================================== -->
        <!--                        END OF ISI KONTEN                       -->
        <!-- ============================================================== -->

    </div>
    <!-- End of Begin Page -->

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

    <script type="text/javascript" async src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.7/MathJax.js?config=TeX-MML-AM_CHTML">
    </script>


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