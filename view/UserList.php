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
        <br>
        <div id="content">
            <div id="titlearea">
                <h1>Usuarios</h1>

                <form action="../index.php?do=AddOrEdit" method="post">
                    <input class="abutton" type="submit" name="btnloadadd" value="Agregar Usuario">
                </form>
            </div>
            <br><br><br><br>
            <?php
                if(isset($errorMessage)){
                    echo '<div id="errorarea">';
                    echo    '<p>Ocurrió un problema :(</p>';
                    echo    '<p>'.$errorMessage.'</p></div>';
                }
            ?>
            <div id="tablearea">
                <table>
                    <tr>
                        <th>Id</th>
                        <th>Nombre de usuario</th>
                        <th>Correo electrónico</th>
                        <th>Acciones</th>
                    </tr>

                    <?php foreach ($result as $row): ?>
                        <tr>
                            <td><?= $row->id ?></td>
                            <td><?= $row->username ?></td>
                            <td><?= $row->email ?></td>
                            <td>
                                <form action="../index.php?do=AddOrEdit" method="post">
                                    <input type="hidden" name="id" value="<?php echo $row->id ?>">
                                    <input class="abutton" type="submit" name="btnloadedit" value="Editar">
                                </form>

                                <form action="../index.php?do=Delete" method="post">
                                    <input type="hidden" name="id" value="<?php echo $row->id ?>">
                                    <input class="abutton" type="submit" name="btnloaddelete" value="Eliminar">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>

                </table>
            </div>
        </div>
    </body>
</html>