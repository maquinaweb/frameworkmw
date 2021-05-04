<?php

require_once(ABSPATH . '/vendor/PHPMailer/class.phpmailer.php');
require_once(ABSPATH . '/vendor/PHPMailer/class.smtp.php');

class emailhelper {
	
    
    //Regex para verificação de e-mail
    public static $validator_regex = '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD';

    //Configurações de email do sistema
    private static $default_emails_by_name = array(
        'sistema' => 'maquinaweb@publiquefacil.com.br',
        'formulario' => 'formulario@contatodoseusite.com.br'
    );

    private static $default_emails = array(
        'maquinaweb@publiquefacil.com.br' => array(
            'Email' => 'maquinaweb@publiquefacil.com.br',
            'Name' => 'Sistema Easypublish',
            'SMTP' => array(
                'Host' => 'smtp.publiquefacil.com.br',
                'Port' => 587,
                'Username' => 'maquinaweb@publiquefacil.com.br',
                'Password' => 'Rmvy90&5'
            )
        ),
        'formulario@contatodoseusite.com.br' => array(
            'Email' => 'formulario@contatodoseusite.com.br',
            'Name' => 'Formulário do seu site',
            'SMTP' => array(
                'Host' => 'smtpi.uni5.net',
                'Port' => 587,
                'Username' => 'formulario@contatodoseusite.com.br',
                'Password' => 'M@qForm0975'
            )
        )
    );

    //Mantem erros de envio
    public $errors = array();
    
    //Objeto de e-mail
    private $email;

    //Diz se há acesso aos e-mail estáticos definidos
    private $static_access;
    
    //Guarda os endereços para enviar o email
    private $address;
    
    //Guarda os endereços de 'responder para'
    private $reply_to;

    /**
     * Construtor
     * @param $string caminho da imagem a ser carregada
     * @return void
    */
    public function __construct( $email_name, $smtp = false, $static_access = false, $debug = false) {

        $this->static_access = $static_access;
        $this->email = new PHPMailer();

        $email = $this->getEmailConfig($email_name);

        if ($smtp) {
            if (isset($email['SMTP'])) {
                $this->email->IsSMTP();
                $this->email->SMTPAuth = true; //Define se haverá ou não autenticação no SMTP

                $this->email->SMTPDebug = ($debug) ? 1 : 0;

                $this->email->Host = $email['SMTP']['Host'];
                $this->email->Port = $email['SMTP']['Port']; //Indica a porta de conexão para a saída de e-mails. Utilize obrigatoriamente a porta 587.
                $this->email->Username = $email['SMTP']['Username']; //Informe o e-mail o completo
                $this->email->Password = $email['SMTP']['Password']; //Senha da caixa postal
            }
            else {
                throw new Exception("Unable to find requested SMTP configuration!");
            }
        }

        $this->email->CharSet = 'UTF-8';
        $this->email->isHTML(true);

        $this->email->From = $email['Email'];
        $this->email->FromName = $email['Name'];
        
        $this->address = array();
        $this->reply_to = array();
        
        return true;
    } 
    
    public function to_json() {
        $json = array();
        
        $json['Host'] = $this->email->Host;
        $json['Port'] = $this->email->Port; 
        $json['Username'] = $this->email->Username;
        
        $json['From'] = $this->email->From;
        $json['FromName'] = $this->email->FromName;
        $json['Subject'] = $this->email->Subject;
        $json['Message'] = $this->email->Body;
        
        if (count($this->reply_to) > 0) {
            $json['ReplyTo'] = $this->reply_to;
        }
        
        if (count($this->address) > 0) {
            $json['Address'] = $this->address;
        }
        
        return json_encode($json);
    }

    private function getEmailConfig($email_name) {
        if ($this->static_access) {
            if (isset(self::$default_emails_by_name[$email_name])) {
                return self::$default_emails[self::$default_emails_by_name[$email_name]];
            }
            if (isset(self::$default_emails[$email_name])) {
                return self::$default_emails[$email_name];
            }
        }
        
        if (preg_match(self::$validator_regex, $email_name)) {
            return array('email' => $email_name);
        }
        else {
            throw new Exception('Invalid e-mail format!');
        }

    }

    public function setSubject($subject) {
        $this->email->Subject = $subject;
        return $this->email->Subject;
    }

    public function setMessage($message) {
        $this->email->Body = $message;
        return $this->email->Body;
    }
    
    public function setFromName($from_name) {
        $this->email->FromName = $from_name;
        return $this->email->FromName;
    }

    public function addAddress($address, $name = null) {
        if (is_array($address)) {
            foreach ($address as $key => $value) {
                if ($name === null or !isset($name[$key])) {
                    if (!isset($this->address[$value])) {
                        //$this->email->addAddress($value);
                        $this->address[$value] = null;
                    }
                }
                else {
                    //$this->email->addAddress($value, $name[$key]);
                    $this->address[$value] = $name[$key];
                }
            }
        }
        else {
            if ($name === null) {
                if (!isset($this->address[$address])) {
                    $this->address[$address] = null;
                }
            }
            else {
                $this->address[$address] = $name;
            }
        }
    }
    
    public function addReplyTo($reply_to, $name = null) {
        if (is_array($reply_to)) {
            foreach ($reply_to as $key => $value) {
                if ($name === null or !isset($name[$key])) {
                    if (!isset($this->reply_to[$value])) {
                        $this->reply_to[$value] = null;
                    }
                }
                else {
                    $this->reply_to[$value] = $name[$key];
                }
            }
        }
        else {
            if ($name === null) {
                if (!isset($this->reply_to[$reply_to])) {
                    $this->reply_to[$reply_to] = null;
                }
            }
            else {
                $this->reply_to[$reply_to] = $name;
            }
        }
    }
    
    public function send() {
        if(count($this->address) == 0)
            throw new Exception("'Send' request without address!");
        
        foreach ($this->address as $key => $value) {
            if ($value !== null) {
                $this->email->addAddress($key, $value);
            }
            else {
                $this->email->addAddress($key);
            }
        }
        
        if(count($this->reply_to) > 0) {
            $this->email->ClearReplyTos();
            
            foreach ($this->reply_to as $key => $value) {
                if ($value !== null) {
                    $this->email->addReplyTo($key, $value);
                }
                else {
                    $this->email->addReplyTo($key);
                }
            }
        }
        
        $result = $this->email->Send();
        
        if (!$result) {
            $this->errors[] = $this->email->ErrorInfo;
        }
        
        return $result;
    }
	
}