<?php

namespace App\Services;

use Illuminate\Support\Str;

class UploadService
{
    const PRODUCTS = 'products';

    public static function salvarArquivo($arquivo, $pasta)
    {
        $nomeNovo = Str::uuid() . "." . $arquivo->getClientOriginalExtension();

        $arquivo->storeAs("public/$pasta", $nomeNovo);

        return "$pasta/$nomeNovo";
    }

    public static function getUrl($arquivo)
    {
        return url('storage/' . $arquivo);
    }
}