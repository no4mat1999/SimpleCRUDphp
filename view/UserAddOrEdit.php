<!DOCTYPE html>
<html>
    <head>
        <title>Simple CRUD</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="reset.css">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <nav>
            <div>
                <h1>Simple CRUD</h1>
            </div>         
        </nav>
        <div id="content">
            <div id="titlearea">
                <a class="aback" href="../index.php">&larr; Volver</a><br>
                <?php
                    $formId;
                    $formUsername;
                    $formEmail;

                    // Dependiendo si el formulario es para editar o crear un usuario...

                    if(isset($userToEdit)){

                        // Si es para editar un usuario, carga los datos para editar

                        echo "<h1>Editar Usuario</h1>";
                        $formId = $userToEdit->id;
                        $formUsername = $userToEdit->username;
                        $formEmail = $userToEdit->email;

                    } else{

                        // Si es agregar, deja vacio los campos

                        echo "<h1>Agregar Usuario</h1>";
                        $formId = null;
                        $formUsername = "";
                        $formEmail = "";
                    }

                    // Cuando se está intentando agregar un usuario pero algun campo es erroneo, se carga nuevamente los datos en los campos del formulario
                    if(isset($tempUserToAdd)){
                        $formId = null;
                        $formUsername = $tempUserToAdd->username;
                        $formEmail = $tempUserToAdd->email;
                    }
                ?>
            </div>
            <br><br><br><br>
            <?php
                if(isset($errorMessage)){
                    echo '<div id="errorarea">';
                    echo    '<p>'.$errorMessage.'</p></div>';
                }
            ?>
            <div id="inputarea">
                <form action="../index.php?do=AddOrEdit" method="post">
                    <input type="hidden" name="id" value="<?php echo $formId ?>">

                    <label for="username">Nombre de usuario: </label></br>
                    <input type="text" name="username" value="<?php echo $formUsername ?>" maxlength="20" minlength="5" required></br></br>

                    <label for="email">Correo electrónico: </label></br>
                    <input type="text" name="email" value="<?php echo $formEmail ?>" maxlength="100" required></br></br>
                    
                    <label for="password">Contraseña: </label></br>
                    <input type="password" name="password" maxlength="50" minlength="5" required></br></br>

                    <label for="passwordconfirm">Confirma la contraseña: </label></br>
                    <input type="password" name="passwordconfirm" maxlength="50" minlength="5" required></br></br>
                    
                    <?php
                        if(isset($userToEdit)){
                            // Es edit
                            echo '<input class="abutton" type="submit" name="btnedit" value="Guardar cambios">';
                        } else {
                            // Es add
                            echo '<input class="abutton" type="submit" name="btnadd" value="Guardar">';
                        }
                    ?>
                    
                    <!--a class="abutton" href="../index.php">Cancelar</a-->
                </form>
            </div>
        </div>
    </body>
</html>