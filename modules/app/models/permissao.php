<?php namespace app;

class permissao extends \mwwork\mwmodel {

    static $table_name = 'app_permissao';
    
    static $belongs_to = array(
        array(
            'modulo', 
            'class_name' => 'modulo', 
            'foreign_key' => 'modulo_id'
        ),
        array(
            'usuario', 
            'class_name' => 'usuario', 
            'foreign_key' => 'usuario_id'
        ),
        array(
            'grupousuario', 
            'class_name' => 'grupousuario', 
            'foreign_key' => 'grupousuario_id'
        )
    );
    
    static $validates_presence_of = array(
        array('modulo_id', 'message' => 'Modulo_id é um campo obrigatório!')
    );

    static $validates_format_of = array(
    );

    public function post($ignore = array()) {
        if($this->is_new_record()) {
            parent::post($ignore);
        }
        else {
            parent::post(array_merge($ignore, array('grupousuario_id','usuario_id')));
        }
        
    }
    
    public function has_access($server_vars = array()) {
        return true;
    }

    static function filter($requisition_vars = array(), $server_vars = array()) {
        return parent::filter($requisition_vars, $server_vars);
    }
}

?>