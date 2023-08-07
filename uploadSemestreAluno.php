<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION["id"])) {
    header("Location: index.php");
    exit();
}

// Função para gerar a senha usando as duas iniciais do nome e a matrícula
function gerarSenha($nome, $matricula) {
    $iniciais = substr($nome, 0, 2);
    return md5($iniciais . $matricula);
}

// Verifica se o arquivo CSV foi enviado
if (isset($_FILES["csvFile"]) && $_FILES["csvFile"]["error"] == UPLOAD_ERR_OK) {
    $semestre = $_POST["semestre"]; // Obtém o semestre informado no formulário
    $csvFile = $_FILES["csvFile"]["tmp_name"]; // Caminho temporário do arquivo CSV

    // Conexão com o banco de dados (substitua pelas suas informações de conexão)
    $servername = "200.17.76.17";
    $username = "root";
    $password = "rootpassword";
    $dbname = "tcc";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica se a conexão foi estabelecida corretamente
    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Prepara a instrução SQL para inserção na tabela "tb_alunos_semestre"
    $sqlAlunosSemestre = "INSERT INTO tb_alunos_semestre (matricula, nome, semestre) VALUES (?, ?, ?)";

    // Prepara a instrução SQL para inserção na tabela "tb_login"
    $sqlLogin = "INSERT INTO tb_login (matricula, senha, IDAluno) VALUES (?, ?, ?)";

    // Prepara as declarações SQL
    $stmtAlunosSemestre = $conn->prepare($sqlAlunosSemestre);
    $stmtLogin = $conn->prepare($sqlLogin);

    // Verifica se as declarações SQL foram preparadas corretamente
    if (!$stmtAlunosSemestre || !$stmtLogin) {
        die("Erro ao preparar as declarações SQL: " . $conn->error);
    }

    // Lê cada linha do arquivo CSV
    if (($handle = fopen($csvFile, "r")) !== FALSE) {
        $firstRow = true; // Variável para verificar se é a primeira linha

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Ignora a primeira linha (cabeçalho)
            if ($firstRow) {
                $firstRow = false;
                continue;
            }

            $matricula = $data[0]; // Obtém a matrícula da coluna 0
            $nome = $data[1]; // Obtém o nome da coluna 1

            // Gera a senha usando as duas iniciais do nome e a matrícula
            $senha = gerarSenha($nome, $matricula);

            // Associa as variáveis aos parâmetros da instrução SQL
            $stmtAlunosSemestre->bind_param("iss", $matricula, $nome, $semestre);

            // Executa a declaração SQL para inserção na tabela "tb_alunos_semestre"
            $stmtAlunosSemestre->execute();

            // Verifica se a execução foi bem-sucedida
            if ($stmtAlunosSemestre->errno) {
                die("Erro ao inserir dados na tabela tb_alunos_semestre: " . $stmtAlunosSemestre->error);
            }

            // Obtém o ID do aluno inserido
            $IDAluno = $stmtAlunosSemestre->insert_id;

            // Associa as variáveis aos parâmetros da instrução SQL
            $stmtLogin->bind_param("isi", $matricula, $senha, $IDAluno);

            // Executa a declaração SQL para inserção na tabela "tb_login"
            $stmtLogin->execute();

            // Verifica se a execução foi bem-sucedida
            if ($stmtLogin->errno) {
                die("Erro ao inserir dados na tabela tb_login: " . $stmtLogin->error);
            }
        }

        // Fecha o arquivo CSV
        fclose($handle);
    } else {
        // Erro ao abrir o arquivo CSV
        $message = "Erro ao abrir o arquivo CSV.";
        header("Location: cadastroSemestre.php?message=" . urlencode($message));
        exit();
    }

    // Fecha as declarações SQL
    $stmtAlunosSemestre->close();
    $stmtLogin->close();

    // Fecha a conexão com o banco de dados
    $conn->close();

    // Redireciona para a página de upload com uma mensagem de sucesso
    $message = "Arquivo CSV importado com sucesso.";
    header("Location: cadastroSemestre.php?message=" . urlencode($message));
    exit();
} else {
    // Nenhum arquivo CSV enviado
    $message = "Nenhum arquivo CSV enviado.";
    header("Location: cadastroSemestre.php?message=" . urlencode($message));
    exit();
}
?>
