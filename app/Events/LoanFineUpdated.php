<?php

namespace App\Events;

use App\Models\Peminjaman;

class LoanFineUpdated
{
    public $loan;
    public $oldFine;
    public $newFine;

    public function __construct(Peminjaman $loan, $oldFine, $newFine)
    {
        $this->loan = $loan;
        $this->oldFine = $oldFine;
        $this->newFine = $newFine;
    }
} 