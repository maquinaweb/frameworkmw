<?php
namespace mwwork ;
class view extends \Template 
{
    public $master_page ;
    public $cache_version = 0;
    public $cache_expires = 0;

    public function __construct($local, $accurate = false) {
        $local = "/modules/" . preg_replace("/\//", '/views/', $local, 1);

        if (empty(pathinfo($local, PATHINFO_EXTENSION)))
            $local.='.html';
        $filename = ABSPATH . $local;
        parent::__construct($filename, $accurate);
    }
    
    static function view_exists($local) {
        $local = "/modules/" . preg_replace("/\//", '/views/', $local, 1);

        if (empty(pathinfo($local, PATHINFO_EXTENSION)))
            $local.='.html';
        $filename = ABSPATH . $local;
        return file_exists($filename);
    }

    public function addFile($varname, $local) {
        $local = "/modules/" . preg_replace("/\//", '/views/', $local, 1);
        
        if (empty(pathinfo($local, PATHINFO_EXTENSION)))
            $local.='.html';
        $filename = ABSPATH . $local;
        parent::addFile($varname, $filename);
    }
    
    public function to_pdf($filename = null, $overwrite = false){
        //colocar as coisas aqui $ filename para salvar se vazio mostra na tela
        include_once(ABSPATH. "/externo/mpdf60/mpdf.php");
        
        //$mpdf=new mPDF('pt','A4','','' , 5 , 5 , 5 , 5 , 0 , 0);
        $mpdf=new mPDF();
        //$mpdf->SetDisplayMode('fullpage');
        $mpdf->allow_charset_conversion = true;
        //$mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list
        $mpdf->WriteHTML($this->parse());
        
        if ($filename) {
            $dir = dirname($filename);
            if (!file_exists($dir))
                 mkdir( $dir, 0777, true);
            else if (file_exists($filename) and $overwrite) {
                if ($overwrite === true) {
                    $mpdf->Output($filename, 'F');
                    return true;
                }
                else {
                    return false;
                }
                    
            }
            else {
                $mpdf->Output($filename, 'F');
                return true;
            }
            
        }
        else {
            $mpdf->Output();
            die;
        }
    }
    public function masterpage($name) {
        // Função para colocar a pagina que a view irá herdar 
        // por padrão é o nome da master page .php dentro da pasta /views/masterpage
        if(file_exists(ABSPATH.'/modules/app/views/masterpage/'.$name.'.php')){
            require_once ABSPATH.'/modules/app/views/masterpage/'.$name.'.php';
        }
        
    }
    
    public function show($replace = false) {
        // Substitui o padrão pelo masterpage caso tenha
        if(is_object($this->master_page)){
            if (!$replace)
                $this->master_page->content = parent::parse();
            else
                $this->master_page->content = replace(parent::parse(), '{', '{{_}');
            $this->master_page->show();
        }else{
            echo parent::parse();
        }
        
    }
    
    public function parse() {
        // Substitui o padrão pelo masterpage caso tenha
        if(is_object($this->master_page)){
            $this->master_page->content = parent::parse();
            return $this->master_page->parse();
        }else{
            return parent::parse();
        }
    }
    
    public static function save_cache($name=null,$object=null){
        file_put_contents(ABSPATH.'/var/cache/'.$name.'.cache', serialize($object));
    }
    
    public static function cache($name=null){
        if(!file_exists(ABSPATH.'/var/cache/'.$name.'.cache'))
            return null ;
        return unserialize(file_get_contents(ABSPATH.'/var/cache/'.$name.'.cache'));
        
    }
    
    public static function remove_cache($name=null){
        if($this->cache_exists($name))
            return unlink(ABSPATH.'/var/cache/'.$name.'.cache');
        else
            return false;
    }
    
    public static function cache_exists($name=null){
        if(!file_exists(ABSPATH.'/var/cache/'.$name.'.cache'))
            return false ;
        else
            return true;
    }
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

