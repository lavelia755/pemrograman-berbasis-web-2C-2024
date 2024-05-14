<?php
session_start();

// Pengecekan user sudah login atau belum, jika belum diarahkan ke halaman login
if(!isset($_SESSION['username'])){
    header('location: login.php');
    exit();
}

// Inisialisasi array data mahasiswa jika belum ada
if (!isset($_SESSION['data'])) {
    $_SESSION['data'] = array();
}

// Fungsi menambahkan data mahasiswa ke array
function tambahData($nama, $nim, $prodi, $alamat, $angkatan) {
    $data = array(
        'nama' => $nama,
        'nim' => $nim,
        'prodi' => $prodi,
        'alamat' => $alamat,
        'angkatan' => $angkatan
    );
    $_SESSION['data'][] = $data;
    $_SESSION['dataToUpdate'] = array('nim' => '', 'nama' => '', 'prodi' => '', 'alamat' => '', 'angkatan' => '');
}

// Fungsi update data mahasiswa array berdasarkan indeks
function updateData($index, $nim, $nama, $prodi, $alamat, $angkatan) {
    $_SESSION['data'][$index] = array(
        'nim' => $nim,
        'nama' => $nama,
        'prodi' => $prodi,
        'alamat' => $alamat,
        'angkatan' => $angkatan
    );
    $_SESSION['dataToUpdate'] = array('nim' => '', 'nama' => '', 'prodi' => '', 'alamat' => '', 'angkatan' => '');
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Fungsi hapus data mahasiswa array berdasarkan indeks
function hapusData($index) {
    if(isset($_SESSION['data'][$index])) {
        unset($_SESSION['data'][$index]);
    }
}

// Pemrosesan tombol submit dan update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        tambahData($_POST['nama'], $_POST['nim'], $_POST['prodi'], $_POST['alamat'], $_POST['angkatan']);
    } elseif (isset($_POST['update'])) {
        updateData($_POST['index'], $_POST['nim'], $_POST['nama'], $_POST['prodi'], $_POST['alamat'], $_POST['angkatan']);
    }
}

// Pemrosesan tombol delete
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['index'])) {
    hapusData($_GET['index']);
    header("Location: ".$_SERVER['PHP_SELF']); // Redirect kembali ke halaman ini setelah penghapusan
    exit;
}

// Menghapus session $_SESSION['dataToUpdate'] jika sudah tidak diperlukan
unset($_SESSION['dataToUpdate']);

// Mengambil data mahasiswa dari session
$data = isset($_SESSION['data']) ? $_SESSION['data'] : array();

// Jika aksi update/tidak update, dan indeks valid/tidak valid
if (isset($_GET['action']) && $_GET['action'] == 'update' && isset($_GET['index']) && isset($data[$_GET['index']])) {
    $index = $_GET['index'];
    $dataToUpdate = $data[$index];
} else {
    $dataToUpdate = array('nim' => '', 'nama' => '', 'prodi' => '', 'alamat' => '', 'angkatan' => '');
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <link rel="stylesheet" href="/230441100055-AbdBased/css/mahasiswa.css">
</head>
<body>
    <h1>Data Mahasiswa</h1>
    <form action="" method="POST">
        <input type="hidden" name="index" value="<?php echo isset($index) ? $index : ''; ?>">
        Nim: <input type="text" name="nim" value="<?php echo isset($dataToUpdate['nim']) ? $dataToUpdate['nim'] : ''; ?>" required><br>
        Nama: <input type="text" name="nama" value="<?php echo isset($dataToUpdate['nama']) ? $dataToUpdate['nama'] : ''; ?>" required><br>
        Prodi: <input type="text" name="prodi" value="<?php echo isset($dataToUpdate['prodi']) ? $dataToUpdate['prodi'] : ''; ?>" required><br>
        Alamat: <input type="text" name="alamat" value="<?php echo isset($dataToUpdate['alamat']) ? $dataToUpdate['alamat'] : ''; ?>" required><br>
        Angkatan: <input type="text" name="angkatan" value="<?php echo isset($dataToUpdate['angkatan']) ? $dataToUpdate['angkatan'] : ''; ?>" required><br>

        <?php if (isset($_GET['action']) && $_GET['action'] == 'update'): ?>
            <center><button type="submit" name="update">Update</button></center>
        <?php else: ?>
            <center><button type="submit" name="submit">Submit</button></center>
        <?php endif; ?>
    </form>
    <center>
    <table border="2">
        <tbody>
            <tr>
                <th>Nim</th>
                <th>Nama</th>
                <th>Prodi</th>
                <th>Alamat</th>
                <th>Angkatan</th>
                <th>Keterangan</th>
            </tr>
            <!-- Menampilkan data mahasiswa dari array -->
            <?php
            foreach ($data as $index => $row) {
                echo "<tr>";
                echo "<td>".$row['nim']."</td>";
                echo "<td>".$row['nama']."</td>";
                echo "<td>".$row['prodi']."</td>";
                echo "<td>".$row['alamat']."</td>";
                echo "<td>".$row['angkatan']."</td>";
                echo "<td><a href='?action=update&index=$index'>Update</a> | <a href='?action=delete&index=$index'>Delete</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    </center>
    <br>
    <center><a class="btn-logout" href="login.php">Logout</a></center>
</body>
</html>
