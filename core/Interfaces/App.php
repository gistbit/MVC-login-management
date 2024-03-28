<?php

namespace MA\PHPMVC\Interfaces;
use MA\PHPMVC\Http\Request;
use MA\PHPMVC\Http\Response;

interface App{
    public function __construct(Request $request, Response $response);
    public function run(): SendResponse;
}