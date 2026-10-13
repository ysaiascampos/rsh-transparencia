<?php

namespace App\Http\Controllers;


class Utiles
{
    /**
     * Retorna el dv, de un run que viene sin puntos ni dv
     */
    public static function dv($r)
    {
        $s = 1;
        for ($m = 0; $r != 0; $r /= 10) {
            $s = ($s + $r % 10 * (9 - $m++ % 6)) % 11;
        }
        return chr($s ? $s + 47 : 75);
    }

    /**
     * Verifica si el dv de un run xxx.xxx-y corresponde realmente al dv correcto
     */
    public static function validaRUN($value)
    {
        $tmp = explode('-', $value);
        if (count($tmp) == 2) {
            return !strcmp(Utiles::dv(str_replace('.', '', $tmp[0])), strtoupper($tmp[1]));
        }
        return false;
    }

    public static function checkPasswordNumerico($pwd)
    {
        if (!preg_match('#[0-9]+#', $pwd)) {
            //$errors[] = 'La contraseña debe incluir por lo menos un número!';
            return false;
        }
        return true;
    }

    public static function scriptAutoVersion($file)
    {
        $filePath = public_path() . DIRECTORY_SEPARATOR . $file;
        $src = null;
        if (!file_exists($filePath)) {
            $src = $file;
        } else {
            $version = filemtime($filePath);
            $src = $file . '?v=' . $version;
        }
        echo '<script src="' . asset($src) . '" ></script>';
    }

    public static function styleAutoVersion($file)
    {
        $filePath = public_path() . DIRECTORY_SEPARATOR . $file;
        $src = null;
        if (!file_exists($filePath)) {
            $src = $file;
        } else {
            $version = filemtime($filePath);
            $src = $file . '?v=' . $version;
        }
        echo '<link rel="stylesheet" href="' . asset($src) . '" ></link>';
    }

    /**
     * Pone puntos a un número
     */
    public static function ponePuntosNumero($numero)
    {
        try {
            return number_format($numero, 0, ',', '.');
        } catch (Exception $ex) {
        }
    }

    /**
     * Pone puntos a un número
     */
    public static function poneComaNumero($numero)
    {
        try {
            return number_format($numero, 2, ',', '');
        } catch (Exception $ex) {
        }
    }

    /**
     * Remueve puntos, guion y dv
     */
    public static function removerPuntoGuionyDV($entrada)
    {
        $exp = explode('-', $entrada);
        return str_replace('.', '', $exp[0]);
    }

}
