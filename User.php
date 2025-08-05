<?php

class User
{
    private $id;
    public $login;
    public $email;
    public $firstname;
    public $lastname;
    private $mysqli;
    private $online = false;

    public function __construct()
    {
        $this->mysqli = mysqli_connect("localhost", "root", "", "classes");
    }

    public function register($login, $password, $email, $firstname, $lastname)
    {

        if (empty(trim($login))) {
            throw new Exception('login cannot be empty');
        } else {
            $sql = "SELECT id FROM utilisateurs WHERE login = ?";
            if ($stmt = mysqli_prepare($this->mysqli, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $login);
                $login = trim($login);
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        throw new Exception('login already exists');
                        // return null;
                    }
                } else {
                    throw new Exception('Something went wrong');
                }
                mysqli_stmt_close($stmt);
            }
        }

        $sql = "INSERT INTO utilisateurs (login, password, email, firstname, lastname) VALUES (?, ?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($this->mysqli, $sql)) {
            mysqli_stmt_bind_param($stmt, "sssss", $login, $param_password, $email, $firstname, $lastname);
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            if (!mysqli_stmt_execute($stmt)) {
                // return null;
                throw new Exception('Something went wrong');
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
            } else {
                throw new Exception('Something went wrong');
            }
        }

    }


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
                            $this->online = true;
                            $this->login = $ret_login;
                            $this->email = $ret_email;
                            $this->firstname = $ret_firstname;
                            $this->lastname = $ret_lastname;
                        } else {
                            throw new Exception('Incorrect password');
                        }
                    }
                } else {
                    throw new Exception('User not found');
                }
            } else {
                throw new Exception('Unable to connect');
            }
            mysqli_stmt_close($stmt);
        } else {
            throw new Exception('Unable to connect');
        }
    }


    public function disconnect()
    {
        $this->online = false;
    }

    public function delete()
    {
        $sql = "DELETE FROM utilisateurs WHERE id = ?";
        if ($stmt = mysqli_prepare($this->mysqli, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $this->id);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception('Unable to execute DELETE instruction');
            }
            $this->online = false;
            mysqli_stmt_close($stmt);
        } else {
            throw new Exception('Unable to execute DELETE instruction');
        }
    }

    public function update($login, $password, $email, $firstname, $lastname)
    {

        $sql = "SELECT id FROM utilisateurs WHERE login = ?";
        if ($stmt = mysqli_prepare($this->mysqli, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $login);
            $login = trim($login);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    throw new Exception('login already exists');
                    // return null;
                }
            } else {
                throw new Exception('Something went wrong');
            }
            mysqli_stmt_close($stmt);
        }





        $param_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE utilisateurs SET login = ?, password = ?, email = ?, firstname = ?, lastname = ? WHERE id = ?";
        if ($stmt = mysqli_prepare($this->mysqli, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssss", $login, $param_password, $email, $firstname, $lastname, $this->id);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception('Could not update user');
            }
            mysqli_stmt_close($stmt);
            $this->login = $login;
            $this->email = $email;
            $this->firstname = $firstname;
            $this->lastname = $lastname;
        } else {
            throw new Exception('Could not update user');
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