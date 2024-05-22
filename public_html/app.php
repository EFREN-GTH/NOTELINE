<?php
    $notes = getUserNotes($user, $conn);
    $message="";
    $okMessage="";
    

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['app'])) {
        if (empty($_POST['content'])) {
            $message = "Complete todos los campos.";
        } else {
            if (empty($_POST['title'])) $_POST['title'] = shortenString($_POST['content']);
            if (strlen($_POST['content']) > 255 || strlen($_POST['title']) > 50) {
                $message = "Longitud de caracteres no permitida.";
            } else {
                $sql = "INSERT INTO notes (email, title, content) VALUES (:user_id, :title, :content)";
                try {
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':user_id', $user['email']);
                    $stmt->bindParam(':title', $_POST['title']);
                    $stmt->bindParam(':content', $_POST['content']);
                    $stmt->execute();
                    $okMessage = "Registro insertado correctamente.";
                    $notes = getUserNotes($user, $conn);
                } catch (Exception $e) {
                    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                }
            }
        }
    }
    function shortenString($str) {
        if (strlen($str) > 20) {
            return substr($str, 0, 20) . '...';
        } else {
            return $str;
        }
    }
    ?>
<div class="container">
    <div class="greeting">
        <span>Hola, <b><?= $user['name']?></b></span>
        <a href="logout.php"> Cerrar Sesión</a>
    </div>
    <form action="index.php" method="post">
        <div class="addNote">
            <label>Escribe una nota:</label>
            <div class="noteFields">
                <input id="title" type="text" name="title" placeholder="Título (max. 50)" maxlength="50">
                <textarea id="content" name="content" placeholder="Empieza a escribir aquí una nueva nota... (max. 255)" required></textarea>
            </div>
            <div class="box-row">
                <span style="color:red; font-size:.9rem"><?= $message ?></span>
                <span style="color:green; font-size:.9rem"><?= $okMessage ?></span>
                <input type="hidden" name="form_type" value="add_note">
                <input class="save-btn" type="submit" value="Guardar" name="app">
            </div>
        </div>
    </form>
    <?php if ($notes): foreach ($notes as $note): ?>
        <div class="notas">
            <div class="nota">
                <h3><?= $note['title'] ?></h3>
                <p><?= $note['content'] ?></p>
                <label id="date" class="data-label" data-utc="<?= $note['created_date'] ?>"></label>
                <input type="button" value="Eliminar nota" onclick="deleteNote(<?= $note['id'] ?>)">
            </div>
        </div>
    <?php endforeach; else: ?>
        <p>No hay notas para mostrar.</p>
    <?php endif; ?>
</div>
<script>
    window.onload = function() {
        const dateLabels = document.querySelectorAll('.data-label');
        dateLabels.forEach(function(label) {
            const utcDateString = label.getAttribute('data-utc');
            label.textContent = utcStringToLocale(utcDateString);
        });
    };
    function utcStringToLocale(utcDateString) {
        return new Date(utcDateString.replace(' ', 'T') + 'Z').toLocaleString();
    }
    
    async function deleteNote(noteId) {
        try {
            const response = await fetch(`database.php?note_id=${noteId}`, {
            method: 'DELETE'
            });

            if (response.ok) {
                window.location.href = 'index.php';
            } else {
                console.error('Error al eliminar la nota:', response.statusText);
            }
        } catch (error) {
            console.error('Error al eliminar la nota:', error.message);
        }
    }
</script>
