<?php
    $servername = "200.17.76.17";
    $username = "root";
    $password = "rootpassword";
    $dbname = "tcc";
    $alunoID = 123; // Substitua pelo ID do aluno que você está buscando

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Preparar a consulta
        $query = "SELECT t.totalContabilizado, (SELECT SUM(totalContabilizado) FROM tb_arquivos_importados WHERE codigoArquivo = t.codigoArquivo AND IDAluno = :alunoID AND statusArquivo = 'Aprovado') AS soma FROM tb_arquivos_importados t JOIN tb_atividades a ON t.codigoArquivo = a.codigo WHERE IDAluno = :alunoID";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':alunoID', $alunoID);
        $stmt->execute();

        // Obter os resultados
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Retornar os resultados como JSON
        echo json_encode($result);
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
?>
