<?php

namespace MA\PHPMVC\MVC;

use Exception;

final class View
{
    public static function render(string $view, array $model = [], string $extends = '')
    {
        try {
            $content = self::loadView($view, $model);

            if (!empty($extends)) {
                $templateContent = self::loadView($extends, $model);
                return str_replace('{{content}}', $content, $templateContent);
            }

            return $content;

        } catch (Exception $e) {
            return self::handleException($e);
        }
    }

    private static function loadView(string $__VIEW, array $__DATA = []): string
    {
        $viewFilePath = VIEWS . '/' . $__VIEW . '.php';
        self::checkViewFile($viewFilePath);
        extract($__DATA);

        ob_start();
        include $viewFilePath;

        return ob_get_clean();
    }

    private static function checkViewFile(string $viewFilePath): void
    {
        if (!file_exists($viewFilePath)) {
            throw new Exception("File View '" . basename($viewFilePath) . "' tidak ditemukan di [$viewFilePath]");
        }
    }

    private static function handleException(Exception $e): string
    {
        return "Terjadi kesalahan: " . $e->getMessage();
    }
}
