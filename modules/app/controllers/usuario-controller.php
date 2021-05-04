<?php namespace app;

class usuariocontroller extends \mwwork\mwcontroller {

    /**
     * Define os limites possíveis para serem usados em condições para a montagem
     * de um grid.
     * 
     * @access public
     */
    public static $grid_limits = array(
        '10', '50', '100'
    );
    
    /**
     * Define um nome descritivo que, pode ou não, ser o mesmo do controller
     * 
     * @access public
     */
    public static $descritive_name = "Usuário";
    
    /**
     * Define um nome descritivo plural que, pode ou não, ser o mesmo do controller
     * 
     * @access public
     */
    public static $descritive_name_plural = "Usuários";
    
     /**
     * Caminho para o JSON de credenciais do Google oAuth
     */
    public static $GOOGLE_OAUTH_CONFIG_JSON = ABSPATH . '/vendor/google-api-php-client-2.2.0/client_secret.json';
    
    /**
     * URL para redirecionamento após o usuário aceitar compartilhar dados 
     * necessários
     */
    public static $GOOGLE_OAUTH_REDIRECT_URL = HOME_URI . '/app/usuario/lerurl';
    
    /**
     * URL do site principal, usado para autenticação
     */
    public static $AUTH_DOMAIN =  "http://localhost/easypublish3-0/";
    
    
    /**
     * Construtor da classe
     *
     * Configura as propriedades e métodos da classe.
     *
     * @access public
     */
    public function __construct($parameters = array(), $module, $name, $action) {
        parent::__construct($parameters, $module, $name, $action);
    }
    
    /**
     * Monta e insere scrips do form de destaques
     *
     * @access protected
     */
    protected function parse_profile_scripts($permissao = null) {
        if (\mwwork\view::view_exists("$this->module/$this->name/profile/script.js")) {
            $vw = new \mwwork\view("$this->module/$this->name/profile/script.js");

            $vw = $this->set_default_vars($vw, array('error', 'success', 'ref'));

            if (is_object($permissao)) {
                $vw = $this->parse_permission($vw, $permissao);
            }
            return $vw->parse();
        }
        else {
            return "";
        }
        
    }
    
    /**
     * Monta o corpo (linhas) do form de perfil
     *
     * @access protected
     */
    protected function get_profile_body($vw, $object, $permissao = null) {
        $vw = $this->set_default_vars($vw, array('error', 'success'));
        
        $vw->usuario = $object;
        
        $vw->userphoto = $_SESSION['app']['userphoto'];
        
        if (is_object($permissao)) {
            $vw = $this->parse_permission($vw, $permissao);
        }
        
        return $vw;
    }
    
    /**
     * Função padrão de montagem de grids
     *
     * @access public
     */
    public function grid() {
        return parent::grid();
    }
    
    /**
     * Mostra tela de login do sistema
     *
     * @access public
     */
    public function profile() {
        //Verifica se há conexão entro o controlador e uma tabela
        if (!$this->set_table()) {
            show_error_page('404');
        }
        
        //Verifica se há usuário logado
        if (!isset($_SESSION) or 
                !isset($_SESSION['app']) or 
                !isset($_SESSION['app']['usuario_id'])) {
            show_error_page('404');
        }
        
        //Carrega usuário logado
        $usuario = \app\usuario::first(array(
            'conditions' => array('id = ? and data_exclusao is null', $_SESSION['app']['usuario_id'])
        ));

        //Veririca se objeto existe
        if (!is_object($usuario)) {
            show_error_page('404');
        }

        $vw = new \mwwork\view("app/painelCMS/content");
        $vw->addFile('content', 'app/usuario/profile');
        
        $vw->breadcrumbs = $this->parse_breadcrumbs("update$this->action", $usuario);
        
        $vw->scripts = $this->parse_profile_scripts();
        
        $vw = $this->get_profile_body($vw, $usuario);
        
        $this->response($vw);
    }
    
    /**
     * Função padrão de montagem de forms
     *
     * @access public
     */
    public function form() {
        return parent::form();
    }
    
