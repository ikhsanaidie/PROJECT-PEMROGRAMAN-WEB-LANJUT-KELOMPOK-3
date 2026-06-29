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

    $username = $_SESSION['username'];

    $password_lama = md5($_POST['password_lama']);
    $password_baru = md5($_POST['password_baru']);
    $konfirmasi = md5($_POST['konfirmasi']);

    if ($password_baru != $konfirmasi) {

        $error = "Konfirmasi password tidak sama.";
    } else {

        $cek = mysqli_query($conn, "
            SELECT *
            FROM tbl_user
            WHERE username='$username'
            AND password='$password_lama'
        ");

        if (mysqli_num_rows($cek) == 1) {

            mysqli_query($conn, "
                UPDATE tbl_user
                SET password='$password_baru'
                WHERE username='$username'
            ");

            $pesan = "Password berhasil diubah.";
        } else {

            $error = "Password lama salah.";
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

        <a
            href="../dashboard/index.php"
            class="btn btn-secondary">

            Kembali

        </a>

    </form>

</div>

<?php include "../includes/footer.php"; ?>