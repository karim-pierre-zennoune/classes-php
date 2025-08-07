<?php


class Userpdo
{
    private $id;
    public $login;
    public $email;
    public $firstname;
    public $lastname;
    private $pdo;
    private $online = false;


    public function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host=" . "localhost" . ";dbname=" . "classes", "root", "");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("ERROR: Could not connect. " . $e->getMessage());
        }
    }

    public function register($login, $password, $email, $firstname, $lastname)
    {

        if (empty(trim($login))) {
            throw new Exception('login cannot be empty');
        } else {
            $sql = "SELECT id FROM utilisateurs WHERE login = :login";
            if ($stmt = $this->pdo->prepare($sql)) {
                $stmt->bindParam(":login", $login, PDO::PARAM_STR);
                if ($stmt->execute()) {
                    if ($stmt->rowCount() == 1) {
                        throw new Exception('login already exists');
                    }
                } else {
                    throw new Exception('Something went wrong');
                }
                unset($stmt);
            }
        }


        $sql = "INSERT INTO utilisateurs (login, password, email, firstname, lastname) VALUES (:login, :password, :email, :firstname, :lastname)";

        if ($stmt = $this->pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":login", $login, PDO::PARAM_STR);
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":firstname", $firstname, PDO::PARAM_STR);
            $stmt->bindParam(":lastname", $lastname, PDO::PARAM_STR);
            if (!$stmt->execute()) {
                throw new Exception('Something went wrong');
            }
            unset($stmt);
        }

        $sql = "SELECT login, password, email, firstname, lastname FROM utilisateurs WHERE login = :login";


        if ($stmt = $this->pdo->prepare($sql)) {
            $stmt->bindParam(":login", $login, PDO::PARAM_STR);
            if ($stmt->execute()) {

                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        return [$row["login"], $row["password"], $row["email"], $row["firstname"], $row["lastname"]];
                    } else {
                        throw new Exception('Something went wrong');
                    }
                } else {
                    throw new Exception('Something went wrong');
                }
            } else {
                throw new Exception('Something went wrong');
            }
        }
    }


    public function connect($login, $password)
    {

        $sql = "SELECT id, login, password, email, firstname, lastname FROM utilisateurs WHERE login = :login";
        if ($stmt = $this->pdo->prepare($sql)) {
            $stmt->bindParam(":login", $login, PDO::PARAM_STR);
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        if (password_verify($password, $row["password"])) {
                            $this->id = $row["id"];
                            $this->online = true;
                            $this->login = $row["login"];
                            $this->email = $row["email"];
                            $this->firstname = $row["firstname"];
                            $this->lastname = $row["lastname"];
                        } else {
                            throw new Exception("Incorrect password");
                        }
                    }

                } else {
                    throw new Exception("User not found");
                }

            }
        }
        unset($stmt);
    }
    public function disconnect()
    {
        $this->online = false;
        $this->id = "";
        $this->login = "";
        $this->email = "";
        $this->firstname = "";
        $this->lastname = "";
    }

    public function delete()
    {
        $sql = "DELETE FROM utilisateurs WHERE id = :id";
        if ($stmt = $this->pdo->prepare($sql)) {
            $stmt->bindParam(":id", $this->id, PDO::PARAM_STR);
            if (!$stmt->execute()) {
                throw new Exception('Unable to execute DELETE instruction');
            }
            $this->online = false;
            unset($stmt);
        } else {
            throw new Exception('Unable to execute DELETE instruction');
        }
        $this->disconnect();
    }


    public function update($login, $password, $email, $firstname, $lastname)
    {
        if (empty(trim($login))) {
            throw new Exception('login cannot be empty');
        } else {
            $sql = "SELECT id FROM utilisateurs WHERE login = :login";
            if ($stmt = $this->pdo->prepare($sql)) {
                $stmt->bindParam(":login", $login, PDO::PARAM_STR);
                if ($stmt->execute()) {
                    if ($stmt->rowCount() == 1) {
                        throw new Exception('login already exists');
                    }
                } else {
                    throw new Exception('Something went wrong');
                }
                unset($stmt);
            }
        }

        $param_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE utilisateurs SET login = :login, password = :password, email = :email, firstname = :firstname, lastname = :lastname WHERE id = :id";

        if ($stmt = $this->pdo->prepare($sql)) {
            $stmt->bindParam(":id", $this->id, PDO::PARAM_STR);
            $stmt->bindParam(":login", $login, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":firstname", $firstname, PDO::PARAM_STR);
            $stmt->bindParam(":lastname", $lastname, PDO::PARAM_STR);

            if (!$stmt->execute()) {
                throw new Exception('Unable to execute DELETE instruction');
            }
            $this->login = $login;
            $this->email = $email;
            $this->firstname = $firstname;
            $this->lastname = $lastname;
            unset($stmt);
        } else {
            throw new Exception('Unable to execute DELETE instruction');
        }





    }









    public function isConnected()
    {
        return $this->online;
    }

    public function getAllInfos()
    {
        return [
            "id" => $this->id,
            "login" => $this->login,
            "email" => $this->email,
            "firstname" => $this->firstname,
            "lastname" => $this->lastname,
        ];
    }





    public function getLogin()
    {
        return $this->login;
    }


    public function getEmail()
    {
        return $this->email;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function getLastname()
    {
        return $this->lastname;
    }


}







?>