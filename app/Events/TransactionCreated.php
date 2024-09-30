<?php

namespace App\Events;

use Thunk\Verbs\Event;

class TransactionCreated extends Event
{
    public function handle()
    {
        // If I ain't have the keys to success I would've picked the lock. - Lil Wayne
    }
}
