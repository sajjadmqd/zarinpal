<?php

use Illuminate\Support\Facades\Route;
use Sajjadmgd\Zarinpal\Http\Controllers\TransactionController;

Route::get('verify-transaction', [TransactionController::class, 'verify'])->name('transactions.verify');
