<?php

function check_password($password, $pass_db, $salt)
{
    $hashed = sha1($password . $salt);

    return ($hashed === $pass_db);    
}

function gerar_senha($senha)
{
    $bin = openssl_random_pseudo_bytes(16);
    $salt   = bin2hex($bin);
    $senha = sha1($senha . $salt);

    return array($salt, $senha);
}
?>