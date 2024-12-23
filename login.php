<?php
session_start();
$status = isset($_GET['status']) ? $_GET['status'] : '';
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KremsGuesser - Anmelden</title>
    <link rel="stylesheet" href="stylemain.css">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .password-container {
        position: relative;
    }

    .password-toggle {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 18px;
        color: #6c757d;
    }

    .password-toggle:hover {
        color: #495057;
    }

    body {
        background: linear-gradient(135deg, #240046, #3C096C);
        color: white;
        font-family: Arial, sans-serif;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin: 0;
    }

    .card {
        background: #f8f9fa;
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        padding: 20px;
    }

    .btn-warning {
        background: #FFC107;
        border: none;
        border-radius: 20px;
        font-weight: bold;
        padding: 10px 20px;
    }

    .btn-warning:hover {
        background: #e0a800;
    }

    .link-register {
        color: white;
        text-decoration: underline;
        cursor: pointer;
    }

    h1,
    h4 {
        text-align: center;
    }
    </style>
</head>

<body>

    <!-- Navbar -->
    <?php require 'navbar.php'; ?>

    <!-- Login Bereich -->
    <div class="container d-flex flex-column align-items-center justify-content-center">
        <div class="card text-center" style="width: 100%; max-width: 400px;">
            <div>
                <img src="img/benutzerbild.png" height="100px" width="100px" alt="Benutzer Bild" class="rounded-circle">
            </div>
            <h1>ANMELDEN</h1>
            <h4>MELDE DICH AN</h4>

            <!-- Statusmeldungen -->
            <?php if ($status === 'wrong_password'): ?>
            <p class="text-danger">Falsches Passwort. Bitte versuche es erneut.</p>
            <?php elseif ($status === 'user_not_found'): ?>
            <p class="text-danger">Benutzer nicht gefunden. Bitte registriere dich.</p>
            <?php endif; ?>

            <form action="process_login.php" method="POST">
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="E-Mail" required>
                </div>
                <div class="mb-3 password-container">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Passwort"
                        required>
                    <span class="password-toggle" onclick="togglePassword('password')">👁️</span>
                </div>
                <button type="submit" class="btn btn-warning">Anmelden</button>
            </form>
        </div>

        <!-- Link zur Registrierung -->
        <div class="text-center mt-4">
            <p>Hast du noch keinen Account? <a href="register.php" class="link-register">Registriere dich jetzt!</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function togglePassword(inputId) {
        const passwordField = document.getElementById(inputId);
        const toggleIcon = passwordField.nextElementSibling;
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.textContent = '🙈'; // Symbol für "Verstecken"
        } else {
            passwordField.type = 'password';
            toggleIcon.textContent = '👁️'; // Symbol für "Anzeigen"
        }
    }
    </script>
</body>

</html>