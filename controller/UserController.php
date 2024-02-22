<?php
require 'model/UserModel.php';
require 'model/User.php';
require_once 'Config.php';

class UserController{
    private $model;

    public function __construct(){
        $config = new Configuration();
        $this->model = new UserModel($config);
    }

    public function handler(){
        try{
            $action = isset($_GET['do']) ? $_GET['do'] : NULL;
            switch($action){
                case 'Delete':
                    if(isset($_POST['btndelete']) or isset($_POST['btnloaddelete'])){
                        $this->delete();
                    }else{
                        header('Location:index.php');
                        exit();
                    }  
                    break;
                case 'AddOrEdit':
                    if(isset($_POST['btnadd']) or isset($_POST['btnloadadd'])){
                        $this->insert();
                    } else if(isset($_POST['btnedit']) or isset($_POST['btnloadedit'])){
                        $this->edit();
                    }else{
                        header('Location:index.php');
                        exit();
                    }
                    break;
                default:
                    $this->get();
            }
        }catch(Exception $e){
            header("Location: error.php");
            exit();
        }
    }

    public function delete(){
        try{
            if(isset($_POST['btnloaddelete'])){
                // Solo carga la vista para confirmar la eliminacion del usuario
                $user_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
                if($user_id){
                    $userToDelete = $this->model->getById(intval($user_id));
                    include 'view/UserDelete.php';
                } else{
                    // Si el id no existe, carga index.php
                    header('Location:index.php');
                    exit();
                }
            }else if(isset($_POST['btndelete'])){
                // Elimina a un usuario
                $user_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
                if($user_id){
                    $this->model->Delete(intval($user_id));
                    header('Location:index.php');
                    exit();
                }
            } else {
                header('Location:index.php');
                exit();
            }
        }catch(Exception $e){
            header("Location:error.php");
            exit();
        }
    }

    public function get(){
        try{
            // Obtiene la lista de usuarios registrados y carga la vista
            $result = $this->model->get();
            include 'view/UserList.php';
        }catch(Exception $e){
            header("Location:error.php");
            exit();
        }
    } 

    public function insert(){
        try{
            if(isset($_POST['btnloadadd'])){
                // Solo carga la vista para esperar los datos de entrada por el formulario
                include 'view/UserAddOrEdit.php';
            } else if (isset($_POST['btnadd'])){
                // Proceso para agregar usuario
                $user = new User();

                $user->username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
                $user->email = trim(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));
                $user->password = trim(filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING));

                $passwordconfirm = trim(filter_input(INPUT_POST, "passwordconfirm", FILTER_SANITIZE_STRING));

                // Valida los datos del formulario
                $errorMessage = $this->validateUser($user, false);
                if($passwordconfirm!==$user->password){
                    $errorMessage = 'Las contraseñas no coinciden';
                }

                if($errorMessage === null){
                    // Guarda la información
                    $this->model->save($user);
                    header('Location:index.php');
                    exit();
                } else {
                    // Muestra error y carga nuevamente los datos erroneos para que sean editados
                    $tempUserToAdd = $user;
                    include 'view/UserAddOrEdit.php';
                } 
            } else{
                header('Location:index.php');
                exit();
            }
        }catch(Exception $e){
            header("Location:error.php");
            exit();
        }
    }

    public function edit(){
        try{
            if(isset($_POST['btnloadedit'])){
                $userId = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
                if($userId){
                    // Muestra la informacion del usuario que se desea editar
                    $userToEdit = $this->model->getById(intval($userId));
                    include 'view/UserAddOrEdit.php';
                } else{
                    // Si el id no existe, carga index.php
                    header('Location:index.php');
                    exit();
                }
            } else if (isset($_POST['btnedit'])){
                // Proceso para guardar los cambios
                $user = new User();

                $user->id = intval(filter_input(INPUT_POST,'id', FILTER_SANITIZE_NUMBER_INT));
                $user->username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
                $user->email = trim(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));
                $user->password = trim(filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING));

                $passwordconfirm = trim(filter_input(INPUT_POST, "passwordconfirm", FILTER_SANITIZE_STRING));

                // Se valida los campos
                $errorMessage = $this->validateUser($user, true);
                if($passwordconfirm!==$user->password){
                    $errorMessage = 'Las contraseñas no coinciden';
                }

                if($errorMessage === null){
                    // Guarda cambios
                    $this->model->update($user);
                    header('Location:index.php');
                    exit();
                } else {
                    // Dibuja la vista para mostrar el mensaje de error
                    $userToEdit = $user;
                    include 'view/UserAddOrEdit.php';
                }
            } else {
                header('Location:index.php');
                exit();
            }
        }catch(Exception $e){
            header("Location:error.php");
            exit();
        }
    }

    private function validateUser(User $user, bool $isEdit):?string{
        if(trim($user->username) == ''){
            return 'El campo "Nombre de usuario" no puede estar vacío';
        }

        if(strlen(trim($user->username)) < 5){
            return 'Use más de 5 carácteres para el campo "Nombre de suario"';
        }

        if(strlen(trim($user->username)) > 20){
            return 'El campo "Nombre de suario" solo se acepta un máximo de 20 carácteres';
        }

        if(trim($user->email) == ''){
            return 'El campo "Correo electrónico" no puede estar vacío';
        }

        if(strlen(trim($user->email)) > 100){
            return 'El campo "Correo electrónico" solo se acepta un máximo de 100 carácteres';
        }

        if(trim($user->password) == ''){
            return 'El campo "Contraseña" no puede estar vacío';
        }

        if(strlen(trim($user->password)) < 5){
            return 'Use más de 5 carácteres para el campo "Contraseña"';
        }

        if(strlen(trim($user->password)) > 50){
            return 'El campo "Contraseña" solo se acepta un máximo de 50 carácteres';
        }

        if(!preg_match('/^[a-zA-Z0-9]+$/', $user->username)){
            return 'Solo se permiten valores alfanumericos sin espacios en el campo Nombre de usuario';
        }

        if(!filter_var($user->email, FILTER_VALIDATE_EMAIL)){
            return 'Dirección de correo invalida';
        }
        // Valida si el usuario ya esta registrado
        $userSearch = new User();
        $userSearch->email = "";
        $userSearch->username = $user->username;

        $userResult = $this->model->getByUser($userSearch);
        if($isEdit){
            foreach($userResult as $r){
                if($r->id != $user->id) return 'El nombre de usuario no está disponible';
            }
        }else if(count($userResult) > 0){
            return 'El nombre de usuario ya está registrado';
        }
        // Valida si se usa el correo
        $userSearch = new User();
        $userSearch->email = $user->email;
        $userSearch->username = "";

        $userResult = $this->model->getByUser($userSearch);
        if($isEdit){
            foreach($userResult as $r){
                if($r->id != $user->id) return 'La dirección de correo electrónico ya está ocupada';
            }
        }else if(count($userResult) > 0){
            return 'La dirección de correo electrónico está en uso';
        }

        return null;
    }

}