    /**
     * Função para salvar o perfil do usuario
     *
     * @access public
     */
    public function saveprofile() {
        //Inicializa variável de resposta
        $response = array(
            'status'    => '0',     // 0 para erro, 1 para sucesso
            'message'   => null,    // mensagem sobre o resultado
            'errors'    => null,    // contém os erros caso haja algum
            'redirect'  => null     // link de redirecionamento caso haja algum
        );
        
        //Verifica se há usuário logado
        if (!isset($_SESSION) or 
                !isset($_SESSION['app']) or 
                !isset($_SESSION['app']['usuario_id'])) {
            show_error_page('404');
        }
        
        //Carrega usuário logado
        $usuario = \app\usuario::first(array(
            'conditions' => array('id = ? and data_exclusao is null', $_SESSION['app']['usuario_id'])
        ));

        //Veririca se objeto existe
        if (!is_object($usuario)) {
            show_error_page('404');
        }
        
        //Post não pode estar vazio
        if (!empty($_POST)) {
            //Atributos da tabela
            $columns = $usuario->table()->columns;
            $valid_attributes = array('nome', 'senha', 'email', 'login');
            $invalid_attributes = array();

            //Itera colunas para verifica quais attributos são inválidos(geral) 
            foreach ($columns as $column) {
                if (!in_array($column->name, $valid_attributes)) {
                    $invalid_attributes[] = $column->name;
                }
            }

            //Verifica quais attributos são inválidos serão neste requisição espefica
            if (!empty($usuario->login)) {
                $invalid_attributes[] = 'login';
            }

            if (!empty($usuario->email)) {
                $invalid_attributes[] = 'email';
            }

            if (!chk_array($_POST, 'change_password')) {
                $invalid_attributes[] = 'senha';
            }
            else if (empty(chk_array($_POST, 'senha')) or empty(chk_array($_POST, 'senha2'))
                    or chk_array($_POST, 'senha') != chk_array($_POST, 'senha2')) {
                $invalid_attributes[] = 'senha';
            }
            
            $usuario->post($invalid_attributes);
            if ($usuario->is_invalid()) {
                $extension = "error=";

                $raw_errors = $usuario->errors->get_raw_errors();

                foreach ( $raw_errors as $attribute => $errors) {
                    $extension .= "$attribute contém os seguintes erros:" . implode('/', $errors) . "; ";
                }

                $response['errors'] = $errors;
                $response['message'] = "Erro ao salvar! $extension";

            } 
            else {
                if (LOG_ACTIONS) {
                    \app\log::create(array(
                        'usuario_id' => (DEBUG) ? 0 : $_SESSION['app']['usuario_id'],
                        'tipo_usuario' => 'app_usuario',
                        'data' => new \DateTime(),
                        'conteudo_id' => $usuario->id,
                        'conteudo' => $this->table_name,
                        'acao' => 'saveprofile',
                        'extra' => json_encode($usuario->dirty_attributes())
                    ));
                }
                $usuario->save();

                $extension = 'success=Salvo com sucesso. Os dados vão estar em uso a partir da próxima sessão.';

                $response['status'] = '1';
                $response['message'] = "Salvo com sucesso. Os dados vão estar em uso a partir da próxima sessão.";
            }
        }
        else {
            show_error_page('404');
        }
        
        $redirect = '';
        if (method_exists($usuario, 'redirect'))
            $redirect = $object->redirect();
        if (empty($redirect))
            $response['redirect'] = ($this->url . "/$this->module/$this->name/profile" . '?' . $extension . ((!empty(get())) ? '&' . http_build_query(get()) : ''));
        else
            $response['redirect'] = ($this->url . $redirect . ((!empty(get())) ? '?' . http_build_query(get()) : ''));
        
        
        $this->response($response);
    }
    
    /**
     * Função para salvar dados da tabela no banco
     *
     * @access public
     */
    public function save() {
        return parent::save();
    }

    /**
     * Função para excluir um registro na tabela do banco
     *
     * @access public
     */
    public function delete() {
        return parent::delete();
    }
    
    /**
     * Função para excluir um ou mais registros na tabela do banco
     *
     * @access public
     */
    public function deletemultiple() {
        return parent::deletemultiple();
    }
    
    /**
     * Função padrão de exportação de dados
     *
     * @access protected
     */
    public function export() {
        return parent::export();
    }
    
