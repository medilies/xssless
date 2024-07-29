<?php

namespace Medilies\Xssless\Dompurify;

class Http
{
    public function clean(string $html): string
    {
        $host = '127.0.0.1';
        $port = 8000;
        $url = "http://{$host}:{$port}";

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $html);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: text/html',
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            echo 'cURL Error: ' . curl_error($ch);
        }

        return $response;

        curl_close($ch);
    }
}
