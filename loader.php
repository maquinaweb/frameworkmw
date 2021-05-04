<?php
// Evita que usuários acesse este arquivo diretamente
if ( !defined('ABSPATH')) exit;
 
// Inicia a sessão
session_start();

// Verifica o modo para debugar
if ( !defined('DEBUG') || DEBUG === false ) {

	// Esconde todos os erros
	error_reporting(0);
	ini_set("display_errors", 0); 
	
} else {

	// Mostra todos os erros
	error_reporting(E_ALL);
	ini_set("display_errors", 1); 
	
}
// Rotas 
require_once ABSPATH . '/rotas.php';
// Classes padrão do framework

//Funções globais
require_once ABSPATH . '/functions/global-functions.php';

//Classes MWWORK
require_once ABSPATH . '/classes/mwwork/mwwork.php';
require_once ABSPATH . '/classes/mwwork/mwload.php';
require_once ABSPATH . '/classes/ActiveRecord.php';
require_once ABSPATH . '/classes/mwwork/mwmodel.php';
require_once ABSPATH . '/classes/mwwork/mwcontroller.php';

require_once ABSPATH . '/classes/mwwork/template.php';
require_once ABSPATH . '/classes/mwwork/view.php';
require_once ABSPATH . '/classes/utils/emailhelper.php';
require_once ABSPATH . '/classes/utils/trataimagem.php';
require_once ABSPATH . '/classes/utils/contentmanager.php';
//Externos
require_once ABSPATH . '/vendor/google-api-php-client-2.2.0/vendor/autoload.php';

spl_autoload_register("load_models");

// Carrega a aplicação
$mwwork = new mwwork\mwwork($rotas);

