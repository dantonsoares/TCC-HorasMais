-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 21-Jun-2023 às 00:14
-- Versão do servidor: 10.4.24-MariaDB
-- versão do PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `tcc`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_alunos_semestre`
--

CREATE TABLE `tb_alunos_semestre` (
  `IDAluno` int(11) NOT NULL,
  `matricula` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `status` varchar(50) NOT NULL,
  `semestre` varchar(10) NOT NULL,
  `conceito` varchar(1) DEFAULT 'Q'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_alunos_semestre`
--

INSERT INTO `tb_alunos_semestre` (`IDAluno`, `matricula`, `nome`, `status`, `semestre`, `conceito`) VALUES
(1, 10000, 'Coordenador', '', '', 'Q'),
(122, 6313, 'Guilherme Moreira', '', '2022-02', 'S'),
(244, 1234, 'João Silva', 'Ativo', '2023-01', 'Q'),
(245, 5678, 'Maria Santos', 'Ativo', '2023-01', 'Q'),
(246, 9012, 'Pedro Oliveira', 'Ativo', '2023-01', 'Q'),
(247, 3456, 'Ana Pereira', 'Ativo', '2023-01', 'Q'),
(248, 7890, 'Lucas Almeida', 'Ativo', '2023-01', 'Q'),
(249, 2345, 'Camila Souza', 'Ativo', '2023-01', 'Q'),
(250, 6789, 'Fernanda Ferreira', 'Ativo', '2023-01', 'Q'),
(251, 123, 'Gustavo Rodrigues', 'Ativo', '2023-01', 'Q'),
(252, 4567, 'Mariana Costa', 'Ativo', '2023-01', 'Q'),
(253, 8901, 'Rafael Silva', 'Ativo', '2023-01', 'Q'),
(254, 2345, 'João Santos', 'Ativo', '2023-01', 'Q'),
(255, 6789, 'Maria Oliveira', 'Ativo', '2023-01', 'Q'),
(256, 123, 'Pedro Pereira', 'Ativo', '2023-01', 'Q');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_arquivos_importados`
--

CREATE TABLE `tb_arquivos_importados` (
  `ID` int(11) NOT NULL,
  `nomeArquivo` varchar(100) NOT NULL,
  `cargaHoraria` int(11) NOT NULL,
  `totalContabilizado` int(11) NOT NULL,
  `statusArquivo` varchar(50) NOT NULL,
  `codigoArquivo` varchar(100) NOT NULL,
  `arquivo` longblob NOT NULL,
  `IDAluno` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_atividades`
--

CREATE TABLE `tb_atividades` (
  `codigo` varchar(5) NOT NULL,
  `maximoAtividade` int(11) NOT NULL,
  `maximoLimite` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_atividades`
--

INSERT INTO `tb_atividades` (`codigo`, `maximoAtividade`, `maximoLimite`) VALUES
('AE001', 90, 90),
('AE002', 90, 90),
('AE003', 15, 90),
('AE004', 20, 60),
('AE005', 15, 45),
('AE006', 30, 90),
('AE007', 15, 60),
('AE008', 30, 90),
('AE009', 30, 90),
('AE010', 30, 90),
('AE011', 30, 60),
('AE012', 90, 90),
('AE013', 90, 90),
('AE014', 30, 30),
('AE015', 90, 90),
('AE016', 5, 10),
('AE017', 90, 90);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_login`
--

CREATE TABLE `tb_login` (
  `ID` int(11) NOT NULL,
  `matricula` int(11) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `IDAluno` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_login`
--

INSERT INTO `tb_login` (`ID`, `matricula`, `senha`, `IDAluno`) VALUES
(2, 6313, '698dc19d489c4e4db73e28a713eab07b', 122),
(112, 10000, '698dc19d489c4e4db73e28a713eab07b', 1),
(126, 1234, '13afa52a3fd2acd596e9863eb62742d6', 244),
(127, 5678, '6d72282f295042fa6d1ecf88e5111575', 245),
(128, 9012, '9d196aa5f9626d209739ecf910e4a494', 246),
(129, 3456, '750263d01d6d98b08f87247a597ef68d', 247),
(130, 7890, '54571d273edcbcebdf09caf2d9c46d87', 248),
(131, 2345, 'e823b8394ed737e0926949edbee8a567', 249),
(132, 6789, '5fadc402359c4a39b991974e75c88af6', 250),
(133, 123, '8516bcc44a3d4f0d2be076359f393321', 251),
(134, 4567, 'f80297dd956c8bf9fbcda159f9cd4532', 252),
(135, 8901, '0d0dc8d9c850aba36383722fa71fc78b', 253),
(136, 2345, '3f790931769aec6ee466cc76fd38a1a2', 254),
(137, 6789, '7f8d8237094d4f5fe8fee2e099986eeb', 255),
(138, 123, 'e090b2edf4150de74a9dc0d2424a8a92', 256);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `tb_alunos_semestre`
--
ALTER TABLE `tb_alunos_semestre`
  ADD PRIMARY KEY (`IDAluno`);

--
-- Índices para tabela `tb_arquivos_importados`
--
ALTER TABLE `tb_arquivos_importados`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `IDAluno` (`IDAluno`);

--
-- Índices para tabela `tb_atividades`
--
ALTER TABLE `tb_atividades`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices para tabela `tb_login`
--
ALTER TABLE `tb_login`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `IDAluno` (`IDAluno`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tb_alunos_semestre`
--
ALTER TABLE `tb_alunos_semestre`
  MODIFY `IDAluno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=257;

--
-- AUTO_INCREMENT de tabela `tb_arquivos_importados`
--
ALTER TABLE `tb_arquivos_importados`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de tabela `tb_login`
--
ALTER TABLE `tb_login`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `tb_arquivos_importados`
--
ALTER TABLE `tb_arquivos_importados`
  ADD CONSTRAINT `tb_arquivos_importados_ibfk_1` FOREIGN KEY (`IDAluno`) REFERENCES `tb_alunos_semestre` (`IDAluno`);

--
-- Limitadores para a tabela `tb_login`
--
ALTER TABLE `tb_login`
  ADD CONSTRAINT `tb_login_ibfk_1` FOREIGN KEY (`IDAluno`) REFERENCES `tb_alunos_semestre` (`IDAluno`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
