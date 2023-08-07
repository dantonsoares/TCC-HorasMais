<?php
session_start();

// Verifica se o ID do aluno foi passado como parâmetro na URL
if (!isset($_GET["id"])) {
    // ID do aluno não está presente, redireciona para a página anterior
    //header("Location: indexAdm.php");
    exit();
}

// Conexão com o banco de dados
$servername = "200.17.76.17";
$username = "root";
$password = "rootpassword";
$dbname = "tcc";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se houve erro na conexão
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Recupera o ID do aluno passado como parâmetro na URL
$alunoID = $_GET["id"];

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém a avaliação atualizada do aluno
    $avaliacao = $_POST["avaliacao"];
    $atividadeID = $_POST["atividadeID"];
    $digitado = $_POST["digitado"];

    // Atualiza a avaliação do aluno no banco de dados
    $query = "SELECT * FROM tb_arquivos_importados WHERE ID = '$atividadeID' AND IDAluno = '$alunoID'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $totalContabilizado =  $row["totalContabilizado"];
        }
    }

    if($avaliacao !='Aprovado' && $digitado != 0){
        $query = "UPDATE tb_arquivos_importados SET totalContabilizado = 0, statusArquivo = '$avaliacao' WHERE ID = '$atividadeID' AND IDAluno = '$alunoID'";
    }else{
        $query = "UPDATE tb_arquivos_importados SET statusArquivo = '$avaliacao', totalContabilizado = '$digitado' WHERE ID = '$atividadeID' AND IDAluno = '$alunoID'";
    }

    if ($conn->query($query) === TRUE) {
        // A atualização foi bem-sucedida
        header("Location: avaliarAluno.php?id=$alunoID");
        exit();
    } else {
        // Ocorreu um erro na execução da consulta SQL
        echo "Erro na atualização do banco de dados: " . $conn->error;
    }
}
?>
