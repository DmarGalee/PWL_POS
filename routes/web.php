<?php
use App\Http\Controllers\BarangController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index']);


Route::group(['prefix' => 'user'], function () {
    Route::get('/', [UserController::class, 'index'])->name('user.index'); // Menampilkan halaman utama user
    Route::post('/list', [UserController::class, 'list'])->name('user.list'); // Mengambil data user (DataTables)
    // Tambah User
    Route::get('/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/', [UserController::class, 'store'])->name('user.store');
    // Tambah User (AJAX)
    Route::get('/create_ajax', [UserController::class, 'create_ajax'])->name('user.create_ajax');
    Route::post('/ajax', [UserController::class, 'store_ajax'])->name('user.store_ajax');
    // Detail & Edit User
    Route::get('/{id}', [UserController::class, 'show'])->name('user.show');
    Route::get('/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/{id}', [UserController::class, 'update'])->name('user.update');
    // Edit User (AJAX)
    Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax'])->name('user.edit_ajax');
    Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax'])->name('user.update_ajax');
    // Hapus User (AJAX)
    Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax'])->name('user.confirm_ajax'); // Tampilkan konfirmasi hapus
    Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax'])->name('user.delete_ajax'); // Hapus via AJAX
    // Hapus User (Non-AJAX)
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('user.destroy');
});

Route::group(['prefix' => 'level'], function () {
    Route::get('/', [LevelController::class, 'index'])->name('level.index'); // Menampilkan halaman utama level
    Route::post('/list', [LevelController::class, 'list'])->name('level.list'); // Mengambil data level (DataTables)
    // Tambah Level
    Route::get('/create', [LevelController::class, 'create'])->name('level.create');
    Route::post('/', [LevelController::class, 'store'])->name('level.store');
    // Tambah Level (AJAX)
    Route::get('/create_ajax', [LevelController::class, 'create_ajax'])->name('level.create_ajax');
    Route::post('/ajax', [LevelController::class, 'store_ajax'])->name('level.store_ajax');
    // Detail & Edit Level
    Route::get('/{id}', [LevelController::class, 'show'])->name('level.show');
    Route::get('/{id}/edit', [LevelController::class, 'edit'])->name('level.edit');
    Route::put('/{id}', [LevelController::class, 'update'])->name('level.update');
    // Edit Level (AJAX)
    Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax'])->name('level.edit_ajax');
    Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax'])->name('level.update_ajax');
    // Hapus Level (AJAX)
    Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax'])->name('level.confirm_ajax'); // Tampilkan konfirmasi hapus
    Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax'])->name('level.delete_ajax'); // Hapus via AJAX
    // Hapus Level (Non-AJAX)
    Route::delete('/{id}', [LevelController::class, 'destroy'])->name('level.destroy');
});

Route::group(['prefix' => 'kategori'], function () {
    Route::get('/', [KategoriController::class, 'index'])->name('kategori.index'); // Menampilkan halaman utama kategori
    Route::post('/list', [KategoriController::class, 'list'])->name('kategori.list'); // Mengambil data kategori (DataTables)
    // Tambah Kategori
    Route::get('/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/', [KategoriController::class, 'store'])->name('kategori.store');
    // Tambah Kategori (AJAX)
    Route::get('/create_ajax', [KategoriController::class, 'create_ajax'])->name('kategori.create_ajax');
    Route::post('/ajax', [KategoriController::class, 'store_ajax'])->name('kategori.store_ajax');
    // Detail & Edit Kategori
    Route::get('/{id}', [KategoriController::class, 'show'])->name('kategori.show');
    Route::get('/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/{id}', [KategoriController::class, 'update'])->name('kategori.update');
    // Edit Kategori (AJAX)
    Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax'])->name('kategori.edit_ajax');
    Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax'])->name('kategori.update_ajax');
    // Hapus Kategori (AJAX)
    Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax'])->name('kategori.confirm_ajax'); // Tampilkan konfirmasi hapus
    Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax'])->name('kategori.delete_ajax'); // Hapus via AJAX
    // Hapus Kategori (Non-AJAX)
    Route::delete('/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
});


Route::group(['prefix' => 'supplier'], function () {
    Route::get('/', [SupplierController::class, 'index'])->name('supplier.index'); // Menampilkan halaman utama supplier
    Route::post('/list', [SupplierController::class, 'list'])->name('supplier.list'); // Mengambil data supplier (DataTables)
    // Tambah Supplier
    Route::get('/create', [SupplierController::class, 'create'])->name('supplier.create');
    Route::post('/', [SupplierController::class, 'store'])->name('supplier.store');
    // Tambah Supplier (AJAX)
    Route::get('/create_ajax', [SupplierController::class, 'create_ajax'])->name('supplier.create_ajax');
    Route::post('/ajax', [SupplierController::class, 'store_ajax'])->name('supplier.store_ajax');
    // Detail & Edit Supplier
    Route::get('/{id}', [SupplierController::class, 'show'])->name('supplier.show');
    Route::get('/{id}/edit', [SupplierController::class, 'edit'])->name('supplier.edit');
    Route::put('/{id}', [SupplierController::class, 'update'])->name('supplier.update');
    // Edit Supplier (AJAX)
    Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax'])->name('supplier.edit_ajax');
    Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax'])->name('supplier.update_ajax');
    // Hapus Supplier (AJAX)
    Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax'])->name('supplier.confirm_ajax'); // Tampilkan konfirmasi hapus
    Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax'])->name('supplier.delete_ajax'); // Hapus via AJAX
    // Hapus Supplier (Non-AJAX)
    Route::delete('/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');
});

Route::group(['prefix' => 'barang'], function () {
    Route::get('/', [BarangController::class, 'index'])->name('barang.index'); // Menampilkan halaman utama barang
    Route::post('/list', [BarangController::class, 'list'])->name('barang.list'); // Mengambil data barang (DataTables)
    // Tambah Barang
    Route::get('/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/', [BarangController::class, 'store'])->name('barang.store');
    // Tambah Barang (AJAX)
    Route::get('/create_ajax', [BarangController::class, 'create_ajax'])->name('barang.create_ajax');
    Route::post('/ajax', [BarangController::class, 'store_ajax'])->name('barang.store_ajax');
    // Detail & Edit Barang
    Route::get('/{id}', [BarangController::class, 'show'])->name('barang.show');
    Route::get('/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/{id}', [BarangController::class, 'update'])->name('barang.update');
    // Edit Barang (AJAX)
    Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax'])->name('barang.edit_ajax');
    Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax'])->name('barang.update_ajax');
    // Hapus Barang (AJAX)
    Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax'])->name('barang.confirm_ajax'); // Tampilkan konfirmasi hapus
    Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax'])->name('barang.delete_ajax'); // Hapus via AJAX
    // Hapus Barang (Non-AJAX)
    Route::delete('/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');
});


// route::get('/level',action: [LevelController::class, 'index']);
// route::get('/kategori',action: [KategoriController::class, 'index']);
// route::get('/user',action: [UserController::class, 'index']);
// Route::get('/user/tambah', [UserController::class, 'tambah']);
// Route::post('/user/tambah_simpan', [UserController::class, 'tambah_simpan']);
// Route::get('/user/ubah/{id}', [UserController::class, 'ubah']);
// Route::put('/user/ubah_simpan/{id}', [UserController::class, 'ubah_simpan'])->name('user.ubah_simpan');
// Route::get('/user/hapus/{id}', [UserController::class, 'hapus'])->name('user.hapus');
