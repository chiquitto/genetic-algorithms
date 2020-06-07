<?php


namespace Chiquitto\FindWord;


class Util
{

    public static function letras()
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ ';
        // $chars .= 'abcdefghijklmnopqrstuvwxyz';
        return $chars;
    }

    public static function randomLetra()
    {
        $chars = static::letras();
        $ultPos = strlen($chars) - 1;
        return $chars[mt_rand(0, $ultPos)];
    }

    public static function randomPalavra($tamanho)
    {
        $chars = static::letras();
        $ultPos = strlen($chars) - 1;
        $r = '';
        for ($i = 0; $i < $tamanho; $i++) {
            $r .= $chars[mt_rand(0, $ultPos)];
        }
        return $r;
    }
}