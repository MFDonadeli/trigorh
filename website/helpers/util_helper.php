<?php
    function only_numbers($str)
    {
        return preg_replace('/[^0-9]/', '', (string) $str);
    }
?>