<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cadastro Semestre</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/cadastroSemestre.css">
</head>
<body>
    <?php
    // Verifica se o usuário está logado
    if (!isset($_SESSION["id"])) {
        header("Location: index.php");
        exit();
    }
    ?>
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
        <a href="indexAdm.php" class="button voltar">Voltar</a>
        <h1 class="titulo">Cadastro de Semestre e Alunos</h1>
    </div>
    <hr>
    <div class="content-container">
        <div class="form-container">
            <form method="post" action="uploadSemestreAluno.php" enctype="multipart/form-data">
                <label for="csvFile">Selecione o arquivo CSV:</label>
                <input class="file-input" type="file" id="csvFile" name="csvFile" accept=".csv" required>
                <label for="semestre">Semestre (Formato: xxxx-x):</label>
                <input type="text" id="semestre" name="semestre" pattern="\d{4}-\d{1}" title="Informe o semestre no formato xxxx-x" required>
                <input class="button" type="submit" value="Enviar">
            </form>
            <div class="csv-info-container">
                <h2>Formato do arquivo CSV</h2>
                <p>O arquivo CSV precisa ter duas colunas principais: 'matricula' e 'nome'. Certifique-se de usar esses nomes exatos, respeitando maiúsculas e minúsculas.</p>
                <p>Exemplo:</p>
                <code style="font-family: Consolas, monospace;">
                <p>matricula,nome</p>
                <p>1234,Maria Almeida</p>
                <p>4567,João Santos</p>
                </code>
            </div>
        </div>
    </div>

    <div class="message-container">
        <p id="message-text"></p>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const messageContainer = document.querySelector('.message-container');
            const messageText = document.getElementById('message-text');

            const urlParams = new URLSearchParams(window.location.search);
            const message = urlParams.get('message');

            if (message) {
                if (messageText) {
                    messageText.textContent = decodeURIComponent(message);
                }
                if (messageContainer) {
                    messageContainer.style.display = 'block';
                }

                if (message.startsWith('Erro')) {
                    messageContainer.style.backgroundColor = '#FFCCCC'; // Tom de vermelho para mensagens de erro
                } else {
                    messageContainer.style.backgroundColor = '#CCFFCC'; // Tom de verde para mensagens de envio bem-sucedido
                }
            }
        });
    </script>
</body>
</html>
