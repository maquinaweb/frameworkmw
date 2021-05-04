<?php namespace app;

class modulo extends \mwwork\mwmodel {

    static $table_name = 'app_modulo';
    
    static $belongs_to = array(
        array(
            'grupomodulo', 
            'class_name' => 'grupomodulo', 
            'foreign_key' => 'grupomodulo_id'
        )
    );
    
    static $has_one = array(
        array(
            'grupomodulo', 
            'class_name' => 'grupomodulo', 
            'foreign_key' => 'grupomodulo_id',
            'conditions' => array('data_exclusao is null') 
        )
    );
            
    static $has_many = array(
        array(
            'grupomodulos', 
            'class_name' => 'grupomodulo', 
            'foreign_key' => 'grupomodulo_id',
            'conditions' => array('data_exclusao is null') 
        )
    );
    
    static $validates_presence_of = array(
        array('nome', 'message' => 'Nome é um campo obrigatório!')
    );

    static $validates_format_of = array(
    );
    
    static $save_attribute_can_update = array(
        'nome'
    );

    public function post() {
        postmodel($this);
    }
    
    public function has_access($server_vars = array()) {
        return true;
    }

    static function filter($requisition_vars = array(), $server_vars = array()) {
        return parent::filter($requisition_vars, $server_vars);
    }
    
    public function getselectgrupomodulo() {
        $vw = new \mwwork\view('app/home/select');
        $grupos = \app\grupomodulo::all(array(
            'order' => 'nome asc',
            'conditions' => array('data_exclusao is null')
        ));
        foreach ($grupos as $grupo) {
            $vw->value = $grupo->id;
            $vw->text = $grupo->nome;
            if ($this->grupomodulo_id == $grupo->id)
                $vw->selected = 'selected="selected"';
            else
                $vw->clear('selected');
            $vw->block('linha');
        }
        return $vw->parse();
    }
    
    public function getselectmenu(){
        $vw = new \mwwork\view('app/home/select');
        $menus = \app\menu::all(array(
            'order' => 'menu_id, ordem asc',
            'conditions' => array('data_exclusao is null')
        ));
        foreach ($menus as $menu) {
            $vw->value = $menu->id;
            if (is_object($menu->pai))
                $vw->text = $menu->pai->nome.'/'.$menu->nome;
            else
                $vw->text = '/'.$menu->nome;
            if ($this->menu_id == $menu->id)
                $vw->selected = 'selected="selected"';
            else
                $vw->clear('selected');
            $vw->block('linha');
        }
        return $vw->parse();
    }
}

?>