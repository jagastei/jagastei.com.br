<?php

declare(strict_types=1);

arch()->preset()->laravel();

arch()->expect('dd')->not->toBeUsed();