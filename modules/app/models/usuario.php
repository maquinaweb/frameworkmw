<?php namespace app;

class usuario extends \mwwork\mwmodel {

    static $table_name = 'app_usuario';
    
    static $belongs_to = array(
        array(
            'grupousuario', 
            'class_name' => 'grupousuario', 
            'foreign_key' => 'grupousuario_id'
        )
    );
    
    static $has_many = array(
        array(
            'permissoes', 
            'class_name' => 'permissao', 
            'foreign_key' => 'usuario_id',
            'conditions' => array('data_exclusao is null') 
        ),
        array(
            'siteusuarios', 
            'class_name' => 'siteusuario', 
            'foreign_key' => 'usuario_id',
            'conditions' => array('data_exclusao is null') 
        )
    );
    
    static $validates_presence_of = array(
        array('nome', 'message' => 'Nome é um campo obrigatório!')
    );

    static $validates_format_of = array(
        array(
            'login', 
            'with' => '/^[[:alnum:]]*$/',
            'message' => 'Nome deve conter apenas caracteres alfanuméricos'
        ),
        array(
            'email', 
            'with' => '/^$|(.+)@(.+){2,}\.(.+){2,}/', 
            'message' => 'Formato inválido de e-mail'
        )
    );

    public function post($ignore = array()) {
        parent::post(array_merge($ignore, array('senha')));
        
        if(!empty($_POST['senha'])){
            if(strlen($_POST['senha'])!=40)
                $this->senha = sha1(salt.$_POST['senha']);
        }
        
        $caminho = '/var/userfiles/userphotos/';
        if (!file_exists(ABSPATH . $caminho)) {
            mkdir(ABSPATH . $caminho, 0755, true);
        }
        if(!empty($_FILES['foto']['name'])) {
            $foto = upload($_FILES['foto'], $caminho, array(".png", ".jpg", ".gif"));
            if(isset($foto['erro'])){
                echo $foto['erro'];
                exit;
            }
            if(isset($foto['arquivo'])){
                if (!empty($this->foto))
                    unlink(ABSPATH.$this->foto);
                
                $this->foto = $caminho.$foto['arquivo'];
            }
        }
        else {
            if (!empty($this->foto) and isset($_POST['delete_foto'])){
                if (unlink(ABSPATH.$this->foto))
                   $this->foto = null ;
                $this->foto = null ;
            }
        }
        
        if (!in_array('ativo', $ignore)) {
            if (!isset($_POST['ativo']))
                $this->ativo = 0;
        }
        
    }
    
    public function has_access($server_vars = array()) {
        return true;
    }

    static function filter($requisition_vars = array(), $server_vars = array()) {
        return parent::filter($requisition_vars, $server_vars);
    }
    
    public function getselectgrupousuarioid(){
        $vw = new \mwwork\view('app/painelCMS/select');
        $grupos = \app\grupousuario::all(array(
            'conditions' => array('data_exclusao is null')
        ));
        foreach ($grupos as $grupo){
            $vw->value = $grupo->id;
            $vw->text = $grupo->nome;
            if($this->grupousuario_id==$grupo->id)
                $vw->selected = 'selected="selected"';
            else 
                $vw->clear ('selected');
            $vw->block('linha');
        }
        return $vw->parse();
    }
    
    public function getfotourl() {
        if (!empty($this->foto))
            return HOME_URI . $this->foto;
        else
            return false;
    }
    
    public function getativo(){
        if($this->is_new_record())
            return 1;
        else
            return $this->ativo;
    }
}

?>