    /**
     * Mostra tela de login do sistema
     *
     * @access public
     */
    public function login() {
        
        $vw = new \mwwork\view("app/usuario/login/body");
        $vw->addFile("head", "app/usuario/login/head");
        $vw->addFile("scripts", "app/usuario/login/scripts");
        
        $vw = $this->set_default_vars($vw);
                
        $vw->plugins = HOME_URI . '/var/plugins/';
        
        $vw->redirect = (isset($_GET['redirect'])) ? $_GET['redirect'] : "";
        
        if (isset($_GET['error'])) {
            $vw->tituloalerta = 'Erro';
            $vw->mensagemalerta = $_GET['error'];
            $vw->tipoalerta = 'danger';
            $vw->icone = 'fa fa-ban';
            $vw->block('block_alert');
        }
        if (isset($_GET['success'])) {
            $vw->tituloalerta = 'Sucesso';
            $vw->mensagemalerta = $_GET['success'];
            $vw->tipoalerta = 'success';
            $vw->icone = 'glyphicon glyphicon-ok';
            $vw->block('block_alert');
        }

        
        /*if (isset($_SESSION) and isset($_SESSION['temp']) and 
                isset($_SESSION['temp']['app_captcha_active']) and $_SESSION['temp']['app_captcha_active']) {
            $captcha = new \mwwork\view ('app/usuario/login/captcha');
            
            $vw->formcaptcha = $captcha->parse();
        }*/

        if (captcha_key_site != ''){
            $vw->formcaptcha = '<div class="form-group"><div class="g-recaptcha" data-sitekey="6Lf-id4ZAAAAAHYlzVBdUZvov-ViwEY2t_gfz2j0"></div></div>';
        }
        
        //$vw->facebook_app_id = '179987335892975';
        //$vw->auth_domain = self::$AUTH_DOMAIN;

        $vw->show();
        die;
        
    }

