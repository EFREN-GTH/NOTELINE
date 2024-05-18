<?php
    $message="";
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
        if (empty($_POST['email']) || empty($_POST['password'])){
            $message="Debe completar todos los campos para poder iniciar sesión.";
        }else{
            $results = getUserData($conn);
            if (is_array($results) && count($results) > 0 && password_verify($_POST['password'], $results['pass'])){
                $_SESSION['user_data'] = $results;
                $user = $_SESSION['user_data'];
            }else{
                $message="Los credenciales indroducidos no son correctos.";
            }
        }
    }
?>
<?php if (isset($_SESSION['user_data']) && !empty($user)): require "app.php"; else: ?>
    <div class="app-bg">
        <div class="card">
            <span class="title-wrapper">INCIAR SESIÓN:</span>
            <div class="login-form">
                <p>Inicie Sesión o <a href="register.php">Regístrese</a> si aún no tiene una cuenta.</p>
                <form action="index.php" method="post">
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
                    <p class="warning"><?php echo $message?></p>
                    <input class="login-btn" type="submit" value="INICIAR SESIÓN" name="login">
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>