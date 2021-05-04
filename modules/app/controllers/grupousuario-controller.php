<?php namespace app;

class grupousuariocontroller extends \mwwork\mwcontroller {

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
    public static $descritive_name = "Grupo de Usuário";
    
    /**
     * Define um nome descritivo plural que, pode ou não, ser o mesmo do controller
     * 
     * @access public
     */
    public static $descritive_name_plural = "Grupos de Usuário";
    
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
     * Função padrão de montagem de grids
     *
     * @access public
     */
    public function grid() {
        return parent::grid();
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
    
    public function formpermission() {
        $permissao = $this->get_permission("app/permissao", 'read');
        if (!is_object($permissao)) {
            show_error_page('403');
        }
        
        $grupo_id = intval(chk_array($this->parameters, 0));
        
        $vw = new \mwwork\view('app/grupousuario/permissao');
        $vw->grupo_id = $grupo_id;
        
        $modulos = \app\modulo::all(array('conditions' => array('data_exclusao is null'), 'order'=>'grupomodulo_id'));
        $atual = 0;
        foreach ($modulos as $modulo) {
            $permissao = \app\permissao::first(array('conditions'=>array('grupousuario_id =? and modulo_id =? and data_exclusao is null',$grupo_id,$modulo->id)));
            if(is_object($permissao))   
                $vw->permissao = $permissao;
            else
                $vw->permissao = new permissao;
            $vw->grid = $modulo;

            if($atual!=$modulo->grupomodulo_id){
                $atual = $modulo->grupomodulo_id;
                $vw->block('bgrupo');

            }
            $vw->block('linha');
        }
        
        $vw = $this->set_default_vars($vw, array('error', 'success', 'ref'));
        $this->response($vw);
    }
    
    public function savepermission() {
        $permissao = $this->get_permission("app/permissao", array('create', 'update'));
        if (!is_object($permissao)) {
            show_error_page('403');
        }
        
        //Checagem feita no meio
        $grupo_id = intval(chk_array($this->parameters, 0));
        $modulos = \app\modulo::all();
        foreach ($modulos as $modulo) {
            $permissao = \app\permissao::first(array('conditions'=>array('grupousuario_id =? and modulo_id =? and data_exclusao is null',$grupo_id,$modulo->id)));
            if(!is_object($permissao)) {
                //checapermissao('cadastro/permissao', 'create');
                $permissao = new permissao();
            }
            else  {
                //checapermissao('cadastro/permissao', 'update');
            }
            $permissao->grupousuario_id = $grupo_id;
            $permissao->modulo_id = $modulo->id ;
            $permissao->read = fcheckbox($_POST,'read'.$modulo->id);
            $permissao->create = fcheckbox($_POST,'create'.$modulo->id);
            $permissao->update = fcheckbox($_POST,'update'.$modulo->id);
            $permissao->delete = fcheckbox($_POST,'delete'.$modulo->id);
            $permissao->print = fcheckbox($_POST,'print'.$modulo->id);
            $permissao->save();
        }
        
        $response = array(
            'status'    => '1',     // 0 para erro, 1 para sucesso
            'message'   => "Permissões salvas",    // mensagem sobre o resultado
            'redirect'  => (HOME_URI . "/app/grupousuario/grid". ((!empty(get())) ? '?' . http_build_query(get()) . '&success=Permissões salvas'  : '?success=Permissões salvas'))
        );
        
        $this->response($response);
        
    }
}