    /**
     * Verifica os dados enviados e realiza o login
     *
     * @access public
     */
    public function check() {        
        if (captcha_key_site == '' || checarcaptcha(captcha_key_site)) {
            //Numero de falhas necessário para checar captcha
            $falhas_para_captcha = 3;
        
            if (isset($_POST['login']))
                $login = $_POST['login'];
            if (isset($_POST['senha']))
                $senha = sha1(salt . $_POST['senha']);

            $usuario = usuario::find_by_login_or_email($login, $login);
            
            if (is_object($usuario)) {
                //Dados basicos para colocar nos logs
                $log_data = array(
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'sucesso' => '0',
                    'usuario' => $usuario->id
                );
                
                
                //Precisamos guardar uns dados na sessão
                //Após sair daqui, devemos saber que o captcha precisa estar abilitado para esse cara
                if ($usuario->falhaacesso >= $falhas_para_captcha) {
                    if (!isset($_SESSION)) {
                        session_start();
                    }
                    if (!isset($_SESSION['temp'])) {
                        $_SESSION['temp'] = array();
                    }
                    $_SESSION['temp']['app_captcha_active'] = 1;
                }
                else {
                    if (isset($_SESSION) and isset($_SESSION['temp']) and isset($_SESSION['temp']['captcha_active'])) {
                        unset($_SESSION['temp']['app_captcha_active']);
                    }
                }
                
                //Usuario inativo, cai fora
                if (!$usuario->ativo) {
                    //Loga ocorrido
                    $log_data['descritivo'] = "Usuário inativo.";
                    \app\log::create(array(
                        'usuario_id' => $usuario->id,
                        'tipo_usuario' => 'app_usuario',
                        'data' => new \DateTime(),
                        'conteudo_id' => null,
                        'conteudo' => null,
                        'acao' => 'login',
                        'extra' => json_encode($log_data)
                    ));
                    
                    if (isset($_GET['redirect']))
                        $this->redirect(HOME_URI . '/app/usuario/login?error=Usuário não encontrado!&redirect=' . $_GET['redirect']);
                    else
                        $this->redirect(HOME_URI . '/app/usuario/login?error=Usuário não encontrado!');
                }
                
                //Usuário bloqueado, avisa sobre o bloqueio
                if ($usuario->bloqueado) {
                    $bloqueio = bloqueiousuario::first(array(
                        'conditions' => array('usuario_id = ? and ativo = 1',$usuario->id)
                    ));
                    
                    $this->redirect(HOME_URI . '/app/usuario/login?error=Usuário bloqueado');
                    
                    //Loga ocorrido
                    $log_data['descritivo'] = "Usuário bloqueado.";
                    \app\log::create(array(
                        'usuario_id' => $usuario->id,
                        'tipo_usuario' => 'app_usuario',
                        'data' => new \DateTime(),
                        'conteudo_id' => null,
                        'conteudo' => null,
                        'acao' => 'login',
                        'extra' => json_encode($log_data)
                    ));
                }
                
                //Caso o usuário necessite de captcha, verifica se ele existe
                if ($usuario->falhaacesso >= $falhas_para_captcha) {
                    if (!checarcaptcha(capctha_key) and !DEBUG) {
                        
                        //Loga ocorrido
                        $log_data['descritivo'] = "Captcha inválido";
                        \app\log::create(array(
                            'usuario_id' => $usuario->id,
                            'tipo_usuario' => 'app_usuario',
                            'data' => new \DateTime(),
                            'conteudo_id' => null,
                            'conteudo' => null,
                            'acao' => 'login',
                            'extra' => json_encode($log_data)
                        ));

                        if (isset($_GET['redirect']))
                            $this->redirect(HOME_URI . '/app/usuario/login?error=Problemas na autenticação.&redirect=' . $_GET['redirect']);
                        else
                            $this->redirect(HOME_URI . '/app/usuario/login?error=Problemas na autenticação.');
                    }
                }
                
                //Senha errada, cai fora
                if ($usuario->senha != $senha) {
                    
                    //Loga ocorrido
                    $log_data['descritivo'] = "Senha inválida.";
                    \app\log::create(array(
                        'usuario_id' => $usuario->id,
                        'tipo_usuario' => 'app_usuario',
                        'data' => new \DateTime(),
                        'conteudo_id' => null,
                        'conteudo' => null,
                        'acao' => 'login',
                        'extra' => json_encode($log_data)
                    ));

                    //$usuario->add_falha();
                    
                    //Precisamos guardar uns dados na sessão
                    //Após sair daqui, devemos saber que o captcha precisa estar abilitado para esse cara
                    if ($usuario->falhaacesso >= $falhas_para_captcha) {
                        if (!isset($_SESSION)) {
                            session_start();
                        }
                        if (!isset($_SESSION['temp'])) {
                            $_SESSION['temp'] = array();
                        }
                        $_SESSION['temp']['app_captcha_active'] = 1;
                    }
                    
                    if (isset($_GET['redirect']))
                        $this->redirect(HOME_URI . '/app/usuario/login?error=Usuário ou senha inválidos!&redirect=' . $_GET['redirect']);
                    else
                        $this->redirect(HOME_URI . '/app/usuario/login?error=Usuário ou senha inválidos!');
                }
                
                //Loga ocorrido
                $log_data['descritivo'] = "Usuário logado com sucesso.";
                $log_data['sucesso'] = "1";
                \app\log::create(array(
                    'usuario_id' => $usuario->id,
                    'tipo_usuario' => 'app_usuario',
                    'data' => new \DateTime(),
                    'conteudo_id' => null,
                    'conteudo' => null,
                    'acao' => 'login',
                    'extra' => json_encode($log_data)
                ));
                
                $usuario->falhaacesso = 0;
                $usuario->save();
                
                //$siteusuario->ultimo_acesso = new \DateTime();
                //$siteusuario->save();
                
                $session = array();
                if (!isset($_SESSION))
                    session_start();
                else{
                    $_SESSION['temp'] = array();
                    $_SESSION['app'] = array();
                    $_SESSION['conteudo'] = array();
                }
                
                $_SESSION['app']['usuario_id'] = $usuario->id;
                $_SESSION['app']['usuario_alteracao'] = $usuario->data_alteracao->format('Y-m-d H:i:s');
                $_SESSION['app']['nome'] = $usuario->nome;
                $_SESSION['app']['grupo'] = $usuario->grupousuario->nome;;
                $_SESSION['app']['grupo_id'] = $usuario->grupousuario_id;
                
                if(empty($usuario->foto))
                    $_SESSION['app']['userphoto'] = HOME_URI . '/var/userfiles/userphotos/icon-person.png';
                else
                    $_SESSION['app']['userphoto'] = HOME_URI . $usuario->foto;
                
                

                if (isset($_GET['redirect'])) {
                    $this->redirect($_GET['redirect']);
                } else {
                    $this->redirect(HOME_URI . '/gee/'); //redirecionamento inicial
                }
            } else {
                if (isset($_GET['redirect']))
                    $this->redirect(HOME_URI . '/app/usuario/login?error=Usuário ou senha inválido&redirect=' . $_GET['redirect']);
                else
                    $this->redirect(HOME_URI . '/app/usuario/login?error=Usuário ou senha inválido');
            }
        }
        else{
            $this->redirect(HOME_URI . '/app/usuario/login?error=Captcha incorreto');
        }
    }
    
    public function logout() {
        session_destroy();
        $this->redirect($this->url . '/app/usuario/login?success=Usuário desconectado');
    }
    
    public function forgetpass(){
        if(!empty(filter_input(INPUT_POST, 'email'))){
            $usuario = \app\usuario::find_by_email(filter_input(INPUT_POST, 'email'));
            if(is_object($usuario)){
                $hash = sha1(strtotime("now").$usuario->id.$usuario->email);
                $usuario->hashsenha = $hash ;
                $usuario->validadehash = date('Y-m-d ', strtotime("+30 minutes"));
            }
        }
    }
    
}
