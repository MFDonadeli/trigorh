<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');
class MY_Form_validation extends CI_Form_validation 
{
    function __construct($config = array())
    {
        parent::__construct($config);
    }

    //Validação de senha forte
    function check_senha_forte($str)
    {
        //veio de http://www.guj.com.br/t/expressao-regular-para-senhas-fortes/140690
        $regex = "/^(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z])(?=.*[\W])(?=\\S+$).{6,}$/";

        if(preg_match($regex, $str))
        {
            return true;
        }

        return false;
    }

    function cpf_check($str)
    {
        $cpf = preg_replace('/[^0-9]/', '', (string) $str);

        // Valida tamanho
        if (strlen($cpf) != 11)
            return false;

        // Calcula e confere primeiro dígito verificador
        for ($i = 0, $j = 10, $soma = 0; $i < 9; $i++, $j--)
            $soma += $cpf{$i} * $j;

        $resto = $soma % 11;

        if ($cpf{9} != ($resto < 2 ? 0 : 11 - $resto))
            return false;

        // Calcula e confere segundo dígito verificador
        for ($i = 0, $j = 11, $soma = 0; $i < 10; $i++, $j--)
            $soma += $cpf{$i} * $j;

        $resto = $soma % 11;

        return $cpf{10} == ($resto < 2 ? 0 : 11 - $resto);
    }

    function cnpj_check($str)
    {
        $cnpj = preg_replace('/[^0-9]/', '', (string) $str);

        // Valida tamanho
        if (strlen($cnpj) != 14)
            return false;

        $arr_digitos1 = array(5,4,3,2,9,8,7,6,5,4,3,2);
        // Calcula e confere primeiro dígito verificador
        for ($i = 0, $soma = 0; $i < 12; $i++)
            $soma += $cnpj{$i} * $arr_digitos1{$i};

        $resto = $soma % 11;

        if ($cnpj{12} != ($resto < 3 ? 0 : 11 - $resto))
            return false;

        $arr_digitos2 = array(6,5,4,3,2,9,8,7,6,5,4,3,2);
        // Calcula e confere segundo dígito verificador
        for ($i = 0, $soma = 0; $i < 13; $i++)
            $soma += $cnpj{$i} * $arr_digitos2{$i};

        $resto = $soma % 11;

        return $cnpj{13} == ($resto < 3 ? 0 : 11 - $resto);
    }

    //http://codigofonte.uol.com.br/codigos/validacao-completa-de-datas-em-javascript
    function data_check($str)
    {
        $regex = '/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/[12][0-9]{3}/'; 

        $data = explode('/', $str);

        if(!preg_match($regex, $str))
        {
            return false;
        }   
        else if((($data[1]==4)||($data[1]==6)||($data[1]==9)||($data[1]==11))&&($data[0]>30))
        {
            return false;
        }
        else if ($data[1]==2)
        {
            if (($data[0]>28)&&(($data[2]%4)!=0))
                return false;
            if (($data[0]>29)&&(($data[2]%4)==0))
                return false;
        }

        return true;
    }

    function data_check_ma($str)
    {
        $regex = '/^(0[1-9]|1[012])\/[12][0-9]{3}/'; 

        if(preg_match($regex, $str))
        {
            return true;
        }

        return false;
    }

	//Compara valor com outro campo do formulario e retorna true se o valor for maior ou igual.
	public function maiorquecampo($str, $field)
	{
        if(!(isset($this->_field_data[$field], $this->_field_data[$field]['postdata'])) || 
            !(is_numeric($str) || !is_numeric($this->_field_data[$field]['postdata'])) )
            return FALSE;
        
        return ($str >= $this->_field_data[$field]['postdata']);
	}

    //Compara valor com outro campo do formulario e retorna true se o valor for menor ou igual.
	public function menorquecampo($str, $field)
	{
        if(!(isset($this->_field_data[$field], $this->_field_data[$field]['postdata'])) || 
            !(is_numeric($str) || !is_numeric($this->_field_data[$field]['postdata'])) )
            return FALSE;
		
        return ($str <= $this->_field_data[$field]['postdata']);
	}
}

