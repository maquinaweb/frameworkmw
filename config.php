<?php

/**
 * Configurações Gerais do Sistema.
 */
// {{{ constants

/**
 * Define a versão do SISTEMA.
 */
define('SYS_VERSION', '3.0.2');

/**
 * Define o caminho raiz do SISTEMA.
 */
define('ABSPATH', dirname(__FILE__));

/**
 * Hash que é misturada as senhas do sistema para aumentar a segurança.
 */
define('salt', 'qukOqx#A0P36unw49nrioD7zHqEiQZOb#jx');

/**
 * Chave secreta do captcha usado para login
 */
define('captcha_key', '6LedUt0ZAAAAAP2Q-lBgLSK_MIgl4wzV3n1WvaiD');
define('captcha_key_site', '');

/**
 * Define o caminho raiz da URL.
 */
define('HOME_URI', isset($_SERVER['HTTPS']) ? "https" : "http" . "://" . $_SERVER['HTTP_HOST'] . '/frameworkmw');

/**
 * URL do banco de dados.
 */
define('DB_HOST', 'localhost');

/**
 * Nome do banco de dados.
 */
define('DB_NAME', 'frameworkmw');

/**
 * Usuário do banco de dados.
 */
define('DB_USER', 'root');

/**
 * Senha do banco de dados.
 */
define('DB_PASSWORD', '');

/**
 * Charset de conexão do banco de dados.
 */
define('DB_CHARSET', 'utf8');

/**
 * Informa se o sistema está em modo teste ou produção.
 * <ul>
 * <li>True = Teste</li>
 * <li>False = Produção</li>
 * </ul>
 */
define('DEBUG', true);

/**
 * Informa de o sistema deve armazenar um log de ações no banco.
 * Ações incluem:
 * <ul>
 * <li>INSERT</li>
 * <li>UPDATE</li>
 * <li>DELETE</li>
 * </ul>
 */
define('LOG_ACTIONS', true);

/**
 * Define o e-mail para qual os logs serão enviados
 */
define('EMAIL_LOG', 'log@maquinaweb.com.br');

/**
 * Módulo padrão.
 */
define('default_module', 'app');

/**
 * Controlador padrão.
 */
define('default_controller', 'cadastro');

/**
 * Requisição padrão.
 */
define('default_action', 'cria');

/**
 * Charset de conexão do banco de dados.
 * <ul>
 * <li>True = Verifica permissão no banco.</li>
 * <li>False = Não.</li>
 * </ul>
 */
define('CHECK_PERMISSION', true);

set_time_limit(300);

// NÃO EDITE DAQUI EM DIANTE.

/**
 * Carrega o loader, que vai carregar a aplicação inteira.
 */
require_once ABSPATH . '/loader.php';
