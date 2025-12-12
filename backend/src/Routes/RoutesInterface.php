<?php

declare(strict_types=1);

namespace App\Routes;

use App\Http\Request;
use App\Http\Response;

interface RoutesInterface
{
    public function handle(Request $request): ?Response;
}