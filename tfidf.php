<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TF-IDF Features</title>
</head>
<body>
    <h1>TF-IDF Features</h1>
    <table border="1">
        <tr>
            <th>Word</th>
            <th>TF-IDF Value</th>
        </tr>
        <?php
        include 'koneksi.php';

        // Buat query SQL untuk mengambil data dari tabel
        $sql = "SELECT * FROM tfidf_table";

        // Eksekusi query dan ambil hasilnya
        $result = mysqli_query($conn, $sql);

        // Tampilkan data ke dalam tabel HTML
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['text'] . '</td>'; // Kata
                echo '<td>' . $row['tfidf_value'] . '</td>'; // Nilai TF-IDF
                echo '</tr>';
            }
        } else {
            echo "Tidak ada data yang ditemukan.";
        }

        // Tutup koneksi
        mysqli_close($conn);
        ?>
    </table>
</body>
</html>
