<?php namespace app;

class siteusuario extends \mwwork\mwmodel {

    static $table_name = 'app_siteusuario';
    
    static $belongs_to = array(
        array(
            'site',
            'class_name' => '\cms\site',
            'foreign_key' => 'site_id'
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
    
    public function getgrupo(){
        if (!empty($this->grupousuario_id))
            $grupo = grupousuario::find($this->grupousuario_id);
        else
            $grupo = grupousuario::find(usuario::find($this->usuario_id)->grupousuario_id);
        return $grupo;
    }
}

?>