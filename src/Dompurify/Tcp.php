<?php

namespace Medilies\Xssless\Dompurify;

use Exception;

class Tcp
{
    public function clean(string $html): string
    {
        $host = '127.0.0.1';
        $port = 63000;

        $socket = fsockopen($host, $port, $errno, $errstr, 30);

        if (!$socket) {
            throw new Exception("Could not connect to Node.js server: $errstr ($errno)");
        }

        // Prepend the message length using 32 bits
        $messageLength = strlen($html);
        $lengthPrefix = pack('N', $messageLength);
        $message = $lengthPrefix . $html;

        fwrite($socket, $message);

        $output = '';
        while (!feof($socket)) {
            $output .= fgets($socket, 128);
        }

        fclose($socket);

        if (trim($output) === 'No input provided') {
            throw new Exception('No input provided');
        }

        return trim($output);
    }
}
