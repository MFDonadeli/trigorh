<?php
    function only_numbers($str)
    {
        return preg_replace('/[^0-9]/', '', (string) $str);
    }

    function get_vaga_code()
    {
        $timeparts = explode(" ",microtime());
        $currenttime = bcadd(($timeparts[0]*1000),bcmul($timeparts[1],1000));
	    return base_convert($currenttime,10, 36);
    }
?>