<?php

namespace App\Core\Http;

use App\Core\MVC\View;

class Response
{
    private array $headers = [];
    private $content;
    private int $statusCode = 0;

    public const STATUS_TEXTS = [
        // INFORMATIONAL CODES
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        // SUCCESS CODES
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-status',
        208 => 'Already Reported',
        // REDIRECTION CODES
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy', // Deprecated
        307 => 'Temporary Redirect',
        // CLIENT ERROR
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        // SERVER ERROR
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        511 => 'Network Authentication Required',
    ];

    public function getStatusText(): string
    {
        return (string)(self::STATUS_TEXTS[$this->statusCode] ?? 'unknown status');
    }

    public function setHeader(string $header): void
    {
        $this->headers[] = $header;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setContent($content): void
    {
        $this->content = $content;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getContent()
    {
        return $this->content;
    }

    public static function redirect(string $url): void
    {
        if (empty($url)) {
            throw new \InvalidArgumentException('Invalid URL provided for redirect.');
        }

        header('Location: ' . str_replace(['&amp;', "\n", "\r"], ['&', '', ''], $url), true);
        exit;
    }

    public function setStatus(int $code): void
    {
        if (!$this->isInvalid($code)) {
            $this->statusCode = $code;
        }
    }

    public function render(): void
    {
        if ($this->content) {
            http_response_code($this->statusCode);
            if (!headers_sent()) {
                foreach ($this->headers as $header) {
                    header($header, true);
                }
            }
            echo $this->content;
        }
    }

    // Fungsi-fungsi tambahan:

    public function setJson(array $data): void
    {
        $this->setHeader('Content-Type: application/json; charset=UTF-8');
        $this->setContent(json_encode($data));
    }

    public function setHtml(string $html): void
    {
        $this->setHeader('Content-Type: text/html; charset=UTF-8');
        $this->setContent($html);
    }

    public function setPlainText(string $text): void
    {
        $this->setHeader('Content-Type: text/plain');
        $this->setContent($text);
    }


    public function setStatusCodeText(string $statusText): void
    {
        $statusCode = array_search($statusText, self::STATUS_TEXTS, true);
        if ($statusCode !== false) {
            $this->setStatus($statusCode);
        }
    }

    public function setContentFromFile(string $filePath): void
    {
        if (file_exists($filePath)) {
            $this->setContent(file_get_contents($filePath));
        }
    }

    public function setCookie(string $name, string $value, int $expire = 0, string $path = '/', string $domain = '', bool $secure = false, bool $httponly = false): void
    {
        $cookieString = sprintf(
            '%s=%s; expires=%s; path=%s; domain=%s; secure=%s; httponly=%s',
            $name,
            urlencode($value),
            ($expire > 0) ? gmdate('D, d M Y H:i:s T', $expire) : 0,
            $path,
            $domain,
            $secure ? 'true' : 'false',
            $httponly ? 'true' : 'false'
        );

        $this->setHeader("Set-Cookie: $cookieString");
    }

    public function setDownload(string $filePath, string $fileName): void
    {
        $this->setHeader('Content-Type: application/octet-stream');
        $this->setHeader("Content-Disposition: attachment; filename=\"$fileName\"");
        $this->setContentFromFile($filePath);
    }

    public function setNotFound(): void
    {
        $this->setStatus(404);
        $this->setContent(View::renderViewOnly('404', [
            'title' => 'Not Found',
            'status' => [
                'code' => '404',
                'text' => 'Not Found'
            ]
        ]));
    }

    public function setNoCache(): void
    {
        $this->setHeader('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        $this->setHeader('Pragma: no-cache');
        $this->setHeader('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
    }

    public function setCorsHeaders(array $allowedOrigins = [], array $allowedMethods = ['GET', 'POST'], array $allowedHeaders = []): void
    {
        if (!empty($allowedOrigins)) {
            $this->setHeader('Access-Control-Allow-Origin: ' . implode(', ', $allowedOrigins));
        }
        $this->setHeader('Access-Control-Allow-Methods: ' . implode(', ', $allowedMethods));
        if (!empty($allowedHeaders)) {
            $this->setHeader('Access-Control-Allow-Headers: ' . implode(', ', $allowedHeaders));
        }
    }

    public function setCacheHeaders(int $maxAgeInSeconds = 3600, string $cacheControl = 'public'): void
    {
        $this->setHeader('Cache-Control: ' . $cacheControl . ', max-age=' . $maxAgeInSeconds);
        $this->setHeader('Expires: ' . gmdate('D, d M Y H:i:s T', time() + $maxAgeInSeconds));
    }

    private function isInvalid(int $statusCode): bool
    {
        return $statusCode < 100 || $statusCode >= 600;
    }
}