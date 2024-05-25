<?php

// Koneksi ke database
include '../koneksi.php';

// Atur seed untuk generator angka acak
mt_srand(42);

// Ambil data dari tabel 'hasil'
$sql = "SELECT username, text, label FROM hasil";
$result = $koneksi->query($sql);

// Pisahkan data ke dalam kelas yang berbeda
$hate_speech_data = [];
$non_hate_speech_data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['label'] == 1) {
            $hate_speech_data[] = $row;
        } else {
            $non_hate_speech_data[] = $row;
        }
    }
} else {
    echo "Tidak ada data ditemukan";
}

// Hitung jumlah sampel dari kelas minoritas (label 1)
$minority_count = count($hate_speech_data);

// Tentukan jumlah sampel yang diinginkan setelah undersampling
$desired_count = $minority_count; // Menyesuaikan dengan jumlah sampel dari kelas minoritas

// Lakukan undersampling pada kelas 'non_hate_speech'
$undersampled_data = array_merge($hate_speech_data, array_slice($non_hate_speech_data, 0, $desired_count));

// Acak urutan hasil undersampling (meskipun seed tetap 42)
shuffle($undersampled_data);

// Simpan hasil undersampling ke dalam tabel baru 'undersampling'
$table_name = 'undersampling';
foreach ($undersampled_data as $data) {
    $username = $data['username'];
    $text = $data['text'];
    $label = $data['label'];
    $sql = "INSERT INTO $table_name (username, text, label) VALUES ('$username', '$text', '$label')";
    if ($koneksi->query($sql) !== TRUE) {
        echo "Error: " . $sql . "<br>" . $koneksi->error;
    }
}

// Output pesan berhasil
echo "Hasil undersampling telah disimpan ke dalam tabel '$table_name'";

// Tutup koneksi ke database
$koneksi->close();

// Redirect ke hasilUndersampling.php
header("Location: ../dataUndersampling.php");
exit;

?>
