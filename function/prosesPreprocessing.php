<?php
include "../koneksi.php";    
if($_GET['aksi'] == "preprocessing") {
    // Jalankan skrip Python
    $output = passthru("python ../preprocessing.py");

    // Tampilkan pesan ke pengguna
    echo "Data telah diproses.";

    // Redirect pengguna ke halaman cleansing.php setelah beberapa detik
    header("location: " . $_SERVER['HTTP_REFERER']);
}
?>
