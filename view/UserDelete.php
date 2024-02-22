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
                <h1>Eliminar Usuario</h1>
            </div>
            <br><br><br><br>
            <?php
                if(isset($errorMessage)){
                    echo '<div id="errorarea">';
                    echo    '<p>Ocurrió un problema :(</p>';
                    echo    '<p>'.$errorMessage.'</p></div>';
                }
            ?>
            <div id="inputarea">
                <?php
                    echo '<p id="messageconfirm">¿Eliminar al usuario '.$userToDelete->username.'?</p>';
                ?>
                <form class="frmbutton" action="../index.php?do=Delete" method="post">
                    <input type="hidden" name="id" value="<?php echo $userToDelete->id ?>">
                    <input class="abutton deletebutton" type="submit" name="btndelete" value="Eliminar">
                </form>
            </div>
        </div>
    </body>
</html>