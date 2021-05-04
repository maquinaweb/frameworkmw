<?php namespace app;

class grupousuario extends \mwwork\mwmodel {

    static $table_name = 'app_grupousuario';
    
    static $has_many = array(
        array(
            'usuarios', 
            'class_name' => 'usuario', 
            'foreign_key' => 'grupousuario_id',
            'conditions' => array('data_exclusao is null') 
        ),
        array(
            'permissoes', 
            'class_name' => 'permissao', 
            'foreign_key' => 'grupousuario_id',
            'conditions' => array('data_exclusao is null') 
        ),
        array(
            'siteusuarios', 
            'class_name' => 'siteusuario', 
            'foreign_key' => 'grupousuario_id',
            'conditions' => array('data_exclusao is null') 
        )
    );
    
    static $validates_presence_of = array(
        array('nome', 'message' => 'Nome é um campo obrigatório!')
    );

    static $validates_format_of = array(
    );

    public function post($ignore = array()) {
        parent::post($ignore);
    }
    
    public function has_access($server_vars = array()) {
        return true;
    }

    static function filter($requisition_vars = array(), $server_vars = array()) {
        return parent::filter($requisition_vars, $server_vars);
    }
}

?>