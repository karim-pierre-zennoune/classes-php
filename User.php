<?php

class User
{
    private $id;
    public $login;
    public $email;
    public $firstname;
    public $lastname;
    private $mysqli;

    public function __construct()
    {
        $this->mysqli = mysqli_connect("localhost", "root", "", "classes");
    }

    public function register($login, $password, $email, $firstname, $lastname)
    {

        if (empty(trim($login))) {
            return null;
        } else {
            $sql = "SELECT id FROM utilisateurs WHERE login = ?";
            if ($stmt = mysqli_prepare($this->mysqli, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $login);
                $login = trim($login);
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        return null;
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
                mysqli_stmt_close($stmt);
            }
        }

        $sql = "INSERT INTO utilisateurs (login, password, email, firstname, lastname) VALUES (?, ?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($this->mysqli, $sql)) {
            mysqli_stmt_bind_param($stmt, "sssss", $login, $param_password, $email, $firstname, $lastname);
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            if (!mysqli_stmt_execute($stmt)) {
                return null;
            }
            mysqli_stmt_close($stmt);
        }

        $sql = "SELECT login, password, email, firstname, lastname FROM utilisateurs WHERE login = ?";
        if ($stmt = mysqli_prepare($this->mysqli, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $login);
            $login = trim($login);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                mysqli_stmt_bind_result($stmt, $ret_login, $ret_password, $ret_email, $ret_firstname, $ret_lastname);
                if (mysqli_stmt_fetch($stmt)) {
                    return [$ret_login, $ret_password, $ret_email, $ret_firstname, $ret_lastname];
                } else {
                    return null;
                }
            }
        }

    }


    //     Connecte l’utilisateur, et
// donne aux attributs de
// la classe les valeurs
// correspondantes à
// celles de l’utilisateur
// connecté.
    public function connect($login, $password)
    {
        $sql = "SELECT id, login, password, email, firstname, lastname FROM utilisateurs WHERE login = ?";
        if ($stmt = mysqli_prepare($this->mysqli, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $login);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $ret_id, $ret_login, $ret_password, $ret_email, $ret_firstname, $ret_lastname);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $ret_password)) {
                            $this->id = $ret_id;
                            $this->login = $ret_login;
                            $this->email = $ret_email;
                            $this->firstname = $ret_firstname;
                            $this->lastname = $ret_lastname;
                        }
                    }
                }
            }
            mysqli_stmt_close($stmt);
        }
    }


    public function disconnect()
    {
        $this->id = null;
    }

    public function delete()
    {

    }
}


?>