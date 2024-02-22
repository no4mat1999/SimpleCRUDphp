<?php 
declare(strict_types = 1);
class UserModel{

	private PDO $pdo;

	public function __construct(Configuration $connectionSetup){
		try{
			$host = $connectionSetup->host;
			$port = $connectionSetup->port;
			$username = $connectionSetup->username;
			$password = $connectionSetup->password;
			$database = $connectionSetup->database;

			$this->pdo = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(Exception $e){
			throw $e;
		}
	}
	
	public function get(): array{
		try{
			$result = array();
			$stmt = $this->pdo->prepare("SELECT id, username, email FROM usuarios");
			$stmt->execute();

			foreach($stmt->fetchAll(PDO::FETCH_OBJ) as $row){
				$user = new User();

				$user->id = intval($row->id);
				$user->username = $row->username;
				$user->email = $row->email;
				$user->password = "";
				
				array_push($result, $user);
			}

            return $result;
		}catch(Exception $e){
			throw $e;
		}
	}

    public function getById(int $id): User{ 
        try{
            $query = "SELECT id, username, email FROM usuarios WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            $user = new User();
            foreach($stmt->fetchAll(PDO::FETCH_OBJ) as $row){
                
                $user->id = intval($row->id);
                $user->username = $row->username;
                $user->email = $row->email;
                $user->password = "";
            }

            return $user;
        }catch(Exception $e){
            throw $e;
        }
    }

    public function getByUser(User $user): array{
        try{
            $result = array();

            $query = "SELECT id, username, email FROM usuarios WHERE ";
            ($user->email !== "") ? $query .= "email = :email AND ": $query .= "";
            ($user->username !== "") ? $query .= "username = :username AND ": $query .= "";
            $query = substr($query, 0, -4);
            $stmt = $this->pdo->prepare($query);

            if($user->email !== ""){
                $stmt->bindParam(":email", $user->email, PDO::PARAM_STR);
            }

            if($user->username !== ""){
                $stmt->bindParam(":username", $user->username, PDO::PARAM_STR);
            }

            $stmt->execute();

            foreach($stmt->fetchAll(PDO::FETCH_OBJ) as $row){
                $user = new User();
                $user->id = intval($row->id);
                $user->username = $row->username;
                $user->email = $row->email;
                $user->password = "";

                array_push($result, $user);
            }

            return $result;
        }catch(Exception $e){
            throw $e;
        }
    }

    public function save(User $user){
        try{
            $query = "INSERT INTO usuarios (username, email, password) VALUES (:username, :email, :password)";
            $stmt = $this->pdo->prepare($query);

            $stmt->bindParam(":username", $user->username, PDO::PARAM_STR);
            $stmt->bindParam(":email", $user->email, PDO::PARAM_STR);
            $newPassword = $this->generatePassword($user->password);
            $stmt->bindParam(":password", $newPassword, PDO::PARAM_STR);

            $stmt->execute();

        }catch(Exception $e){
            throw $e;
        }
    }

    public function update(User $user){
        try{
            $query = "UPDATE usuarios SET username = :username, email = :email, password = :password WHERE id = :id";
            $stmt = $this->pdo->prepare($query);

            $stmt->bindParam(":username", $user->username, PDO::PARAM_STR);
            $stmt->bindParam(":email", $user->email, PDO::PARAM_STR);
            $new_password = $this->generatePassword($user->password);
            $stmt->bindParam(":password", $new_password, PDO::PARAM_STR);
            $stmt->bindParam(":id", $user->id, PDO::PARAM_INT);

            $stmt->execute();

        }catch(Exception $e){
            throw $e;
        }
    }

    public function Delete(int $id){
        try{
            $query = "DELETE FROM usuarios WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);

            $stmt->execute();
        }catch(Exception $e){
            throw $e;
        }
    }

    private function generatePassword($password): string{
        $sal = bin2hex(random_bytes(16));
        $hashSha256 = hash('sha256', $sal.$password);
        return $sal.$hashSha256;
    }
}

