<?php
require "database.php";

$message = "";
$message2 = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $name = trim($_POST['name']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

    // Validaciones para el formulario de registro:
    if (strlen($email) <= 255 && strlen($name) <= 50 && strlen($password) <= 100) {
        if (!empty($name) && !empty($email) && !empty($password) && !empty($confirmPassword)) {
            if (arePasswordsMatching($password, $confirmPassword)) {
                if (!isEmailAlreadyRegistered($conn, $email)) {
                    if (createUser($conn, $name, $email, $password)) {
                        $message2 = 'Usuario registrado exitosamente. <a href="index.php">Iniciar Sesión</a>';
                    } else {
                        $message = "Lo siento, debe haber habido un problema al crear tu cuenta.";
                    }
                } else {
                    $message = "El correo electrónico introducido ya se ha registrado anteriormente.";
                }
            } else {
                $message = "Las contraseñas no coinciden. Por favor inténtelo de nuevo.";
            }
        } else {
            $message = "Debe completar todos los campos para poder registrarse.";
        }
    } else {
        $message = "Los campos superan la longitud máxima permitida.";
    }
}

function isEmailAlreadyRegistered($conn, $email)
{
    $check_sql = "SELECT COUNT(*) FROM users WHERE email = :email";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bindParam(':email', $email);
    $check_stmt->execute();
    return $check_stmt->fetchColumn();
}

function createUser($conn, $name, $email, $password)
{
    $sql = "INSERT INTO users (email, name, pass) VALUES (:email, :name, :password)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':name', $name);
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    $stmt->bindParam(':password', $passwordHash);

    return $stmt->execute();
}

function arePasswordsMatching($password, $confirmPassword)
{
    return $password === $confirmPassword;
}
?>




<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/img/logo.png" type="image/png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&family=Readex+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Noteline: Notas Online</title>
</head>
<body>
    <?php require "partials/header.php"; ?>

    <div class="app-bg">
        <div class="card">
        <span class="title-wrapper">REGISTRARSE:</span>
            <div class="login-form">
                <p>Regístrese o <a href="index.php">Inicie Sesión</a> si ya tiene una cuenta.</p>
                <form action="register.php" method="post">
                    <div class="form-field">
                        <input class="login-field" type="text" name="name" placeholder="">
                        <span>Nombre de Usuario</span>
                        <i></i>
                    </div>
                    <div class="form-field">
                        <input class="login-field" type="email" name="email" placeholder="">
                        <span>Correo</span>
                        <i></i>
                    </div>
                    <div class="form-field">
                        <input class="login-field" type="password" name="password" placeholder="">
                        <span>Contraseña</span>
                        <i></i>
                    </div>
                    <div class="form-field">
                        <input class="login-field" type="password" name="confirm_password" placeholder="">
                        <span>Repetir Contraseña</span>
                        <i></i>
                    </div>

                    <p class="warning"><?php echo $message?></p>
                    <p class="warning2"><?php echo $message2?></p>
                    
                    <input class="login-btn" type="submit" value="REGISTRARSE">
                </form>
            </div>
        </div>
    </div>
    
    <?php require "partials/footer.php"; ?>
</body>

</html>