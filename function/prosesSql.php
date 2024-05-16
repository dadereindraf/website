<?php
// Koneksi ke database (ganti dengan koneksi yang sesuai)
include '../koneksi.php';

// Fungsi untuk menjalankan perintah SQL
function jalankanSQL() {
    global $koneksi;
    $sql = "INSERT INTO hasil (id, username, text, label, cleaned_text, tokens, tokens_replaced, tokens_without_stopwords, stemmed_tokens)
            SELECT MAX(id), MAX(username), MAX(text), MAX(label), MAX(cleaned_text), MAX(tokens), MAX(tokens_replaced), MAX(tokens_without_stopwords), MAX(stemmed_tokens)
            FROM preprocessing
            WHERE text IS NOT NULL AND text <> ''
            GROUP BY text";
    
    if (mysqli_query($koneksi, $sql)) {
        // Redirect kembali ke hasilpreprocessing.php
        header("Location: ../hasilPreprocessing.php");
        exit(); // Penting: Pastikan tidak ada output lain sebelum header di atas
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($koneksi);
    }
}

// Periksa apakah tombol telah ditekan
if(isset($_POST['submit'])) {
    jalankanSQL();
}
?>
