-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04-Maio-2021 às 15:31
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
CREATE DATABASE IF NOT EXISTS `frameworkmw` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `frameworkmw`;

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

--
-- Extraindo dados da tabela `app_log`
--

INSERT INTO `app_log` (`id`, `data`, `usuario_id`, `tipo_usuario`, `conteudo_id`, `conteudo`, `acao`, `extra`) VALUES
(1, '2018-09-13 09:31:12', 0, 'app_usuario', 1, 'app_grupousuario', 'save/insert', '{\"id\":\"1\",\"nome\":\"Administrativo\",\"descricao\":null,\"data_criacao\":\"2018-09-13T09:31:11-0300\",\"data_exclusao\":null}'),
(2, '2018-09-13 09:31:22', 0, 'app_usuario', 2, 'app_grupousuario', 'save/insert', '{\"id\":\"2\",\"nome\":\"Sistema\",\"descricao\":null,\"data_criacao\":\"2018-09-13T09:31:22-0300\",\"data_exclusao\":null}'),
(3, '2018-09-13 09:31:37', 0, 'app_usuario', 3, 'app_grupousuario', 'save/insert', '{\"id\":\"3\",\"nome\":\"Cliente\",\"descricao\":null,\"data_criacao\":\"2018-09-13T09:31:37-0300\",\"data_exclusao\":null}'),
(4, '2018-09-13 09:31:47', 0, 'app_usuario', 4, 'app_grupousuario', 'save/insert', '{\"id\":\"4\",\"nome\":\"Tecnico\",\"descricao\":null,\"data_criacao\":\"2018-09-13T09:31:47-0300\",\"data_exclusao\":null}'),
(5, '2018-09-13 10:01:05', 0, 'app_usuario', 1, 'app_menu', 'save/update', '{\"nome\":\"Sistema\",\"verificarmodulo\":0}'),
(6, '2018-09-13 10:01:19', 0, 'app_usuario', 2, 'app_menu', 'save/update', '{\"nome\":\"Administrativo\",\"verificarmodulo\":0}'),
(7, '2018-09-13 10:01:29', 0, 'app_usuario', 3, 'app_menu', 'save/update', '{\"nome\":\"Menu\",\"verificarmodulo\":1}'),
(8, '2018-09-13 10:02:04', 0, 'app_usuario', 4, 'app_menu', 'save/update', '{\"nome\":\"Grupos de M\\u00f3dulos\",\"verificarmodulo\":1}'),
(9, '2018-09-13 10:02:17', 0, 'app_usuario', 5, 'app_menu', 'save/update', '{\"nome\":\"M\\u00f3dulo\",\"verificarmodulo\":1}'),
(10, '2018-09-13 10:02:34', 0, 'app_usuario', 6, 'app_menu', 'save/update', '{\"nome\":\"Rotas\",\"verificarmodulo\":1}'),
(11, '2018-09-13 10:02:51', 0, 'app_usuario', 7, 'app_menu', 'save/update', '{\"nome\":\"Gerador de C\\u00f3digos\",\"verificarmodulo\":1}'),
(12, '2018-09-13 10:03:09', 0, 'app_usuario', 8, 'app_menu', 'save/update', '{\"nome\":\"Grupo de Usu\\u00e1rios\",\"verificarmodulo\":1}'),
(13, '2018-09-13 10:03:23', 0, 'app_usuario', 9, 'app_menu', 'save/update', '{\"nome\":\"Usu\\u00e1rios\",\"verificarmodulo\":1}'),
(14, '2018-09-20 15:00:30', 0, 'app_usuario', 1, 'app_usuario', 'save/insert', '{\"id\":\"1\",\"nome\":\"Fabio Henrique de Andrade\",\"login\":\"fabiohandrade\",\"email\":\"fabio@maquinaweb.com.br\",\"senha\":\"805bf325b070797f4f3836a0ecb979b1f4434e3e\",\"data_criacao\":\"2018-09-20T15:00:30-0300\",\"data_alteracao\":\"2018-09-20T15:00:30-0300\",\"foto\":null,\"ativo\":1,\"falhaacesso\":null,\"bloqueado\":null,\"grupousuario_id\":1,\"facebookid\":null,\"googleid\":null,\"data_exclusao\":null}'),
(15, '2018-09-20 16:13:26', 0, 'app_usuario', 1, 'app_usuario', 'save/update', '{\"data_alteracao\":{\"date\":\"2018-09-20 16:13:26.000000\",\"timezone_type\":2,\"timezone\":\"BRT\"},\"grupousuario_id\":2}'),
(16, '2018-09-20 16:46:03', 0, 'app_usuario', 2, 'app_modulo', 'save/update', '{\"nome\":\"app\\/codigo\"}'),
(17, '2018-09-20 16:51:23', 0, 'app_usuario', 3, 'app_menu', 'save/update', '{\"modulo_id\":null}'),
(18, '2018-09-20 16:52:36', 0, 'app_usuario', 3, 'app_menu', 'save/update', '{\"modulo_id\":null}'),
(19, '2018-09-21 09:04:01', 0, 'app_usuario', 3, 'app_menu', 'save/update', '{\"modulo_id\":null}'),
(20, '2018-09-21 09:06:50', 0, 'app_usuario', 3, 'app_menu', 'save/update', '{\"modulo_id\":null}'),
(21, '2018-09-21 09:08:30', 0, 'app_usuario', 3, 'app_menu', 'save/update', '{\"modulo_id\":null}'),
(22, '2018-09-21 10:44:13', 0, 'app_usuario', 3, 'app_menu', 'save/update', '{\"modulo_id\":1}'),
(23, '2018-09-21 10:59:35', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(24, '2018-09-21 11:02:50', 0, 'app_usuario', 7, 'app_modulo', 'save/insert', '{\"id\":\"7\",\"nome\":\"app\\/permissao\",\"descricao\":null,\"grupomodulo_id\":2,\"data_criacao\":\"2018-09-21T11:02:50-0300\",\"data_exclusao\":null}'),
(25, '2018-09-21 11:05:28', 0, 'app_usuario', 4, 'app_menu', 'save/update', '{\"modulo_id\":3}'),
(26, '2018-09-21 11:05:41', 0, 'app_usuario', 5, 'app_menu', 'save/update', '{\"modulo_id\":3}'),
(27, '2018-09-21 11:05:51', 0, 'app_usuario', 6, 'app_menu', 'save/update', '{\"modulo_id\":4}'),
(28, '2018-09-21 11:06:13', 0, 'app_usuario', 7, 'app_menu', 'save/update', '{\"modulo_id\":2}'),
(29, '2018-09-21 11:26:32', 0, 'app_usuario', 8, 'app_menu', 'save/update', '{\"modulo_id\":6}'),
(30, '2018-09-21 11:26:47', 0, 'app_usuario', 9, 'app_menu', 'save/update', '{\"modulo_id\":6}'),
(31, '2018-09-21 11:41:59', 0, 'app_usuario', 6, 'app_menu', 'save/update', '{\"modulo_id\":4}'),
(32, '2018-09-21 11:42:16', 0, 'app_usuario', 6, 'app_menu', 'delete', NULL),
(33, '2018-09-22 13:40:58', 0, 'app_usuario', 8, 'app_modulo', 'save/insert', '{\"id\":\"8\",\"nome\":\"gee\\/tipocliente\",\"descricao\":null,\"grupomodulo_id\":2,\"data_criacao\":\"2018-09-22T13:40:57-0300\",\"data_exclusao\":null}'),
(34, '2018-09-22 13:41:27', 0, 'app_usuario', 10, 'app_menu', 'save/update', '{\"modulo_id\":8}'),
(35, '2018-09-24 16:09:47', 0, 'app_usuario', 1, 'gee_api', 'save/insert', '{\"id\":\"1\",\"nome\":\"Web continental\",\"urlbase\":\"https:\\/\\/infoar.api.flexy.com.br\\/platform\\/api\\/\",\"parametros\":null,\"chave\":\"3vnt2xpfmml4yd4rp7qwlf\",\"data_criacao\":\"2018-09-24T16:09:47-0300\",\"data_exclusao\":null}'),
(36, '2018-09-25 15:05:04', 0, 'app_usuario', 1, 'gee_api', 'save/update', '{\"urlbase\":\"https:\\/\\/infoar.api.flexy.com.br\\/platform\\/api\\/{{item-api}}\\/?token=3vnt2xpfmml4yd4rp7qwlf&referenceCodeStore=28904587000182&version=1 HTTP\\/1.1\"}'),
(37, '2018-09-25 15:05:28', 0, 'app_usuario', 1, 'gee_api', 'save/update', '{\"urlbase\":\"https:\\/\\/infoar.api.flexy.com.br\\/platform\\/api\\/{{item-api}}\\/?token=3vnt2xpfmml4yd4rp7qwlf&referenceCodeStore=28904587000182&version=1 HTTP\\/1.1\"}'),
(38, '2018-09-25 15:06:11', 0, 'app_usuario', 1, 'gee_api', 'save/update', '{\"urlbase\":\"https:\\/\\/infoar.api.flexy.com.br\\/platform\\/api\\/[item-api]\\/?token=3vnt2xpfmml4yd4rp7qwlf&referenceCodeStore=28904587000182&version=1 HTTP\\/1.1\"}'),
(39, '2018-09-25 15:09:44', 0, 'app_usuario', 1, 'gee_api', 'save/update', '{\"urlbase\":\"https:\\/\\/infoar.api.flexy.com.br\\/platform\\/api\\/{{item-api}}\\/?token=3vnt2xpfmml4yd4rp7qwlf&referenceCodeStore=28904587000182&version=1 HTTP\\/1.1\"}'),
(40, '2018-09-25 17:12:57', 0, 'app_usuario', 1, 'gee_api', 'save/update', '{\"urlbase\":\"https:\\/\\/infoar.api.flexy.com.br\\/platform\\/api\\/{{item-api}}\\/?token=3vnt2xpfmml4yd4rp7qwlf&referenceCodeStore=28904587000182&version=1.1\"}'),
(41, '2018-09-26 08:14:28', 0, 'app_usuario', 1, 'gee_api', 'save/update', '{\"urlbase\":\"https:\\/\\/infoar.api.flexy.com.br\\/platform\\/api\\/{{item-api}}\\/?from=2015-01-01T13:45:59&to=2019-01-31T13:45:59&offset=1&limit=15&token=3vnt2xpfmml4yd4rp7qwlf&referenceCodeStore=28904587000182&version=1.1\"}'),
(42, '2018-09-26 08:15:07', 0, 'app_usuario', 1, 'gee_api', 'save/update', '{\"urlbase\":\"https:\\/\\/infoar.api.flexy.com.br\\/platform\\/api\\/{{item-api}}\\/?from=2015-01-01T13:45:59&to=2019-01-31T13:45:59&offset=0&limit=15&token=3vnt2xpfmml4yd4rp7qwlf&referenceCodeStore=28904587000182&version=1.1\"}'),
(43, '2018-09-26 08:44:33', 0, 'app_usuario', 1, 'gee_api', 'save/update', '{\"urlbase\":\"https:\\/\\/infoar.api.flexy.com.br\\/platform\\/api\\/{{item-api}}\\/?token=3vnt2xpfmml4yd4rp7qwlf&referenceCodeStore=28904587000182&version=1.1\",\"chave\":\"from=2015-01-01T13:45:59&to=2019-01-31T13:45:59&offset=0&limit=15&\"}'),
(44, '2018-09-27 15:00:47', 0, 'app_usuario', 1, 'gee_api', 'save/update', '{\"parametros\":\"[]\"}'),
(45, '2018-09-27 15:01:46', 0, 'app_usuario', 1, 'gee_api', 'save/update', '{\"parametros\":\"[{\\\"Nome\\\":\\\"fabio\\\",\\\"Valor\\\":\\\"h \\\",\\\"Descri\\u00e7\\u00e3o\\\":\\\"andrade\\\"},{\\\"Nome\\\":\\\"hugo\\\",\\\"Valor\\\":\\\"de\\\",\\\"Descri\\u00e7\\u00e3o\\\":\\\"andrade\\\"}]\"}'),
(46, '2018-10-09 16:08:54', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(47, '2018-10-09 16:36:22', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(48, '2018-10-15 09:33:05', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"0\",\"usuario\":1,\"descritivo\":\"Senha inv\\u00e1lida.\"}'),
(49, '2018-10-15 09:33:39', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(50, '2020-06-02 10:09:40', 0, 'app_usuario', 14, 'app_menu', 'save/update', '{\"modulo_id\":13}'),
(51, '2020-06-02 10:10:11', 0, 'app_usuario', 14, 'app_menu', 'save/update', '{\"icone\":\"fa fa-user\",\"modulo_id\":13}'),
(52, '2020-09-07 13:55:38', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"0\",\"usuario\":1,\"descritivo\":\"Senha inv\\u00e1lida.\"}'),
(53, '2020-09-07 13:56:34', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"0\",\"usuario\":1,\"descritivo\":\"Senha inv\\u00e1lida.\"}'),
(54, '2020-09-07 13:58:06', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(55, '2020-09-07 13:59:02', 0, 'app_usuario', 1, 'app_usuario', 'save/update', '{\"nome\":\"Mario Fabre\",\"login\":\"mario\",\"email\":\"mario@maquinaweb.com.br\",\"senha\":\"8e3fba1d382f7df9e50f481179db8780ffcd4d71\",\"data_alteracao\":{\"date\":\"2020-09-07 13:59:02.000000\",\"timezone_type\":1,\"timezone\":\"-03:00\"}}'),
(56, '2020-09-07 13:59:47', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(57, '2020-09-07 14:10:55', 0, 'app_usuario', 3, 'app_menu', 'save/update', '{\"modulo_id\":1}'),
(58, '2020-09-07 14:11:37', 0, 'app_usuario', 5, 'app_menu', 'save/update', '{\"modulo_id\":3}'),
(59, '2020-09-07 14:11:53', 0, 'app_usuario', 7, 'app_menu', 'save/update', '{\"modulo_id\":2}'),
(60, '2020-09-07 14:12:03', 0, 'app_usuario', 8, 'app_menu', 'save/update', '{\"modulo_id\":5}'),
(61, '2020-09-07 14:12:13', 0, 'app_usuario', 14, 'app_menu', 'save/update', '{\"modulo_id\":13}'),
(62, '2020-09-07 14:13:11', 0, 'app_usuario', 14, 'app_modulo', 'save/insert', '{\"id\":\"14\",\"nome\":\"app\\/administrativo\",\"descricao\":\"Administrativo\",\"menu_id\":null,\"grupomodulo_id\":2,\"data_criacao\":\"2020-09-07T14:13:11-0300\",\"data_exclusao\":null}'),
(63, '2020-09-07 14:13:32', 0, 'app_usuario', 15, 'app_modulo', 'save/insert', '{\"id\":\"15\",\"nome\":\"app\\/sistema\",\"descricao\":\"Sistema\",\"menu_id\":null,\"grupomodulo_id\":3,\"data_criacao\":\"2020-09-07T14:13:32-0300\",\"data_exclusao\":null}'),
(64, '2020-09-07 14:13:57', 0, 'app_usuario', 1, 'app_menu', 'save/update', '{\"modulo_id\":15}'),
(65, '2020-09-07 14:14:09', 0, 'app_usuario', 2, 'app_menu', 'save/update', '{\"modulo_id\":14}'),
(66, '2020-09-07 14:27:18', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(67, '2020-09-07 14:27:47', 0, 'app_usuario', 15, 'app_menu', 'save/update', '{\"modulo_id\":16}'),
(68, '2020-09-07 14:32:05', 0, 'app_usuario', 1, 'gee_produto', 'save/insert', '{\"id\":\"1\",\"nome\":\"teste\",\"descricao\":\"teste do produto\",\"imagem\":\"ergewrg\",\"data_exclusao\":null}'),
(69, '2020-09-07 14:43:56', 0, 'app_usuario', 16, 'app_menu', 'save/update', '{\"modulo_id\":17}'),
(70, '2020-09-07 14:45:05', 0, 'app_usuario', 1, 'gee_marca', 'save/insert', '{\"id\":\"1\",\"nome\":\"marca1\",\"data_exclusao\":null,\"data_criacao\":\"2020-09-07T14:45:05-0300\"}'),
(71, '2020-09-08 10:44:37', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(72, '2020-09-18 09:27:11', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(73, '2020-09-18 09:33:08', 0, 'app_usuario', 17, 'app_modulo', 'delete', NULL),
(74, '2020-09-18 09:33:20', 0, 'app_usuario', 16, 'app_modulo', 'delete', NULL),
(75, '2020-09-18 09:36:05', 0, 'app_usuario', 9, 'app_menu', 'save/update', '{\"modulo_id\":6}'),
(76, '2020-09-18 10:05:50', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(77, '2020-09-18 10:06:02', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(78, '2020-09-18 10:06:35', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(79, '2020-09-18 10:06:45', 0, 'app_usuario', 19, 'app_menu', 'delete', NULL),
(80, '2020-09-18 10:06:57', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(81, '2020-09-18 10:07:37', 0, 'app_usuario', 20, 'app_modulo', 'save/update', '{\"descricao\":\"Cadastro de tipo de veiculo\"}'),
(82, '2020-09-18 10:07:59', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(83, '2020-09-18 10:13:30', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(84, '2020-09-18 10:16:17', 0, 'app_usuario', 15, 'app_modulo', 'save/update', '{\"grupomodulo_id\":1}'),
(85, '2020-09-18 10:17:40', 0, 'app_usuario', 17, 'app_menu', 'save/update', '{\"modulo_id\":18}'),
(86, '2020-09-18 10:18:15', 0, 'app_usuario', 18, 'app_menu', 'save/update', '{\"modulo_id\":19}'),
(87, '2020-09-18 10:18:28', 0, 'app_usuario', 18, 'app_menu', 'save/update', '{\"modulo_id\":19}'),
(88, '2020-09-18 10:18:50', 0, 'app_usuario', 20, 'app_menu', 'save/update', '{\"modulo_id\":21}'),
(89, '2020-09-18 10:19:09', 0, 'app_usuario', 21, 'app_menu', 'save/update', '{\"modulo_id\":20}'),
(90, '2020-09-18 10:19:29', 0, 'app_usuario', 22, 'app_menu', 'save/update', '{\"modulo_id\":23}'),
(91, '2020-09-18 10:19:45', 0, 'app_usuario', 23, 'app_menu', 'save/update', '{\"modulo_id\":24}'),
(92, '2020-09-18 11:25:37', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(93, '2020-09-18 11:29:10', 0, 'app_usuario', 1, 'gee_cliente', 'save/insert', '{\"id\":\"1\",\"nome\":\"Mario\",\"cpf\":\"99999999999\",\"email\":\"mario@teste.com\",\"celular\":\"999999999\",\"endereco\":\"endere\\u00e7o teste\",\"complemento\":null,\"cep\":\"15000000\",\"bairro\":\"bairro teste\",\"cidade\":\"catanduva\",\"uf\":\"SP\",\"data_criacao\":\"2020-09-18T11:29:10-0300\",\"data_exclusao\":null}'),
(94, '2020-09-18 11:29:38', 0, 'app_usuario', 1, 'gee_tipovistoria', 'save/insert', '{\"id\":\"1\",\"nome\":\"Vistoria padr\\u00e3o\",\"data_criacao\":\"2020-09-18T11:29:38-0300\",\"data_exclusao\":null}'),
(95, '2020-09-18 11:29:57', 0, 'app_usuario', 1, 'gee_tipoveiculo', 'save/insert', '{\"id\":\"1\",\"nome\":\"Veiculo Passeio\",\"data_criacao\":\"2020-09-18T11:29:57-0300\",\"data_exclusao\":null}'),
(96, '2020-09-18 11:30:57', 0, 'app_usuario', 1, 'gee_veiculo', 'save/insert', '{\"id\":\"1\",\"ano\":2015,\"modelo\":\"gol\",\"placa\":\"aaa-0000\",\"chassi\":\"789654489156\",\"cor\":\"preto\",\"cliente_id\":1,\"tipoveiculo_id\":1,\"data_criacao\":\"2020-09-18T11:30:57-0300\",\"data_exclusao\":null}'),
(97, '2020-09-18 11:32:27', 0, 'app_usuario', 1, 'gee_vistoria', 'save/insert', '{\"id\":\"1\",\"veiculo_id\":1,\"tipovistoria_id\":1,\"data_finalizacao\":null,\"data_criacao\":\"2020-09-18T11:32:27-0300\",\"data_exclusao\":null,\"hash\":\"7ad26e38dc224619901215c556fd98b7b467a905\"}'),
(98, '2020-09-22 10:02:25', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(99, '2020-09-22 10:05:05', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(100, '2020-09-22 10:06:15', 0, 'app_usuario', 25, 'app_modulo', 'save/update', 'null'),
(101, '2020-09-22 10:06:31', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(102, '2020-09-22 10:06:51', 0, 'app_usuario', 24, 'app_menu', 'save/update', '{\"modulo_id\":25}'),
(103, '2020-09-22 10:08:05', 0, 'app_usuario', 25, 'app_modulo', 'save/update', 'null'),
(104, '2020-09-22 10:08:24', 0, 'app_usuario', 1, 'app_grupousuario', 'save/update', 'null'),
(105, '2020-09-22 10:08:27', 0, 'app_usuario', 1, 'app_grupousuario', 'save/update', 'null'),
(106, '2020-09-22 10:08:57', 0, 'app_usuario', 24, 'app_menu', 'save/update', '{\"menu_id\":2,\"modulo_id\":25}'),
(107, '2020-09-22 10:09:10', 0, 'app_usuario', 24, 'app_menu', 'save/update', '{\"menu_id\":0,\"modulo_id\":25}'),
(108, '2020-09-22 10:09:27', 0, 'app_usuario', 23, 'app_menu', 'save/update', '{\"modulo_id\":24}'),
(109, '2020-09-22 10:09:29', 0, 'app_usuario', 23, 'app_menu', 'save/update', '{\"modulo_id\":24}'),
(110, '2020-09-22 10:09:39', 0, 'app_usuario', 24, 'app_menu', 'save/update', '{\"modulo_id\":25}'),
(111, '2020-09-22 10:11:41', 0, 'app_usuario', 24, 'app_modulo', 'save/update', 'null'),
(112, '2020-09-22 10:11:49', 0, 'app_usuario', 25, 'app_modulo', 'save/update', 'null'),
(113, '2020-09-22 10:12:11', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(114, '2020-09-22 10:14:37', 0, 'app_usuario', 1, 'app_grupousuario', 'save/update', 'null'),
(115, '2020-09-22 10:15:19', 0, 'app_usuario', 1, 'app_grupousuario', 'save/update', 'null'),
(116, '2020-09-22 10:15:25', 0, 'app_usuario', 1, 'app_grupousuario', 'save/update', 'null'),
(117, '2020-09-22 10:33:42', 0, 'app_usuario', 25, 'app_menu', 'save/update', '{\"modulo_id\":26}'),
(118, '2020-09-22 10:34:02', 0, 'app_usuario', 26, 'app_modulo', 'save/update', 'null'),
(119, '2020-09-24 15:11:53', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(120, '2020-09-24 15:12:33', 0, 'app_usuario', 18, 'app_menu', 'delete', NULL),
(121, '2020-09-24 15:12:52', 0, 'app_usuario', 25, 'app_menu', 'delete', NULL),
(122, '2020-09-25 08:42:22', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(123, '2020-09-25 13:48:00', 0, 'app_usuario', 2, 'gee_vistoria', 'save/insert', '{\"id\":\"2\",\"veiculo_id\":1,\"tipovistoria_id\":0,\"data_finalizacao\":null,\"data_criacao\":\"2020-09-25T13:48:00-0300\",\"data_exclusao\":null,\"hash\":\"d4d32d0b6853385bc94ded37f740032ff34cee77\"}'),
(124, '2020-09-25 13:49:59', 0, 'app_usuario', 3, 'gee_vistoria', 'save/insert', '{\"id\":\"3\",\"veiculo_id\":1,\"tipovistoria_id\":1,\"data_finalizacao\":null,\"data_criacao\":\"2020-09-25T13:49:59-0300\",\"data_exclusao\":null,\"hash\":\"b0a66b2a1d3f3122b9cea3d4bf71191fc5502ae8\"}'),
(125, '2020-09-25 13:50:09', 0, 'app_usuario', 2, 'gee_vistoria', 'delete', NULL),
(126, '2020-09-25 13:50:54', 0, 'app_usuario', 4, 'gee_vistoria', 'save/insert', '{\"id\":\"4\",\"veiculo_id\":1,\"tipovistoria_id\":1,\"data_finalizacao\":null,\"data_criacao\":\"2020-09-25T13:50:54-0300\",\"data_exclusao\":null,\"hash\":\"b1caa6d78478c83d4dac62291df66abc8e662a50\"}'),
(127, '2020-09-25 14:49:14', 0, 'app_usuario', 5, 'gee_vistoria', 'save/insert', '{\"id\":\"5\",\"veiculo_id\":1,\"tipovistoria_id\":1,\"data_finalizacao\":null,\"data_criacao\":\"2020-09-25T14:49:14-0300\",\"data_exclusao\":null,\"hash\":\"2fe00687e1257d412ad8f3b45315438172035b31\"}'),
(128, '2020-09-25 14:49:41', 0, 'app_usuario', 5, 'gee_vistoria', 'save/update', '{\"veiculo_id\":1}'),
(129, '2020-09-30 11:53:37', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(130, '2020-09-30 11:55:54', 0, 'app_usuario', 6, 'gee_vistoria', 'save/insert', '{\"id\":\"6\",\"veiculo_id\":2,\"tipovistoria_id\":1,\"data_finalizacao\":null,\"data_criacao\":\"2020-09-30T11:55:54-0300\",\"data_exclusao\":null,\"hash\":\"214fd7287e9f6fb806b2e9795012957c9625e00b\"}'),
(131, '2020-10-01 08:41:31', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(132, '2020-10-05 10:10:12', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(133, '2020-10-06 08:33:04', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(134, '2020-10-07 09:23:02', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(135, '2020-10-08 09:05:41', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(136, '2020-10-09 14:22:05', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(137, '2020-10-13 08:22:16', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(138, '2020-10-13 15:31:44', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(139, '2020-10-14 08:20:48', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(140, '2020-10-14 10:44:06', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(141, '2020-10-15 09:06:07', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(142, '2020-10-16 08:35:51', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(143, '2020-10-16 10:28:03', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(144, '2020-10-16 10:28:29', 0, 'app_usuario', 26, 'app_menu', 'save/update', '{\"modulo_id\":27}'),
(145, '2020-10-19 10:23:05', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(146, '2020-10-23 08:32:22', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(147, '2020-10-26 08:31:18', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(148, '2020-10-26 14:17:10', 0, 'app_usuario', 17, 'app_menu', 'save/update', '{\"icone\":\"fa fa-user\",\"modulo_id\":18}'),
(149, '2020-10-26 14:30:49', 0, 'app_usuario', 20, 'app_menu', 'save/update', '{\"icone\":\"fa fa-poll-h\",\"modulo_id\":21}'),
(150, '2020-10-26 14:31:02', 0, 'app_usuario', 20, 'app_menu', 'save/update', '{\"modulo_id\":21}'),
(151, '2020-10-26 14:32:50', 0, 'app_usuario', 22, 'app_menu', 'save/update', '{\"icone\":\"fa fa-car-side\",\"modulo_id\":23}'),
(152, '2020-10-26 14:33:03', 0, 'app_usuario', 22, 'app_menu', 'save/update', '{\"icone\":\"fa fa-car\",\"modulo_id\":23}'),
(153, '2020-10-26 14:34:45', 0, 'app_usuario', 20, 'app_menu', 'save/update', '{\"icone\":\"fa fa-list\",\"modulo_id\":21}'),
(154, '2020-10-26 14:35:05', 0, 'app_usuario', 21, 'app_menu', 'save/update', '{\"icone\":\"fa fa-list\",\"modulo_id\":20}'),
(155, '2020-10-26 14:36:11', 0, 'app_usuario', 23, 'app_menu', 'save/update', '{\"icone\":\"fa fa-search\",\"modulo_id\":24}'),
(156, '2020-10-26 14:37:38', 0, 'app_usuario', 26, 'app_menu', 'save/update', '{\"icone\":\"fa fa-hand-peace\",\"modulo_id\":27}'),
(157, '2020-10-26 14:38:03', 0, 'app_usuario', 26, 'app_menu', 'save/update', '{\"icone\":\"fa fa-hand-scissors\",\"modulo_id\":27}'),
(158, '2020-10-26 14:40:22', 0, 'app_usuario', 26, 'app_menu', 'save/update', '{\"icone\":\"fa fa-thumbs-up\",\"modulo_id\":27}'),
(159, '2020-10-26 14:50:02', 0, 'app_usuario', 7, 'gee_vistoria', 'save/insert', '{\"id\":\"7\",\"veiculo_id\":1,\"tipovistoria_id\":1,\"status\":\"Pendente\",\"data_finalizacao\":null,\"data_criacao\":\"2020-10-26T14:50:02-0300\",\"data_exclusao\":null,\"hash\":\"0cd81270e030b52a9ddd0a92614c276b3a21eaaf\"}'),
(160, '2020-10-26 15:03:42', 0, 'app_usuario', 7, 'gee_vistoria', 'delete', NULL),
(161, '2020-10-26 15:03:55', 0, 'app_usuario', 8, 'gee_vistoria', 'save/insert', '{\"id\":\"8\",\"veiculo_id\":1,\"tipovistoria_id\":1,\"status\":\"Pendente\",\"data_finalizacao\":null,\"data_criacao\":\"2020-10-26T15:03:55-0300\",\"data_exclusao\":null,\"hash\":\"00ffd66121c2566451f565ac3900ef672c00cab0\"}'),
(162, '2020-10-27 08:23:41', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(163, '2020-10-27 08:33:48', 0, 'app_usuario', 17, 'app_menu', 'save/update', '{\"nome\":\"Clientes\",\"modulo_id\":18}'),
(164, '2020-10-27 08:34:14', 0, 'app_usuario', 20, 'app_menu', 'save/update', '{\"nome\":\"Tipos de vistoria\",\"modulo_id\":21}'),
(165, '2020-10-27 08:34:34', 0, 'app_usuario', 21, 'app_menu', 'save/update', '{\"nome\":\"Tipos de veiculo\",\"modulo_id\":20}'),
(166, '2020-10-27 08:34:43', 0, 'app_usuario', 22, 'app_menu', 'save/update', '{\"nome\":\"Veiculos\",\"modulo_id\":23}'),
(167, '2020-10-27 08:34:49', 0, 'app_usuario', 23, 'app_menu', 'save/update', '{\"nome\":\"Vistorias\",\"modulo_id\":24}'),
(168, '2020-10-27 08:34:58', 0, 'app_usuario', 26, 'app_menu', 'save/update', '{\"nome\":\"Gestos de captcha\",\"modulo_id\":27}'),
(169, '2020-10-27 10:25:07', 0, 'app_usuario', 9, 'gee_vistoria', 'save/insert', '{\"id\":\"9\",\"veiculo_id\":3,\"tipovistoria_id\":1,\"status\":\"Pendente\",\"data_finalizacao\":null,\"data_criacao\":\"2020-10-27T10:25:07-0300\",\"data_exclusao\":null,\"hash\":\"2509a876230ccba7b40a09cd876ea5dc9fddb5ad\"}'),
(170, '2020-10-27 11:19:46', 0, 'app_usuario', 2, 'gee_tipoveiculo', 'save/insert', '{\"id\":\"2\",\"nome\":\"Veiculo Largo\",\"data_criacao\":\"2020-10-27T11:19:46-0300\",\"data_exclusao\":null}'),
(171, '2020-10-27 11:20:23', 0, 'app_usuario', 2, 'gee_tipoveiculo', 'save/update', 'null'),
(172, '2020-10-27 11:22:05', 0, 'app_usuario', 2, 'gee_tipoveiculo', 'save/update', 'null'),
(173, '2020-10-27 11:22:24', 0, 'app_usuario', 3, 'gee_veiculo', 'save/update', '{\"tipoveiculo_id\":2}'),
(174, '2020-10-27 11:28:32', 0, 'app_usuario', 2, 'gee_veiculo', 'save/update', '{\"tipoveiculo_id\":1}'),
(175, '2020-10-28 14:29:45', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(176, '2020-10-29 08:49:40', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(177, '2020-10-30 08:29:31', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(178, '2020-10-30 11:31:19', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(179, '2020-10-30 14:40:21', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(180, '2020-10-30 14:40:51', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(181, '2020-10-30 15:02:34', 0, 'app_usuario', 1, 'gee_veiculo', 'save/update', '{\"cliente_id\":1}'),
(182, '2020-10-30 15:02:41', 0, 'app_usuario', 1, 'gee_veiculo', 'save/update', '{\"cliente_id\":1}'),
(183, '2020-10-30 15:04:43', 0, 'app_usuario', 4, 'gee_veiculo', 'save/insert', '{\"id\":\"4\",\"ano\":2000,\"modelo\":\"uno\",\"placa\":\"aaa0002\",\"chassi\":\"4564854\",\"cor\":\"verde\",\"cliente_id\":4,\"tipoveiculo_id\":1,\"data_criacao\":\"2020-10-30T15:04:43-0300\",\"data_exclusao\":null}'),
(184, '2020-10-30 15:15:07', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(185, '2020-10-30 15:17:19', 0, 'app_usuario', 5, 'gee_veiculo', 'save/insert', '{\"id\":\"5\",\"ano\":2002,\"modelo\":\"honda civic\",\"placa\":\"aaa0003\",\"chassi\":\"5484354684\",\"cor\":\"Prata\",\"cliente_id\":4,\"tipoveiculo_id\":1,\"data_criacao\":\"2020-10-30T15:17:19-0300\",\"data_exclusao\":null}'),
(186, '2020-10-30 17:25:00', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(187, '2020-11-11 14:12:55', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(188, '2020-11-16 08:26:41', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(189, '2020-11-16 08:38:55', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(190, '2020-11-18 09:04:36', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(191, '2020-11-27 08:48:04', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(192, '2020-12-02 15:33:34', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(193, '2020-12-03 07:52:32', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(194, '2021-02-09 09:19:47', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(195, '2021-02-10 09:38:17', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}'),
(196, '2021-02-11 09:35:17', 1, 'app_usuario', NULL, NULL, 'login', '{\"ip\":\"::1\",\"sucesso\":\"1\",\"usuario\":1,\"descritivo\":\"Usu\\u00e1rio logado com sucesso.\"}');

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
(9, 2, 'Usuários', '/app/usuario/grid', NULL, 9, NULL, 1, '2018-06-15 11:57:31', NULL, 6),
(17, 0, 'Clientes', '/gee/cliente/grid', 'fa fa-user', 10, NULL, 1, NULL, NULL, 18);

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
(15, 'app/sistema', 'Sistema', NULL, 1, '2020-09-07 14:13:32', NULL),
(18, 'gee/cliente', 'Cadastro de cliente', NULL, 2, NULL, NULL);

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

-- --------------------------------------------------------

--
-- Estrutura da tabela `gee_cliente`
--

CREATE TABLE `gee_cliente` (
  `id` int(11) NOT NULL,
  `nome` varchar(60) DEFAULT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `email` varchar(15) DEFAULT NULL,
  `celular` varchar(14) DEFAULT NULL,
  `endereco` varchar(60) DEFAULT NULL,
  `complemento` varchar(60) DEFAULT NULL,
  `cep` varchar(8) DEFAULT NULL,
  `bairro` varchar(30) DEFAULT NULL,
  `cidade` varchar(30) DEFAULT NULL,
  `uf` varchar(2) DEFAULT NULL,
  `data_criacao` datetime DEFAULT NULL,
  `data_exclusao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `gee_cliente`
--

INSERT INTO `gee_cliente` (`id`, `nome`, `cpf`, `email`, `celular`, `endereco`, `complemento`, `cep`, `bairro`, `cidade`, `uf`, `data_criacao`, `data_exclusao`) VALUES
(1, 'Mario', '47544588858', 'mario@teste.com', '999999999', 'endereço teste', 'ap 157', '15000000', 'bairro teste', 'catanduva', 'SP', '2020-09-18 11:29:10', NULL),
(2, 'José Antonio', '1234567891011', 'jose@teste.com', '9999999', 'rua vistoria', 'ap 1', '1544444', 'centro', 'catanduva', 'SP', '2020-09-30 11:55:54', NULL),
(3, 'Adailton Cleido', '09826500860', 'adailton@gmail.', '99999999', 'av rua', NULL, '1500000', 'jd Oliveira', 'Catanduva', 'SP', '2020-10-27 10:25:07', NULL),
(4, 'André Luiz Souza', '47544588859', 'andre123@gmail.', '99999999999', 'Rua Concórdia', 'ap 2', '157629', 'bairro jardim', 'Campinas', 'SP', '2020-10-30 15:04:43', NULL);

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
-- Índices para tabela `gee_cliente`
--
ALTER TABLE `gee_cliente`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=197;

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
-- AUTO_INCREMENT de tabela `gee_cliente`
--
ALTER TABLE `gee_cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
