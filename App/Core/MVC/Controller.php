<?php

namespace App\Core\MVC;

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Domain\User;
use App\Core\Database\Database;
use App\Repository\{SessionRepository, UserRepository};
use App\Service\SessionService;

abstract class Controller
{
    protected Request $request;
    protected Response $response;
    protected View $view;

    protected ?User $user;

    public function __construct()
    {
        $this->view = new View();
        $this->request = $GLOBALS['request'];
        $this->response = $GLOBALS['response'];
        $this->user = $this->userCurrent();
    }

    private function userCurrent(): ?User
    {
        $connection = Database::getConnection();
        $sessionRepository = new SessionRepository($connection);
        $userRepository = new UserRepository($connection);
        $sessionService = new SessionService($sessionRepository, $userRepository);
        return $sessionService->current();
    }

    public function model(string $modelName)
    {
        $modelFilePath = MODELS . $modelName . ".php";

        $this->checkModelFile($modelFilePath);

        $modelClass = "\App\Models\\" . $modelName;

        $this->checkModelClass($modelClass);

        return new $modelClass;
    }

    private function checkModelFile(string $modelFilePath)
    {
        if (!file_exists($modelFilePath)) {
            throw new \Exception(sprintf('{ %s } this model file not found', $modelFilePath));
        }
    }

    private function checkModelClass(string $modelClass)
    {
        if (!class_exists($modelClass)) {
            throw new \Exception(sprintf('{ %s } this model class not found', $modelClass));
        }
    }
}
