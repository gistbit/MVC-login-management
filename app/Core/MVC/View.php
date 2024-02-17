<?php

namespace MA\PHPMVC\Core\MVC;

use Exception;

final class View
{
    public static function renderView(string $view, array $model = [])
    {
        try {
            $viewContent = self::loadViewContent($view, $model);
            $templateContent = self::loadViewTemplate($view, $model);
            return str_replace('{{content}}', $viewContent, $templateContent);

        } catch (Exception $e) {
            return self::handleException($e);
        }
    }

    public static function renderViewOnly(string $view, array $model = [])
    {
        try {
            return self::loadViewContent($view, $model);
        } catch (Exception $e) {
            return self::handleException($e);
        }
    }

    private static function loadViewContent(string $view, array $__data = [])
    {
        $viewFilePath = VIEWS .'/'. $view . '.php';
        self::checkViewFile($viewFilePath);
        extract($__data);
        ob_start();
        include $viewFilePath;
        return ob_get_clean();
    }

    private static function loadViewTemplate(string $view, array $__data = [])
    {
        $templateFilePath = VIEWS . "/templates/" . self::getTemplate($view) . '.php';
        self::checkViewFile($templateFilePath);
        extract($__data);
        ob_start();
        include $templateFilePath;
        return ob_get_clean();
    }

    private static function getTemplate(string $view)
    {
        $viewParts = explode('/', $view);
        return $viewParts[0];
    }

    private static function checkViewFile(string $viewFilePath)
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
