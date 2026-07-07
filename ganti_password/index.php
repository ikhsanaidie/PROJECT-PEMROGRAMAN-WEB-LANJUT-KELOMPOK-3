<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: ../auth/login.php");
    exit;
}
include "../config/koneksi.php";
include "../includes/header.php";
/** @var mysqli $conn */

$pesan = "";
$error = "";

if (isset($_POST['simpan'])) {
    $role = $_SESSION['role'];

    // Tentukan identifier sesuai role (SESUAI session yang di-set saat login)
    if ($role == "admin") {
        $identifier = $_SESSION['username'];
    } elseif ($role == "guru") {
        $identifier = $_SESSION['nip'];
    } elseif ($role == "siswa") {
        $identifier = $_SESSION['nisn'] ?? $_SESSION['username'];
    } else {
        $identifier = "";
    }

    $password_lama = trim($_POST['password_lama']);
    $password_baru = trim($_POST['password_baru']);
    $konfirmasi    = trim($_POST['konfirmasi']);

    if ($password_baru !== $konfirmasi) {
        $error = "Konfirmasi password tidak sama.";
    } elseif ($identifier == "") {
        $error = "Sesi tidak valid. Silakan login ulang.";
    } else {
        // Tentukan tabel & kolom identifier sesuai role
        if ($role == "admin") {
            $tabel = "tbl_user";
            $kolom_id = "username";
        } elseif ($role == "guru") {
            $tabel = "tbl_guru";
            $kolom_id = "nip";
        } elseif ($role == "siswa") {
            $tabel = "tbl_siswa";
            $kolom_id = "nisn";
        } else {
            $tabel = "";
            $kolom_id = "";
        }

        if ($tabel != "") {
            // Cek password lama
            $sql = "SELECT * FROM $tabel WHERE $kolom_id = ? AND password = MD5(?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'ss', $identifier, $password_lama);
            mysqli_stmt_execute($stmt);
            $cek = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($cek) == 1) {
                // Update password baru
                $update = "UPDATE $tabel SET password = MD5(?) WHERE $kolom_id = ?";
                $stmt2 = mysqli_prepare($conn, $update);
                mysqli_stmt_bind_param($stmt2, 'ss', $password_baru, $identifier);

                if (mysqli_stmt_execute($stmt2)) {
                    $pesan = "Password berhasil diubah.";
                } else {
                    $error = "Gagal mengubah password.";
                }
                mysqli_stmt_close($stmt2);
            } else {
                $error = "Password lama salah.";
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = "Role tidak dikenali.";
        }
    }
}
?>

<div class="page-header">
    <h2>Ganti Password</h2>
</div>

<?php if ($pesan != ""): ?>
<div class="notification notification-success">
    ✅ <?php echo $pesan; ?>
</div>
<?php endif; ?>

<?php if ($error != ""): ?>
<div class="notification notification-danger">
    ❌ <?php echo $error; ?>
</div>
<?php endif; ?>

<div class="card">
<form method="POST">
    <div class="form-group">
        <label>Password Lama</label>
        <input
            type="password"
            name="password_lama"
            class="form-control"
            required>
    </div>
    <div class="form-group">
        <label>Password Baru</label>
        <input
            type="password"
            name="password_baru"
            class="form-control"
            required>
    </div>
    <div class="form-group">
        <label>Konfirmasi Password</label>
        <input
            type="password"
            name="konfirmasi"
            class="form-control"
            required>
    </div>
    <button
        type="submit"
        name="simpan"
        class="btn btn-primary">
        💾 Simpan Password
    </button>
    
        href="../dashboard/index.php"
        class="btn btn-secondary">
        Kembali
    </a>
</form>
</div>

<?php include "../includes/footer.php"; ?>