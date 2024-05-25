<?php

// Koneksi ke database
include '../koneksi.php';

// Atur seed untuk generator angka acak
mt_srand(23);

// Ambil data dari tabel 'undersampling'
$sql = "SELECT text, label FROM undersampling";
$result = $koneksi->query($sql);

/// Pisahkan data menjadi data latih dan data uji
$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    echo "Tidak ada data ditemukan";
}

// Hitung jumlah data dan tentukan proporsi untuk data latih dan data uji
$total_data = count($data);
$train_data_count = (int)($total_data * 0.8); // 80% data latih
$test_data_count = $total_data - $train_data_count; // 20% data uji

// Acak urutan data
shuffle($data);

// Bagi data menjadi data latih dan data uji
$data_latih = array_slice($data, 0, $train_data_count);
$data_uji = array_slice($data, $train_data_count);

// Simpan data latih ke dalam tabel 'datalatih'
foreach ($data_latih as $data) {
    $text = $data['text'];
    $label = $data['label'];
    $sql = "INSERT INTO datalatihbungtowel (text, label) VALUES ('$text', '$label')";
    if ($koneksi->query($sql) !== TRUE) {
        echo "Error: " . $sql . "<br>" . $koneksi->error;
    }
}

// Simpan data uji ke dalam tabel 'datauji'
foreach ($data_uji as $data) {
    $text = $data['text'];
    $label = $data['label'];
    $sql = "INSERT INTO dataujibungtowel (text, label) VALUES ('$text', '$label')";
    if ($koneksi->query($sql) !== TRUE) {
        echo "Error: " . $sql . "<br>" . $koneksi->error;
    }
}

// Output pesan berhasil
echo "Data latih dan data uji telah disimpan ke dalam tabel 'datalatih' dan 'datauji'";

// Tutup koneksi ke database
$koneksi->close();

// Redirect ke hasilUndersampling.php
header("Location: ../dataLatih.php");
exit;

?>
