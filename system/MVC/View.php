<?php

namespace MVC;

class View{

    public function renderView(string $view, $model = [])
{
    try {
        // Memvalidasi apakah file tampilan ada sebelum proses rendering
        $viewFilePath = VIEWS . "$view.php";
        if (!file_exists($viewFilePath)) {
            throw new Exception("File tampilan '$view.php' tidak ditemukan.");
        }

        // Menyalin model ke variabel data
        $data = $model;

        // Memulai buffering untuk tampilan
        ob_start();
        include $viewFilePath;
        $viewContent = ob_get_clean();

        // Memulai buffering untuk layout
        ob_start();
        include VIEWS . "layouts/main.php";
        $layoutContent = ob_get_clean();

        // Mengganti placeholder dengan konten tampilan
        return str_replace('{{content}}', $viewContent, $layoutContent);
    } catch (Exception $e) {
        // Tangani kesalahan, seperti log atau tampilkan pesan kesalahan
        return "Terjadi kesalahan: " . $e->getMessage();
    }
}

public function renderViewOnly(string $view, $model = [])
{
    try {
        // Memvalidasi apakah file tampilan ada sebelum proses rendering
        $viewFilePath = VIEWS . "$view.php";
        if (!file_exists($viewFilePath)) {
            throw new Exception("File tampilan '$view.php' tidak ditemukan.");
        }


    // Menyalin model ke variabel data
        $data = $model;

        // Memulai buffering untuk tampilan
        ob_start();
        include $viewFilePath;
        return ob_get_clean();
    } catch (Exception $e) {
        // Tangani kesalahan, seperti log atau tampilkan pesan kesalahan
        return "Terjadi kesalahan: " . $e->getMessage();
    }
}



}