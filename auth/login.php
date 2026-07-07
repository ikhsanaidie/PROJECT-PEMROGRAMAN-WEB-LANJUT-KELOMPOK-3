<?php
// auth/login.php
session_start();
if (isset($_SESSION['role'])) {
    header('Location: ../dashboard/');
    exit;
}
include '../config/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIAKAD</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" href="../assets/img/smapgri4.png">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-left">
                <div class="login-logo">
                    <img src="../assets/img/smapgri4.png" alt="Logo">
                    <h1>SMA PGRI 4 JAKARTA</h1>
                    <p>SISTEM INFORMASI AKADEMIK</p>
                </div>
                <form action="proses_login.php" method="POST">
                    <div class="form-group">
                        <label>Login Sebagai</label>
                        <select name="role" class="form-control" required>
                            <option value="Administrator">Administrator</option>
                            <option value="Guru">Guru</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Username / NIP</label>
                        <div class="input-with-icon">
                            <span class="input-icon">👤</span>
                            <input type="text" name="username" class="form-control" placeholder="Masukkan username/NIP" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <div class="input-with-icon" style="position:relative;">
                            <span class="input-icon">🔒</span>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required style="padding-right:40px;">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">LOGIN</button>
                </form>
            </div>
            <div class="login-right">
                <div class="icon-big">🎓</div>
                <div class="quote-icon">"</div>
                <div class="quote-text" id="quoteText">Pendidikan adalah senjata paling ampuh untuk mengubah dunia.</div>
                <div class="quote-icon" style="margin-top:-10px;">"</div>
                <div class="quote-source">— Inspirasi Pendidikan —</div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            if (!input) return;

            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
        }

        const quotes = [
            "Pendidikan adalah senjata paling ampuh untuk mengubah dunia.",
            "Belajar tanpa berpikir tidak ada gunanya, berpikir tanpa belajar itu berbahaya.",
            "Ilmu tanpa agama buta, agama tanpa ilmu lumpuh.",
            "Hiduplah seolah engkau mati besok, belajarlah seolah engkau hidup selamanya.",
            "Pendidikan bukanlah persiapan untuk hidup, pendidikan adalah kehidupan itu sendiri.",
            "Jadilah manusia yang bermanfaat bagi orang lain.",
            "Tuntutlah ilmu dari buaian hingga liang lahat.",
            "Sebaik-baik manusia adalah yang paling bermanfaat bagi orang lain."
        ];
        let idx = 0;
        setInterval(() => {
            idx = (idx + 1) % quotes.length;
            document.getElementById('quoteText').textContent = quotes[idx];
        }, 7000);
    </script>
</body>
</html>