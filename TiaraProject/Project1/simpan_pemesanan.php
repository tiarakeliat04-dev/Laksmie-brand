<?php
$conn = new mysqli("localhost", "root", "", "araskincare");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama    = $conn->real_escape_string($_POST['nama_pembeli']);
    $alamat  = $conn->real_escape_string($_POST['alamat']);
    $nohp    = $conn->real_escape_string($_POST['no_hp']);
    $keranjang = isset($_POST['cart_data']) ? json_decode($_POST['cart_data'], true) : [];

    if (!empty($keranjang)) {
        $tanggal = date('Y-m-d H:i:s');
        $total = 0;

        foreach ($keranjang as $item) {
            $total += intval($item['harga']);
        }

        // Simpan ke tabel pemesanan
        $conn->query("INSERT INTO pemesanan (nama_pembeli, alamat, no_hp, tanggal)
                      VALUES ('$nama', '$alamat', '$nohp', '$tanggal')");
        $id_pemesanan = $conn->insert_id;

        // Simpan detail pesanan
        foreach ($keranjang as $item) {
            $nama_produk = $conn->real_escape_string($item['nama']);
            $harga = intval($item['harga']);
            $jumlah = 1; // default 1 per klik
            $subtotal = $harga * $jumlah;

            $conn->query("INSERT INTO detailpesanan (id_pemesanan, nama_produk, harga, jumlah, subtotal)
                          VALUES ($id_pemesanan, '$nama_produk', $harga, $jumlah, $subtotal)");
        }

        // Tampilkan hasil
        echo "<h2>&#10004;  Pemesanan Berhasil</h2>";
        echo "<p><strong>Nama:</strong> $nama<br>";
        echo "<strong>Alamat:</strong> $alamat<br>";
        echo "<strong>No HP:</strong> $nohp<br>";
        echo "<strong>Tanggal:</strong> " . date('d-m-Y H:i', strtotime($tanggal)) . "</p>";

        echo "<h3>&#10004;  Detail Pesanan:</h3>";
        echo "<table border='1' cellpadding='8' cellspacing='0'>";
        echo "<tr><th>Nama Produk</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th></tr>";
        foreach ($keranjang as $item) {
            $harga = intval($item['harga']);
            $jumlah = 1;
            $subtotal = $harga * $jumlah;
            echo "<tr>
                    <td>{$item['nama']}</td>
                    <td>Rp " . number_format($harga, 0, ',', '.') . "</td>
                    <td>$jumlah</td>
                    <td>Rp " . number_format($subtotal, 0, ',', '.') . "</td>
                  </tr>";
        }
        echo "<tr>
                <td colspan='3' align='right'><strong>Total</strong></td>
                <td><strong>Rp " . number_format($total, 0, ',', '.') . "</strong></td>
              </tr>";
        echo "</table><br>";
        echo "<a href='produk.htm'>? Kembali ke Halaman Produk</a>";
    } else {
        echo "<p>?? Tidak ada produk yang dipesan.</p>";
    }
} else {
    echo "<p>?? Akses tidak valid.</p>";
}

$conn->close();
?>
