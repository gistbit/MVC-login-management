<?php

namespace App\Core\MVC;

class View
{
    public function renderView(string $view, $model = [])
    {
        try {
            $viewContent = $this->loadViewContent($view, $model);
            $templateContent = $this->loadViewTemplate($view, $model);
            return str_replace('{{content}}', $viewContent, $templateContent);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function renderViewOnly(string $view, $model = [])
    {
        try {
            return $this->loadViewContent($view, $model);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    private function loadViewContent(string $view, $data = [])
    {
        $viewFilePath = VIEWS . $view . '.php';
        $this->checkViewFile($viewFilePath);

        ob_start();
        include $viewFilePath;
        return ob_get_clean();
    }

    private function loadViewTemplate(string $view , $data = [])
    {
        $templateFilePath = VIEWS . "templates/{$this->getTemplate($view)}.php";
        $this->checkViewFile($templateFilePath);

        ob_start();
        include $templateFilePath;
        return ob_get_clean();
    }

    private function getTemplate(string $view)
    {
        $viewParts = explode('/', $view);
        return $viewParts[0];
    }


    private function checkViewFile(string $viewFilePath)
    {
        if (!file_exists($viewFilePath)) {
            throw new \Exception("File View '" . basename($viewFilePath) . "' tidak ditemukan di [$viewFilePath]");
        }
    }

    private function handleException(\Exception $e)
    {
        return "Terjadi kesalahan: " . $e->getMessage();
    }
}
