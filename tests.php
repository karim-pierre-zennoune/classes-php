<?php



echo "Step 2: registering a new user<br/>";


try {
    $ret = $newuser->register(
        $test_login,
        $test_password,
        "kazenun@gmail.com",
        "Karim",
        "Zennoune"
    );
    var_dump($ret);
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "<br/>";
}

echo "Step 3: try to register same user again<br/>";
try {
    $ret = $newuser->register(
        $test_login,
        $test_password,
        "kazenun@gmail.com",
        "Karim",
        "Zennoune"
    );
    var_dump($ret);
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "<br/>";
}

echo "Step 4: attempt to connect with non existing user<br/>";
try {
    $newuser->connect(
        $wrong_login,
        $wrong_password,
    );
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "<br/>";
}

echo "Step 5: attempt to connect with wrong password<br/>";
try {
    $newuser->connect(
        $test_login,
        $wrong_password,
    );
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "<br/>";
}

echo "Step 6: connect user<br/>";
try {
    $newuser->connect(
        $test_login,
        $test_password,
    );
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "<br/>";
}

echo "Step 7: disconnect user<br/>";
$newuser->disconnect();

echo "Step 8: delete user<br/>";
try {
    $newuser->delete();
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "<br/>";
}

echo "Step 9: try to reconnect deleted user<br/>";
try {
    $newuser->connect(
        $test_login,
        $test_password,
    );
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "<br/>";
}

echo "Step 10: register and connect user again<br/>";

try {
    $ret = $newuser->register(
        $test_login,
        $test_password,
        "kazenun@gmail.com",
        "Karim",
        "Zennoune"
    );
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "<br/>";
}

try {
    $newuser->connect(
        $test_login,
        $test_password,
    );
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "<br/>";
}

echo "Step 11: update user<br/>";

try {
    $newuser->update(
        $new_login,
        $new_password,
        "Michel@gmail.com",
        "Hakim",
        "Chirac"
    );
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "<br/>";
}

echo "Step 10: getters<br/>";
echo "isConnected(): ";
echo $newuser->isConnected() ? "true <br/>" : "false <br/>";
echo "getAllInfos(): <br/>";
var_dump($newuser->getAllInfos());
echo "getLogin(): ";
echo $newuser->getLogin() . "<br/>";
echo "getEmail(): ";
echo $newuser->getEmail() . "<br/>";
echo "getFirstname(): ";
echo $newuser->getFirstname() . "<br/>";
echo "getLastname(): ";
echo $newuser->getLastname() . "<br/>";



?>