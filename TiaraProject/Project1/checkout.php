<?php
// Ambil data keranjang dari parameter POST (dikirim dari produk.htm)
session_start();
if (!isset($_POST['cart_data'])) {
  echo "Tidak ada data keranjang.";
  exit;
}

$cartData = json_decode($_POST['cart_data'], true);
if (!$cartData || count($cartData) === 0) {
  echo "Keranjang kosong.";
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Checkout - LAKSMIÉ</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, sans-serif;
      background-color: #fffaf9;
      padding: 20px;
      color: #444;
    }

    .checkout-container {
      max-width: 700px;
      margin: 0 auto;
      background: #ffffff;
      padding: 25px 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
    }

    h2 {
      color: #f2a5b5;
      margin-top: 0;
      text-align: center;
    }

    label {
      display: block;
      margin: 12px 0 5px;
      font-weight: 500;
    }

    input, textarea, select {
      width: 100%;
      padding: 10px 12px;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      border-radius: 6px;
      font-size: 14px;
    }

    button {
      background-color: #f2a5b5;
      color: white;
      padding: 12px 24px;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      width: 100%;
      font-size: 16px;
    }

    button:hover {
      background-color: #e08fa2;
    }

    .order-summary {
      background-color: #fff0f3;
      padding: 15px 20px;
      margin-bottom: 25px;
      border: 1px solid #f5c2cc;
      border-radius: 6px;
    }

    .order-summary h4 {
      margin-top: 0;
      color: #d25b79;
    }

    .order-summary p {
      margin: 6px 0;
      font-size: 14px;
    }

    @media (max-width: 768px) {
      .checkout-container {
        margin: 15px;
      }
    }
  </style>
</head>
<body>
  <div class="checkout-container">
    <h2>Formulir Checkout LAKSMIÉ</h2>

    <div class="order-summary">
      <h4>Ringkasan Pesanan:</h4>
<?php
$total = 0;
foreach ($cartData as $item) {
  echo "<p><strong>{$item['nama']}</strong> - Rp " . number_format($item['harga'], 0, ',', '.') . "</p>";
  $total += $item['harga'];
}
?>
<p><strong>Total: Rp <?= number_format($total, 0, ',', '.') ?></strong></p>


    <form action="simpan_pemesanan.php" method="post">
      <label for="nama_pembeli">Nama Pembeli:</label>
      <input type="text" name="nama_pembeli" id="nama_pembeli" required>

      <label for="alamat">Alamat:</label>
      <textarea name="alamat" id="alamat" required></textarea>

      <label for="no_hp">No. HP:</label>
      <input type="text" name="no_hp" id="no_hp" required>

      <label for="metode">Metode Pembayaran:</label>
      <select name="metode" id="metode" required>
        <option value="">-- Pilih Metode --</option>
        <option value="Transfer Bank">Transfer Bank</option>
        <option value="COD (Bayar di Tempat)">COD (Bayar di Tempat)</option>
        <option value="E-Wallet (OVO/Gopay/Dana)">E-Wallet (OVO/Gopay/Dana)</option>
      </select>

      <input type="hidden" name="cart_data" value='<?= json_encode($cartData) ?>'>
      <input type="hidden" name="total_harga" value="<?= $total ?>">

      <button type="submit">Kirim Pesanan</button>
    </form>
  </div>
</body>
</html>
