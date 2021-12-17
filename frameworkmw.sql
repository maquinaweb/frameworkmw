-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 28-Out-2021 às 13:11
-- Versão do servidor: 10.4.14-MariaDB
-- versão do PHP: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `frameworkmw`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `app_confirmchange`
--

CREATE TABLE `app_confirmchange` (
  `id` int(10) UNSIGNED NOT NULL,
  `class` text DEFAULT NULL,
  `hash` varchar(45) DEFAULT NULL,
  `valid` datetime DEFAULT NULL,
  `data_criacao` datetime DEFAULT NULL,
  `data_exclusao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `app_grupomenu`
--

CREATE TABLE `app_grupomenu` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(50) NOT NULL,
  `data_criacao` datetime DEFAULT NULL,
  `data_exclusao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `app_grupomenu`
--

INSERT INTO `app_grupomenu` (`id`, `nome`, `data_criacao`, `data_exclusao`) VALUES
(1, 'lateral_esquerdo', '2018-03-12 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `app_grupomodulo`
--

CREATE TABLE `app_grupomodulo` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(95) DEFAULT NULL,
  `data_criacao` datetime DEFAULT NULL,
  `data_exclusao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `app_grupomodulo`
--

INSERT INTO `app_grupomodulo` (`id`, `nome`, `data_criacao`, `data_exclusao`) VALUES
(1, 'Sistema', '2018-06-15 11:59:46', NULL),
(2, 'Administrativo', '2018-06-15 11:59:57', NULL),
(3, 'Cliente', '2018-07-07 21:15:58', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `app_grupousuario`
--

CREATE TABLE `app_grupousuario` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(45) DEFAULT NULL,
  `descricao` varchar(545) DEFAULT NULL,
  `data_criacao` datetime DEFAULT NULL,
  `data_exclusao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `app_grupousuario`
--

INSERT INTO `app_grupousuario` (`id`, `nome`, `descricao`, `data_criacao`, `data_exclusao`) VALUES
(1, 'Administrativo', NULL, '2018-09-13 09:31:11', NULL),
(2, 'Sistema', NULL, '2018-09-13 09:31:22', NULL),
(3, 'Cliente', NULL, '2018-09-13 09:31:37', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `app_log`
--

CREATE TABLE `app_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `data` datetime DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `tipo_usuario` varchar(50) DEFAULT NULL,
  `conteudo_id` int(11) DEFAULT NULL,
  `conteudo` varchar(100) DEFAULT NULL,
  `acao` varchar(50) DEFAULT NULL,
  `extra` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Estrutura da tabela `app_menu`
--

CREATE TABLE `app_menu` (
  `id` int(10) UNSIGNED NOT NULL,
  `menu_id` int(10) UNSIGNED DEFAULT 0,
  `nome` varchar(45) DEFAULT NULL,
  `url` varchar(500) DEFAULT NULL,
  `icone` varchar(50) DEFAULT NULL,
  `ordem` int(11) DEFAULT NULL,
  `verificarmodulo` tinyint(3) UNSIGNED DEFAULT NULL,
  `grupomenu_id` int(10) UNSIGNED NOT NULL,
  `data_criacao` datetime DEFAULT NULL,
  `data_exclusao` datetime DEFAULT NULL,
  `modulo_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `app_menu`
--

INSERT INTO `app_menu` (`id`, `menu_id`, `nome`, `url`, `icone`, `ordem`, `verificarmodulo`, `grupomenu_id`, `data_criacao`, `data_exclusao`, `modulo_id`) VALUES
(1, 0, 'Sistema', '#', 'fa fa-cog', 1, NULL, 1, '2018-06-15 11:39:22', NULL, 15),
(2, 0, 'Administrativo', '#', 'fa fa-briefcase', 2, NULL, 1, '2018-06-15 11:39:41', NULL, 14),
(3, 1, 'Menu', '/app/menu/grid', NULL, 3, NULL, 1, '2018-06-15 11:41:16', NULL, 1),
(4, 1, 'Grupos de Módulos', '/app/grupomodulo/grid', NULL, 4, NULL, 1, '2018-06-15 11:41:38', NULL, 0),
(5, 1, 'Módulo', '/app/modulo/grid', NULL, 5, NULL, 1, '2018-06-15 11:49:27', NULL, 3),
(6, 1, 'Rotas', '/app/rotas/grid', NULL, 6, NULL, 1, '2018-06-15 11:49:51', '2018-09-21 11:42:16', 0),
(7, 1, 'Gerador de Códigos', '/app/codigo/create', NULL, 7, NULL, 1, '2018-06-15 11:50:14', NULL, 2),
(8, 2, 'Grupo de Usuários', '/app/grupousuario/grid', NULL, 8, NULL, 1, '2018-06-15 11:55:26', NULL, 5),
(9, 2, 'Usuários', '/app/usuario/grid', NULL, 9, NULL, 1, '2018-06-15 11:57:31', NULL, 6);

-- --------------------------------------------------------

--
-- Estrutura da tabela `app_modulo`
--

CREATE TABLE `app_modulo` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(145) DEFAULT NULL,
  `descricao` varchar(345) DEFAULT NULL,
  `menu_id` int(10) UNSIGNED DEFAULT NULL,
  `grupomodulo_id` int(10) UNSIGNED NOT NULL,
  `data_criacao` datetime DEFAULT NULL,
  `data_exclusao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `app_modulo`
--

INSERT INTO `app_modulo` (`id`, `nome`, `descricao`, `menu_id`, `grupomodulo_id`, `data_criacao`, `data_exclusao`) VALUES
(1, 'app/menu', 'Gerenciamento de menus do sistema', NULL, 1, '2018-06-15 14:39:52', NULL),
(2, 'app/codigo', 'Gerenciamento de gropomodulos do sistema', NULL, 1, '2018-06-15 14:40:14', NULL),
(3, 'app/modulo', 'Gerenciamento de modulos do sistema', NULL, 1, '2018-06-15 14:40:37', NULL),
(4, 'app/rotas', 'Gerenciamento de rotas do sistema', NULL, 1, '2018-06-15 14:41:57', NULL),
(5, 'app/grupousuario', 'Gerenciamento de grupousuarios do sistema', NULL, 2, '2018-06-15 14:43:05', NULL),
(6, 'app/usuario', 'Gerenciamento de usuarios do sistema', NULL, 2, '2018-06-15 14:43:23', NULL),
(7, 'app/permissao', NULL, NULL, 2, '2018-09-21 11:02:50', NULL),
(14, 'app/administrativo', 'Administrativo', NULL, 2, '2020-09-07 14:13:11', NULL),
(15, 'app/sistema', 'Sistema', NULL, 1, '2020-09-07 14:13:32', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `app_permissao`
--

CREATE TABLE `app_permissao` (
  `id` int(10) UNSIGNED NOT NULL,
  `grupousuario_id` int(10) UNSIGNED DEFAULT NULL,
  `usuario_id` int(10) UNSIGNED DEFAULT NULL,
  `create` tinyint(3) UNSIGNED DEFAULT NULL,
  `read` tinyint(3) UNSIGNED DEFAULT NULL,
  `update` tinyint(3) UNSIGNED DEFAULT NULL,
  `delete` tinyint(3) UNSIGNED DEFAULT NULL,
  `print` tinyint(3) UNSIGNED DEFAULT NULL,
  `modulo_id` int(10) UNSIGNED NOT NULL,
  `data_criacao` datetime DEFAULT NULL,
  `data_exclusao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `app_permissao`
--

INSERT INTO `app_permissao` (`id`, `grupousuario_id`, `usuario_id`, `create`, `read`, `update`, `delete`, `print`, `modulo_id`, `data_criacao`, `data_exclusao`) VALUES
(1, 2, NULL, 1, 1, 1, 1, 1, 1, NULL, NULL),
(2, 2, NULL, 1, 1, 1, 1, 1, 2, NULL, NULL),
(3, 2, NULL, 1, 1, 1, 1, 1, 3, NULL, NULL),
(4, 2, NULL, 1, 1, 1, 1, 1, 4, NULL, NULL),
(5, 2, NULL, 1, 1, 1, 1, 1, 5, NULL, NULL),
(6, 2, NULL, 1, 1, 1, 1, 1, 6, NULL, NULL),
(7, 2, NULL, 1, 1, 1, 1, 1, 7, NULL, NULL),
(8, 2, NULL, 1, 1, 1, 1, 1, 13, NULL, NULL),
(9, 2, NULL, 1, 1, 1, 1, 1, 14, NULL, NULL),
(10, 2, NULL, 1, 1, 1, 1, 1, 15, NULL, NULL),
(13, 2, NULL, 1, 1, 1, 1, 1, 18, NULL, NULL),
(14, 2, NULL, 1, 1, 1, 1, 1, 19, NULL, NULL),
(15, 2, NULL, 1, 1, 1, 1, 1, 20, NULL, NULL),
(16, 2, NULL, 1, 1, 1, 1, 1, 21, NULL, NULL),
(17, 2, NULL, 1, 1, 1, 1, 1, 23, NULL, NULL),
(18, 2, NULL, 1, 1, 1, 1, 1, 24, NULL, NULL),
(19, 1, NULL, 0, 0, 0, 0, 0, 1, NULL, NULL),
(20, 1, NULL, 0, 0, 0, 0, 0, 2, NULL, NULL),
(21, 1, NULL, 0, 0, 0, 0, 0, 3, NULL, NULL),
(22, 1, NULL, 0, 0, 0, 0, 0, 4, NULL, NULL),
(23, 1, NULL, 0, 0, 0, 0, 0, 5, NULL, NULL),
(24, 1, NULL, 0, 0, 0, 0, 0, 6, NULL, NULL),
(25, 1, NULL, 0, 0, 0, 0, 0, 7, NULL, NULL),
(26, 1, NULL, 0, 0, 0, 0, 0, 14, NULL, NULL),
(27, 1, NULL, 0, 0, 0, 0, 0, 15, NULL, NULL),
(28, 1, NULL, 0, 0, 0, 0, 0, 18, NULL, NULL),
(29, 1, NULL, 0, 0, 0, 0, 0, 19, NULL, NULL),
(30, 1, NULL, 0, 0, 0, 0, 0, 20, NULL, NULL),
(31, 1, NULL, 0, 0, 0, 0, 0, 21, NULL, NULL),
(32, 1, NULL, 0, 0, 0, 0, 0, 23, NULL, NULL),
(33, 1, NULL, 0, 0, 0, 0, 0, 24, NULL, NULL),
(34, 1, NULL, 1, 1, 1, 1, 1, 25, NULL, NULL),
(35, 3, NULL, 0, 0, 0, 0, 0, 1, NULL, NULL),
(36, 3, NULL, 0, 0, 0, 0, 0, 2, NULL, NULL),
(37, 3, NULL, 0, 0, 0, 0, 0, 3, NULL, NULL),
(38, 3, NULL, 0, 0, 0, 0, 0, 4, NULL, NULL),
(39, 3, NULL, 0, 0, 0, 0, 0, 5, NULL, NULL),
(40, 3, NULL, 0, 0, 0, 0, 0, 6, NULL, NULL),
(41, 3, NULL, 0, 0, 0, 0, 0, 7, NULL, NULL),
(42, 3, NULL, 0, 0, 0, 0, 0, 14, NULL, NULL),
(43, 3, NULL, 0, 0, 0, 0, 0, 15, NULL, NULL),
(44, 3, NULL, 0, 0, 0, 0, 0, 18, NULL, NULL),
(45, 3, NULL, 0, 0, 0, 0, 0, 19, NULL, NULL),
(46, 3, NULL, 0, 0, 0, 0, 0, 20, NULL, NULL),
(47, 3, NULL, 0, 0, 0, 0, 0, 21, NULL, NULL),
(48, 3, NULL, 0, 0, 0, 0, 0, 23, NULL, NULL),
(49, 3, NULL, 0, 0, 0, 0, 0, 24, NULL, NULL),
(50, 3, NULL, 0, 0, 0, 0, 0, 25, NULL, NULL),
(51, 2, NULL, 1, 1, 1, 1, 1, 25, NULL, NULL),
(52, 2, NULL, 1, 1, 1, 1, 1, 26, NULL, NULL),
(53, 2, NULL, 1, 1, 1, 1, 1, 27, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `app_rotas`
--

CREATE TABLE `app_rotas` (
  `id` int(10) UNSIGNED NOT NULL,
  `url` varchar(345) DEFAULT NULL,
  `rota` varchar(345) DEFAULT NULL,
  `descricao` varchar(545) DEFAULT NULL,
  `data_criacao` datetime DEFAULT NULL,
  `data_exclusao` datetime DEFAULT NULL,
  `rotas_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `app_usuario`
--

CREATE TABLE `app_usuario` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `login` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `senha` varchar(40) DEFAULT NULL,
  `data_criacao` datetime DEFAULT NULL,
  `data_alteracao` datetime DEFAULT NULL,
  `foto` varchar(500) DEFAULT NULL,
  `ativo` tinyint(3) UNSIGNED DEFAULT NULL,
  `falhaacesso` tinyint(3) UNSIGNED DEFAULT NULL,
  `bloqueado` tinyint(3) UNSIGNED DEFAULT NULL,
  `grupousuario_id` int(10) UNSIGNED NOT NULL,
  `facebookid` varchar(100) DEFAULT NULL,
  `googleid` varchar(100) DEFAULT NULL,
  `data_exclusao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `app_usuario`
--

INSERT INTO `app_usuario` (`id`, `nome`, `login`, `email`, `senha`, `data_criacao`, `data_alteracao`, `foto`, `ativo`, `falhaacesso`, `bloqueado`, `grupousuario_id`, `facebookid`, `googleid`, `data_exclusao`) VALUES
(1, 'Mario Fabre', 'mario', 'mario@maquinaweb.com.br', '8e3fba1d382f7df9e50f481179db8780ffcd4d71', '2018-09-20 15:00:30', '2020-09-07 13:59:02', NULL, 1, 0, NULL, 2, NULL, NULL, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `app_confirmchange`
--
ALTER TABLE `app_confirmchange`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `app_grupomenu`
--
ALTER TABLE `app_grupomenu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome_UNIQUE` (`nome`);

--
-- Índices para tabela `app_grupomodulo`
--
ALTER TABLE `app_grupomodulo`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `app_grupousuario`
--
ALTER TABLE `app_grupousuario`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `app_log`
--
ALTER TABLE `app_log`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `app_menu`
--
ALTER TABLE `app_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_app_menu_app_grupomenu1_idx` (`grupomenu_id`),
  ADD KEY `fk_app_menu_app_modulo1_idx` (`modulo_id`);

--
-- Índices para tabela `app_modulo`
--
ALTER TABLE `app_modulo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_modulo_grupomodulo1_idx` (`grupomodulo_id`),
  ADD KEY `fk_modulo_menu1_idx` (`menu_id`);

--
-- Índices para tabela `app_permissao`
--
ALTER TABLE `app_permissao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_permissao_grupousuario1_idx` (`grupousuario_id`),
  ADD KEY `fk_permissao_modulo1_idx` (`modulo_id`),
  ADD KEY `fk_app_permissao_app_usuario1_idx` (`usuario_id`);

--
-- Índices para tabela `app_rotas`
--
ALTER TABLE `app_rotas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_app_rotas_app_rotas1_idx` (`rotas_id`);

--
-- Índices para tabela `app_usuario`
--
ALTER TABLE `app_usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_app_usuario_app_grupousuario1_idx` (`grupousuario_id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `app_confirmchange`
--
ALTER TABLE `app_confirmchange`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `app_grupomenu`
--
ALTER TABLE `app_grupomenu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `app_grupomodulo`
--
ALTER TABLE `app_grupomodulo`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `app_grupousuario`
--
ALTER TABLE `app_grupousuario`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `app_log`
--
ALTER TABLE `app_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=198;

--
-- AUTO_INCREMENT de tabela `app_menu`
--
ALTER TABLE `app_menu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de tabela `app_modulo`
--
ALTER TABLE `app_modulo`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de tabela `app_permissao`
--
ALTER TABLE `app_permissao`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT de tabela `app_rotas`
--
ALTER TABLE `app_rotas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `app_usuario`
--
ALTER TABLE `app_usuario`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `app_modulo`
--
ALTER TABLE `app_modulo`
  ADD CONSTRAINT `fk_modulo_grupomodulo1` FOREIGN KEY (`grupomodulo_id`) REFERENCES `app_grupomodulo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_modulo_menu1` FOREIGN KEY (`menu_id`) REFERENCES `app_menu` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
