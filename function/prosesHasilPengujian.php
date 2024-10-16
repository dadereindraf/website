<?php
include "../koneksi.php";    
if($_GET['aksi'] == "pengujian") {
    // Jalankan skrip Python
    $output = passthru("python ../metode.py");

    // Tampilkan pesan ke pengguna
    echo "Data telah diproses.";

    // Redirect pengguna ke halaman cleansing.php setelah beberapa detik
    header("location: " . $_SERVER['HTTP_REFERER']);
}
?>
