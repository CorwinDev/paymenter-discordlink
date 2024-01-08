<?php

use Illuminate\Support\Facades\Route;

Route::get('/linkedroles', [CorwinDev\PaymenterDiscordLink\Http\Controllers\LinkedRoleController::class, 'index'])->name('linkedroles.index')->middleware(['auth']);
Route::get('/linkedroles/callback', [CorwinDev\PaymenterDiscordLink\Http\Controllers\LinkedRoleController::class, 'callback'])->name('linkedroles.callback')->middleware(['auth']);