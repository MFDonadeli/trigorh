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
}

