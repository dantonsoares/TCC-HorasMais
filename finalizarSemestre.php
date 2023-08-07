<?php
    // Conecta ao banco de dados
    $servername = "200.17.76.17";
    $username = "root";
    $password = "rootpassword";
    $dbname = "tcc";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Verifica se o semestre foi passado como parâmetro na URL
    if (isset($_GET["semestre"])) {
        $semestre = $_GET["semestre"];

        // Exclui os alunos desse semestre da tabela "tb_login"
        $sql = "DELETE FROM tb_login WHERE IDAluno IN (SELECT IDAluno FROM tb_alunos_semestre WHERE semestre = ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $semestre);
        $stmt->execute();

        // Atualiza o status dos alunos desse semestre para "Finalizado" na tabela "tb_alunos_semestre"
        $sql = "UPDATE tb_alunos_semestre SET status = 'Finalizado' WHERE semestre = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $semestre);
        $stmt->execute();

        // Fecha a conexão com o banco de dados
        $stmt->close();
        header("Location: listaAlunos.php?semestre=" . urlencode($semestre));
    } else {
        echo "Semestre inválido.";
    }
?>
