<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
   return Inertia::render('Deputados', [
       'title' => 'Deputados'
   ]);
})->name('home');

Route::get('/deputados', function () {
   return Inertia::render('Deputados', [
       'title' => 'Deputados Dashboard'
   ]);
})->name('deputados_dashboard');

Route::get('/partidos', function () {
   return Inertia::render('Partidos', [
       'title' => 'Partidos Dashboard'
   ]);
})->name('partidos_dashboard');

Route::get('Despesas', function () {
    return Inertia::render('Despesas', [
        'title' => 'Despesas Dashboard'
    ]);
})->name('despesas_dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
