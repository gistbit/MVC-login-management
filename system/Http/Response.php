<?php

namespace Http;

class Response {
    private $headers = [];
    private $version = '11.09';
    private $content;

    public function setVersion(string $version) {
        $this->version = $version;
    }

    public function getVersion(): string {
        return $this->version;
    }

    public function setHeader(string $header) {
        $this->headers[] = $header;
    }

    public function getHeader() {
        return $this->headers;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function getContent() {
        return $this->content;
    }

    public function redirect($url) {
        if (empty($url)) {
            http_response_code(400); // Bad Request
            exit;
        }

        header('Location: ' . str_replace(['&amp;', "\n", "\r"], ['&', '', ''], $url), true, 302);
        exit();
    }

    public function render() {
        if ($this->content) {  
            foreach ($this->headers as $header) {
                header($header, true);
            }
            echo $this->content;
        }
    }
}
