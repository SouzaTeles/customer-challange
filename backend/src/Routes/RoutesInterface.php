<?php

declare(strict_types=1);

namespace App\Routes;

use App\Http\Request;

interface RoutesInterface
{
    public function handle(Request $request): ?array;
}