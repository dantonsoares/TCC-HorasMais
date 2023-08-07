<?php
    session_start();

    // Verifica se o usuário está logado
    if (!isset($_SESSION["id"])) {
        // O usuário não está logado, redireciona para a página de login
        header("Location: login.php");
        exit();
    }

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

    // Obtém as informações do aluno com base no ID
    $query = "SELECT * FROM tb_alunos_semestre WHERE IDAluno = '$alunoID'";
    $result = $conn->query($query);

    // Verifica se o aluno existe
    if ($result->num_rows == 0) {
        // Aluno não encontrado, redireciona para a página anterior
        //header("Location: indexAdm.php");
        exit();
    }

    // Obtém os dados do aluno
    $aluno = $result->fetch_assoc();
    $alunoNome = $aluno["nome"];
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Avaliar Aluno</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/avaliarAluno.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-left">
            <span class="navbar-brand">Horas+</span>
        </div>
        <div class="navbar-right">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="indexAdm.php" class="nav-link">Página Inicial</a>
                </li>
                <li class="nav-item">
                    <a href="perfil.php" class="nav-link">Coordenador</a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">Sair</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="topo">
        <a href="listaAlunos.php?semestre=<?php echo urlencode($_SESSION['semestre']); ?>" class="button voltar">Voltar</a>
        <h1 class="titulo">Avaliar Aluno</h1>
    </div>

    <hr>

    <div class='tabela'>
        <h2><?php echo $aluno["matricula"]; ?> - <?php echo $alunoNome; ?></h2>
        <h2>Atividades cadastradas:</h2>
        <p>Suas alterações são salvas automaticamente.</p>
        <table>
            <tr>
                <th>Codigo</th>
                <th>Atividade</th>
                <th>Horas</th>
                <th>Contabilizado</th>
                <th>Avaliação</th>
            </tr>
            <?php
                // Obtém as atividades do aluno com base no ID
                $query = "SELECT t.*, a.maximoAtividade, a.maximoLimite, (SELECT SUM(totalContabilizado) FROM tb_arquivos_importados WHERE codigoArquivo = t.codigoArquivo AND IDAluno = $alunoID AND statusArquivo = 'Aprovado') AS soma FROM tb_arquivos_importados t JOIN tb_atividades a ON t.codigoArquivo = a.codigo WHERE IDAluno = '$alunoID' ORDER BY t.codigoArquivo ASC";
                $result = $conn->query($query);
                
                if ($result->num_rows > 0) {
                    $somas = array(); // Array para armazenar os códigos de arquivo e suas somas correspondentes
                    while ($row = $result->fetch_assoc()) {
                        // Armazena o código de arquivo e sua soma no array somas
                        $codigoArquivo = $row["codigoArquivo"];
                        $soma = $row["soma"];
                        $somas[$codigoArquivo] = $soma;
                
                        // Exibe a primeira tabela com as atividades
                        echo "<tr>";
                        echo "<td>" . $row["codigoArquivo"] . "</td>";
                        echo "<td><a href='downloadAtividadeAluno.php?id=" . $row["ID"] . "'>" . $row["nomeArquivo"] . "</a></td>";
                        echo "<td>" . $row["cargaHoraria"] . "</td>";
                        echo "<td>";
                        echo "<form method='POST' action='atualizarAvaliacao.php?id=" . $row["IDAluno"] . "'>";

                        if($row["totalContabilizado"] == 0){
                            echo "<input type='number' oninput='validarValor(this)' onchange='editarAvaliacao(this.form)' name='digitado' value='" . $row["cargaHoraria"] . "' step='1'>";
                        }else{
                            echo "<input type='number' oninput='validarValor(this)' onchange='editarAvaliacao(this.form)' name='digitado' value='" . $row["totalContabilizado"] . "' step='1'>";
                        }
                        echo "</td>";
                        echo "<td>";
                        echo "<label class='radio-label'><input type='radio' name='avaliacao' value='Aprovado' " . ($row["statusArquivo"] == "Aprovado" ? "checked" : "") . " onclick='atualizarAvaliacao(this.form)'> Aprovado</label>";
                        echo "<label class='radio-label'><input type='radio' name='avaliacao' value='Reprovado' " . ($row["statusArquivo"] == "Reprovado" ? "checked" : "") . " onclick='atualizarAvaliacao(this.form)'> Reprovado</label>";
                        echo "<label class='radio-label'><input type='radio' name='avaliacao' value='Pendente' " . ($row["statusArquivo"] == "Pendente" ? "checked" : "") . " onclick='atualizarAvaliacao(this.form)'> Pendente</label>";
                        echo "<input type='hidden' name='atividadeID' value='" . $row["ID"] . "'>";
                        echo "<input type='hidden' name='soma' value='" . $row["soma"] . "'>";
                        echo "<input type='hidden' name='maximoAtividade' value='" . $row["maximoAtividade"] . "'>";
                        echo "<input type='hidden' name='maximoLimite' value='" . $row["maximoLimite"] . "'>";
                        echo "<input type='hidden' name='totalContabilizado' value='" . $row["totalContabilizado"] . "'>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                
                    echo "</table>";
                    echo "<table>";
                    echo "<h2>Horas aprovadas por categoria:</h2>";
                    echo "<tr>";
                    echo "<th>Codigo</th>";
                    echo "<th>Horas Aprovadas</th>";
                    echo "</tr>";

                    $totalHorasAprovadas = 0; // Variável para armazenar a soma total das horas aprovadas

                    foreach ($somas as $codigoArquivo => $soma) {
                        if ($soma == '') {
                            $soma = 0;
                        }
                        echo "<tr>";
                        echo "<td>" . $codigoArquivo . "</td>";
                        echo "<td>" . $soma . "</td>";
                        echo "</tr>";

                        $totalHorasAprovadas += $soma; // Adiciona a soma atual à soma total
                    }

                    // Adiciona a última linha da tabela com o total de horas aprovadas
                    echo "<tr>";
                    echo "<td><strong>Total de horas aprovadas:</strong></td>";
                    echo "<td><strong>" . $totalHorasAprovadas . "</strong></td>";
                    echo "</tr>";

                    echo "</table>";

                    // Atualiza a coluna horasAprovadas na tabela tb_alunos_semestre
                    //$updateQuery = "UPDATE tb_alunos_semestre SET horasAprovadas = $totalHorasAprovadas WHERE IDAluno = $alunoID";
                    $updateQuery = "UPDATE tb_alunos_semestre SET horasAprovadas = $totalHorasAprovadas,conceito = (CASE WHEN horasAprovadas >= '210' THEN 'S' ELSE 'Q' END) WHERE IDAluno = $alunoID";
                    $conn->query($updateQuery);
                }           
            ?>
        </table>
    </div>

    <script>
        function atualizarAvaliacao(form) {
            var digitado = form.digitado.value.trim();
            digitado = digitado === '' ? 0 : parseInt(digitado);
            var maximoAtividade = parseInt(form.maximoAtividade.value);
            var maximoLimite = parseInt(form.maximoLimite.value);
            var soma = parseInt(form.soma.value);
            var totalContabilizado = parseInt(form.totalContabilizado.value);
            var soma2 = soma + digitado - totalContabilizado;

            if (soma2 > maximoLimite) {
                alert("O Valor máximo para essa atividade é de: " + (maximoLimite - soma + totalContabilizado));
                location.reload();
            } else if(digitado > maximoAtividade) {
                alert("O Valor máximo para essa atividade é de: " + maximoAtividade);
                location.reload();
            } else{
                form.submit();
            }
        }

        function validarValor(input) {
            if (parseFloat(input.value) < 0) {
                input.value = 0;
            }
        }

        function editarAvaliacao(form) {
            if (form.avaliacao.value === "Aprovado") {
                atualizarAvaliacao(form);
            }
        }
    </script>


</body>
</html>
