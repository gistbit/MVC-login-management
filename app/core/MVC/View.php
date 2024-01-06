<?php

namespace App\Core\MVC;

class View
{
    public function renderView(string $view, $model = [])
    {
        try {
            $viewContent = $this->renderViewContent($view, $model);
            $templateContent = $this->renderTemplateContent($viewContent);
            return $templateContent;
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function renderViewOnly(string $view, $model = [])
    {
        try {
            return $this->renderViewContent($view, $model);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    private function renderViewContent(string $view, $model = [])
    {
        $viewFilePath = VIEWS . $view . '.php';
        $this->checkViewFile($viewFilePath);

        $data = $model;
        ob_start();
        include $viewFilePath;
        return ob_get_clean();
    }

    private function renderTemplateContent(string $viewContent)
    {
        ob_start();
        include VIEWS . "templates/main.php";
        $templateContent = ob_get_clean();
        return str_replace('{{content}}', $viewContent, $templateContent);
    }

    private function checkViewFile(string $viewFilePath)
    {
        if (!file_exists($viewFilePath)) {
            throw new \Exception("File tampilan '" . basename($viewFilePath) . "' tidak ditemukan.");
        }
    }

    private function handleException(\Exception $e)
    {
        return "Terjadi kesalahan: " . $e->getMessage();
    }
}
