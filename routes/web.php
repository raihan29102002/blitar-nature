<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Pages\Admin\Dashboard as AdminDashboard;
use App\Livewire\Pages\Admin\Wisata\Wisata as DataWisata;
use App\Livewire\Pages\Admin\Wisata\Form as WisataForm;
use App\Livewire\Pages\Admin\Wisata\Detail as WisataDetail;
use App\Livewire\Pages\Admin\Fasilitas as FasilitasWisata;
use App\Livewire\Pages\Admin\Pengunjung as PengunjungWisata;
use App\Livewire\Pages\Admin\RatingFeedback as Rating;
use App\Livewire\Pages\Admin\Akun as Akun;
use App\Livewire\Pages\Wisatawan\Dashboard as UserDashboard;
use App\Livewire\Pages\Wisatawan\Home;
use App\Livewire\Pages\Wisatawan\Profil;
use App\Livewire\Pages\Wisatawan\DetailWisata;
use App\Livewire\Pages\Wisatawan\Wisata as WisatawanWisata;

Route::get('/', UserDashboard::class)
    ->name('wisatawan.dashboard');

Route::get('/wisatawan/wisata', WisatawanWisata::class)
    ->name('wisata');
Route::get('/wisatawan/home', Home::class)
    ->name('home');
Route::get('/wisatawan/profil', Profil::class)
    ->middleware(['auth'])
    ->name('profil');
    Route::get('/wisata/{id}', DetailWisata::class)
    ->name('wisata.detail');


    
Route::get('/admin/dashboard', AdminDashboard::class)
    ->middleware(['auth', 'role:admin'])
    ->name('admin.dashboard');
    
    Route::prefix('admin/wisata')->middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/', DataWisata::class)->name('admin.wisata');
        Route::get('/create', WisataForm::class)->name('admin.wisata.create');
        Route::get('/{id}/edit', WisataForm::class)->whereNumber('id')->name('admin.wisata.edit');
        Route::get('/{id}/detail', WisataDetail::class)->whereNumber('id')->name('admin.wisata.detail');
    });
    
Route::get('/admin/fasilitas', FasilitasWisata::class)
    ->middleware(['auth', 'role:admin'])
    ->name('admin.fasilitas');
Route::get('/admin/pengunjung', PengunjungWisata::class)
    ->middleware(['auth', 'role:admin'])
    ->name('admin.pengunjung');
Route::get('/admin/rating', Rating::class)
    ->middleware(['auth', 'role:admin'])
    ->name('admin.rating');
Route::get('/admin/akun', Akun::class)
    ->middleware(['auth', 'role:admin'])
    ->name('admin.akun');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


require __DIR__.'/auth.php';

