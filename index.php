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

                        echo "<div class='col-md-4'>
                                <div class='card'>
                                    <div class='card-body'>
                                        <h5 class='card-title'>Label Data</h5>
                                        <p>Ujaran Kebencian      : <strong>$total_rows_label_1</strong></p>
                                        <p>Bukan Ujaran Kebencian: <strong>$total_rows_label_0</strong></p>
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