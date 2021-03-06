<?php
session_start();

include("./conexao/conexaoPDO.php");
# login e senha preenchidos entra no if para validar

//CRIA A TABELA USUÁRIO

$createTable = "CREATE TABLE `usuarios` (
                    `iduser` int(11) NOT NULL AUTO_INCREMENT,
                    `usuario` varchar(50) NOT NULL,
                    `fone` varchar(15) NOT NULL,
                    `login` varchar(15) NOT NULL,
                    `senha` varchar(15) NOT NULL,
                    PRIMARY KEY (`iduser`),
                    UNIQUE KEY `login` (`login`)
                ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;";

try {
   $conexao->exec($createTable);
} catch (\Throwable $th) {
    
}

if ((isset($_POST['login'])) && (isset($_POST['senha']))) {
    
    # buscar na tabela usuarios o login e senha 
    # incluir proteção contra SQL Injection
    
    // $login = $_POST['login'];
    // $senha = $_POST['senha'];

    $query =   "SELECT *
                FROM usuarios
                WHERE login = ? AND senha = ?";

    $stmt = $conexao->prepare($query);

    $stmt->bindValue(1, $_POST['login']);
    $stmt->bindValue(2, $_POST['senha']);

    $stmt->execute();

    $usuario = $stmt->fetch();
    echo "<hr><pre>";
    print_r($usuario);
    echo "<pre>";
    if($usuario['login'] == $_POST['login'] && $usuario['senha'] == $_POST['senha']){
        # encontrou
        # grava sessão loginOK e redireciona o usuário para a página loja.html
        $_SESSION['loginOK'] = 'Logado';
        echo $_SESSION['loginOK'];
        header("Location: loja.php");
    }else{
        # não encontrou
        # grava sessão loginErro e redireciona o usuário para a página de login
        $_SESSION['loginErro'] = "Usuário ou senha Inválido";
        header("location: index.php");
    }
} else {
    # campo usuário e senha não preenchido 
    # grava sessão loginErro e redireciona o usuário para a página de login
    $_SESSION['loginErro'] = "Usuário ou senha inválido";
    header("location: index.php");
}