<?php 
    session_start();
    require_once "database.php";
    if (isset($_SESSION['user_data'])) {
        $user = $_SESSION['user_data'];
    }
    if(isset($_POST['note_id'])) {
        $noteId = $_POST['note_id'];
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
    <?php 
        require_once "partials/header.php"; 
        if (isset($_SESSION['user_data']) && !empty($user)){
            require_once "app.php";
        } else {
            require_once "login.php";
        }
        require_once "partials/footer.php"; 
    ?>
</body>

</html>