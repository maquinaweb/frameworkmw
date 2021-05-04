<?php namespace app;

class siteusuariocontroller extends \mwwork\mwcontroller {

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
    public static $descritive_name = "Siteusuario";
    
    /**
     * Define um nome descritivo plural que, pode ou não, ser o mesmo do controller
     * 
     * @access public
     */
    public static $descritive_name_plural = "Siteusuarios";
    
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
}
