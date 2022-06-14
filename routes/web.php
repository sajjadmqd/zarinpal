<?php

use Illuminate\Support\Facades\Route;

Route::get('verify-transaction', [TransactionController::class, 'verify'])->name('transactions.verify');
