<?php

namespace Medilies\Xssless\Dompurify;

use Illuminate\Support\Facades\Http as FacadesHttp;

class Http
{
    public function clean(string $html): string
    {
        $host = '127.0.0.1';
        $port = 8000;
        $url = "http://{$host}:{$port}";

        $response = FacadesHttp::post($url, ['html' => $html]);

        return $response->body();
    }
}
