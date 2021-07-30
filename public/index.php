<?php

declare(strict_types=1);

require_once dirname(__DIR__).'/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;
use TBoileau\Oc\Php\Project5\Kernel;

$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__.'/.env');

$kernel = new Kernel($_ENV['APP_ENV']);
$kernel->run(Request::createFromGlobals());
