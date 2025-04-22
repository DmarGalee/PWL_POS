<?php
use App\Http\Controllers\BarangController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'postRegister'])->name('register.post');

Route::pattern('id', '[0-9]+'); // Parameter ID harus angka

// Login Routes
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);

// Logout harus menggunakan POST, bukan GET
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// Route::group(['prefix' => 'user'], function () {
//     Route::get('/', [UserController::class, 'index'])->name('user.index'); // Menampilkan halaman utama user
//     Route::post('/list', [UserController::class, 'list'])->name('user.list'); // Mengambil data user (DataTables)
//     // Tambah User
//     Route::get('/create', [UserController::class, 'create'])->name('user.create');
//     Route::post('/', [UserController::class, 'store'])->name('user.store');
//     // Tambah User (AJAX)
//     Route::get('/create_ajax', [UserController::class, 'create_ajax'])->name('user.create_ajax');
//     Route::post('/ajax', [UserController::class, 'store_ajax'])->name('user.store_ajax');
//     // Detail & Edit User
//     Route::get('/{id}', [UserController::class, 'show'])->name('user.show');
//     Route::get('/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
//     Route::put('/{id}', [UserController::class, 'update'])->name('user.update');
//     // Edit User (AJAX)
//     Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax'])->name('user.edit_ajax');
//     Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax'])->name('user.update_ajax');
//     // Hapus User (AJAX)
//     Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax'])->name('user.confirm_ajax'); // Tampilkan konfirmasi hapus
//     Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax'])->name('user.delete_ajax'); // Hapus via AJAX
//     // Hapus User (Non-AJAX)
//     Route::delete('/{id}', [UserController::class, 'destroy'])->name('user.destroy');
// });

// Routes yang membutuhkan autentikasi
Route::middleware(['auth'])->group(function () {
    // Artinya semua route di dalam group ini harus login dulu
    Route::get('/', [WelcomeController::class, 'index']);

    Route::middleware(['authorize:ADM'])->group(function () {
        // Artinya semua route di dalam group ini harus punya role ADM (Administrator)

        Route::get('/user', [UserController::class, 'index']);
        Route::post('/user/list', [UserController::class, 'list']); // Untuk list JSON datatables
        Route::get('/user/create_ajax', [UserController::class, 'create_ajax']); // Ajax form create
        Route::post('/user/ajax', [UserController::class, 'store_ajax']); // Ajax store
        Route::get('/user/{id}/edit_ajax', [UserController::class, 'edit_ajax']); // Ajax form edit
        Route::put('/user/{id}/update_ajax', [UserController::class, 'update_ajax']); // Ajax update
        Route::get('/user/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); // Ajax form confirm
        Route::delete('/user/{id}/delete_ajax', [UserController::class, 'delete_ajax']); // Ajax delete
        Route::get('/user/import', [UserController::class, 'import']);
        Route::post('/user/import_ajax', [UserController::class, 'import_ajax']);
        Route::get('/user/export_excel', [UserController::class, 'export_excel']); // export excel
        Route::get('/user/export_pdf', [UserController::class, 'export_pdf']); // export pfd
    });

    //LevelController
    Route::middleware(['authorize:ADM'])->group(function () {
        // Artinya semua route di dalam group ini harus punya role ADM (Administrator)

        Route::get('/level', [LevelController::class, 'index']);
        Route::post('/level/list', [LevelController::class, 'list']); // Untuk list JSON datatables
        Route::get('/level/create_ajax', [LevelController::class, 'create_ajax']); // Ajax form create
        Route::post('/level/ajax', [LevelController::class, 'store_ajax']); // Ajax store
        Route::get('/level/{id}/edit_ajax', [LevelController::class, 'edit_ajax']); // Ajax form edit
        Route::put('/level/{id}/update_ajax', [LevelController::class, 'update_ajax']); // Ajax update
        Route::get('/level/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']); // Ajax form confirm
        Route::delete('/level/{id}/delete_ajax', [LevelController::class, 'delete_ajax']); // Ajax delete
        Route::get('/level/import', [LevelController::class, 'import']);
        Route::post('/level/import_ajax', [LevelController::class, 'import_ajax']);
        Route::get('/level/export_excel', [LevelController::class, 'export_excel']); // export excel
        Route::get('/level/export_pdf', [LevelController::class, 'export_pdf']); // export pfd
    });
    //BarangController
    Route::middleware(['authorize:ADM,MNG'])->group(function () {
        // Artinya semua route di dalam group ini harus punya role ADM (Administrator) dan MNG (Manager)
    
        Route::get('/barang', [BarangController::class, 'index']);
        Route::post('/barang/list', [BarangController::class, 'list']);
        Route::get('/barang/create_ajax', [BarangController::class, 'create_ajax']); // Ajax form create
        Route::post('/barang/ajax', [BarangController::class, 'store_ajax']); // Ajax store
        Route::get('/barang/{id}/edit_ajax', [BarangController::class, 'edit_ajax']); // Ajax form edit
        Route::put('/barang/{id}/update_ajax', [BarangController::class, 'update_ajax']); // Ajax update
        Route::get('/barang/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']); // Ajax form confirm
        Route::delete('/barang/{id}/delete_ajax', [BarangController::class, 'delete_ajax']); // Ajax delete
        Route::get('/barang/import', [BarangController::class, 'import']); // ajax form upload excel
        Route::post('/barang/import_ajax', [BarangController::class,'import_ajax']); // ajax import excel
        Route::get('/barang/export_excel', [BarangController::class, 'export_excel']); // export excel
        Route::get('/barang/export_pdf', [BarangController::class, 'export_pdf']); // export pfd
    });
    //SupplierController
    Route::middleware(['authorize:ADM,MNG,STF'])->group(function () {
        // Artinya semua route di dalam group ini harus punya role ADM (Administrator) , MNG (Manager) , dan STF (Staff)
    
        Route::get('/supplier', [SupplierController::class, 'index']);
        Route::post('/supplier/list', [SupplierController::class, 'list']);
        Route::get('/supplier/create_ajax', [SupplierController::class, 'create_ajax']); // Ajax form create
        Route::post('/supplier/ajax', [SupplierController::class, 'store_ajax']); // Ajax store
        Route::get('/supplier/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']); // Ajax form edit
        Route::put('/supplier/{id}/update_ajax', [SupplierController::class, 'update_ajax']); // Ajax update
        Route::get('/supplier/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']); // Ajax form confirm
        Route::delete('/supplier/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']); // Ajax delete
        Route::get('/supplier/import', [SupplierController::class, 'import']);
        Route::post('/supplier/import_ajax', [SupplierController::class, 'import_ajax']);
        Route::get('/supplier/export_excel', [SupplierController::class, 'export_excel']); // export excel
        Route::get('/supplier/export_pdf', [SupplierController::class, 'export_pdf']); // export pfd
    });

    //KategoriController
    Route::middleware(['authorize:ADM,MNG,STF'])->group(function () {
        // Artinya semua route di dalam group ini harus punya role ADM (Administrator) , MNG (Manager) , dan STF (Staff)
    
        Route::get('/kategori', [KategoriController::class, 'index']);
        Route::post('/kategori/list', [KategoriController::class, 'list']);
        Route::get('/kategori/create_ajax', [KategoriController::class, 'create_ajax']); // Ajax form create
        Route::post('/kategori/ajax', [KategoriController::class, 'store_ajax']); // Ajax store
        Route::get('/kategori/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']); // Ajax form edit
        Route::put('/kategori/{id}/update_ajax', [KategoriController::class, 'update_ajax']); // Ajax update
        Route::get('/kategori/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']); // Ajax form confirm
        Route::delete('/kategori/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']); // Ajax delete
        Route::get('/kategori/import', [KategoriController::class, 'import']);
        Route::post('/kategori/import_ajax', [KategoriController::class, 'import_ajax']);
        Route::get('/kategori/export_excel', [KategoriController::class, 'export_excel']); // export excel
        Route::get('/kategori/export_pdf', [KategoriController::class, 'export_pdf']); // export pfd
    });

    //StokController
    Route::middleware(['authorize:ADM,MNG,STF'])->group(function () {
        // Artinya semua route di dalam group ini harus punya role ADM (Administrator) , MNG (Manager) , dan STF (Staff)
    
        Route::get('/stok', [StokController::class, 'index']);
        Route::post('/stok/list', [StokController::class, 'list']);
        Route::get('/stok/create_ajax', [StokController::class, 'create_ajax']); // Ajax form create
        Route::post('/stok/ajax', [StokController::class, 'store_ajax']); // Ajax store
        Route::get('/stok/{id}/edit_ajax', [StokController::class, 'edit_ajax']); // Ajax form edit
        Route::put('/stok/{id}/update_ajax', [StokController::class, 'update_ajax']); // Ajax update
        Route::get('/stok/{id}/delete_ajax', [StokController::class, 'confirm_ajax']); // Ajax form confirm
        Route::delete('/stok/{id}/delete_ajax', [StokController::class, 'delete_ajax']); // Ajax delete
        Route::get('/stok/import', [StokController::class, 'import']);
        Route::post('/stok/import_ajax', [StokController::class, 'import_ajax']);
        Route::get('/stok/export_excel', [StokController::class, 'export_excel']); // export excel
        Route::get('/stok/export_pdf', [StokController::class, 'export_pdf']); // export pfd
    });

    //PenjualanController
    Route::middleware(['authorize:ADM,MNG,STF'])->group(function () {
        // Artinya semua route di dalam group ini harus punya role ADM (Administrator) , MNG (Manager) , dan STF (Staff)
    
        Route::get('/penjualan', [PenjualanController::class, 'index']);
        Route::post('/penjualan/list', [PenjualanController::class, 'list']);
        Route::get('/penjualan/create_ajax', [PenjualanController::class, 'create_ajax']); // Ajax form create
        Route::post('/penjualan/ajax', [PenjualanController::class, 'store_ajax']); // Ajax store
        Route::get('/penjualan/{id}/edit_ajax', [PenjualanController::class, 'edit_ajax']); // Ajax form edit
        Route::put('/penjualan/{id}/update_ajax', [PenjualanController::class, 'update_ajax']); // Ajax update
        Route::get('/penjualan/{id}/delete_ajax', [PenjualanController::class, 'confirm_ajax']); // Ajax form confirm
        Route::delete('/penjualan/{id}/delete_ajax', [penjualanController::class, 'delete_ajax']); // Ajax delete
        Route::get('/penjualan/import', [penjualanController::class, 'import']);
        Route::post('/penjualan/import_ajax', [penjualanController::class, 'import_ajax']);
        Route::get('/penjualan/export_excel', [penjualanController::class, 'export_excel']); // export excel
        Route::get('/penjualan/export_pdf', [penjualanController::class, 'export_pdf']); // export pfd
    });


});

// Route::group(['prefix' => 'level'], function () {
//     Route::get('/', [LevelController::class, 'index'])->name('level.index'); // Menampilkan halaman utama level
//     Route::post('/list', [LevelController::class, 'list'])->name('level.list'); // Mengambil data level (DataTables)
//     // Tambah Level
//     Route::get('/create', [LevelController::class, 'create'])->name('level.create');
//     Route::post('/', [LevelController::class, 'store'])->name('level.store');
//     // Tambah Level (AJAX)
//     Route::get('/create_ajax', [LevelController::class, 'create_ajax'])->name('level.create_ajax');
//     Route::post('/ajax', [LevelController::class, 'store_ajax'])->name('level.store_ajax');
//     // Detail & Edit Level
//     Route::get('/{id}', [LevelController::class, 'show'])->name('level.show');
//     Route::get('/{id}/edit', [LevelController::class, 'edit'])->name('level.edit');
//     Route::put('/{id}', [LevelController::class, 'update'])->name('level.update');
//     // Edit Level (AJAX)
//     Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax'])->name('level.edit_ajax');
//     Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax'])->name('level.update_ajax');
//     // Hapus Level (AJAX)
//     Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax'])->name('level.confirm_ajax'); // Tampilkan konfirmasi hapus
//     Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax'])->name('level.delete_ajax'); // Hapus via AJAX
//     // Hapus Level (Non-AJAX)
//     Route::delete('/{id}', [LevelController::class, 'destroy'])->name('level.destroy');
// });

// Route::group(['prefix' => 'kategori'], function () {
//     Route::get('/', [KategoriController::class, 'index'])->name('kategori.index'); // Menampilkan halaman utama kategori
//     Route::post('/list', [KategoriController::class, 'list'])->name('kategori.list'); // Mengambil data kategori (DataTables)
//     // Tambah Kategori
//     Route::get('/create', [KategoriController::class, 'create'])->name('kategori.create');
//     Route::post('/', [KategoriController::class, 'store'])->name('kategori.store');
//     // Tambah Kategori (AJAX)
//     Route::get('/create_ajax', [KategoriController::class, 'create_ajax'])->name('kategori.create_ajax');
//     Route::post('/ajax', [KategoriController::class, 'store_ajax'])->name('kategori.store_ajax');
//     // Detail & Edit Kategori
//     Route::get('/{id}', [KategoriController::class, 'show'])->name('kategori.show');
//     Route::get('/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
//     Route::put('/{id}', [KategoriController::class, 'update'])->name('kategori.update');
//     // Edit Kategori (AJAX)
//     Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax'])->name('kategori.edit_ajax');
//     Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax'])->name('kategori.update_ajax');
//     // Hapus Kategori (AJAX)
//     Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax'])->name('kategori.confirm_ajax'); // Tampilkan konfirmasi hapus
//     Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax'])->name('kategori.delete_ajax'); // Hapus via AJAX
//     // Hapus Kategori (Non-AJAX)
//     Route::delete('/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
// });


// Route::group(['prefix' => 'supplier'], function () {
//     Route::get('/', [SupplierController::class, 'index'])->name('supplier.index'); // Menampilkan halaman utama supplier
//     Route::post('/list', [SupplierController::class, 'list'])->name('supplier.list'); // Mengambil data supplier (DataTables)
//     // Tambah Supplier
//     Route::get('/create', [SupplierController::class, 'create'])->name('supplier.create');
//     Route::post('/', [SupplierController::class, 'store'])->name('supplier.store');
//     // Tambah Supplier (AJAX)
//     Route::get('/create_ajax', [SupplierController::class, 'create_ajax'])->name('supplier.create_ajax');
//     Route::post('/ajax', [SupplierController::class, 'store_ajax'])->name('supplier.store_ajax');
//     // Detail & Edit Supplier
//     Route::get('/{id}', [SupplierController::class, 'show'])->name('supplier.show');
//     Route::get('/{id}/edit', [SupplierController::class, 'edit'])->name('supplier.edit');
//     Route::put('/{id}', [SupplierController::class, 'update'])->name('supplier.update');
//     // Edit Supplier (AJAX)
//     Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax'])->name('supplier.edit_ajax');
//     Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax'])->name('supplier.update_ajax');
//     // Hapus Supplier (AJAX)
//     Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax'])->name('supplier.confirm_ajax'); // Tampilkan konfirmasi hapus
//     Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax'])->name('supplier.delete_ajax'); // Hapus via AJAX
//     // Hapus Supplier (Non-AJAX)
//     Route::delete('/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');
// });

// Route::group(['prefix' => 'barang'], function () {
//     Route::get('/', [BarangController::class, 'index'])->name('barang.index'); // Menampilkan halaman utama barang
//     Route::post('/list', [BarangController::class, 'list'])->name('barang.list'); // Mengambil data barang (DataTables)
//     // Tambah Barang
//     Route::get('/create', [BarangController::class, 'create'])->name('barang.create');
//     Route::post('/', [BarangController::class, 'store'])->name('barang.store');
//     // Tambah Barang (AJAX)
//     Route::get('/create_ajax', [BarangController::class, 'create_ajax'])->name('barang.create_ajax');
//     Route::post('/ajax', [BarangController::class, 'store_ajax'])->name('barang.store_ajax');
//     // Detail & Edit Barang
//     Route::get('/{id}', [BarangController::class, 'show'])->name('barang.show');
//     Route::get('/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit');
//     Route::put('/{id}', [BarangController::class, 'update'])->name('barang.update');
//     // Edit Barang (AJAX)
//     Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax'])->name('barang.edit_ajax');
//     Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax'])->name('barang.update_ajax');
//     // Hapus Barang (AJAX)
//     Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax'])->name('barang.confirm_ajax'); // Tampilkan konfirmasi hapus
//     Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax'])->name('barang.delete_ajax'); // Hapus via AJAX
//     // Hapus Barang (Non-AJAX)
//     Route::delete('/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');
// });


// route::get('/level',action: [LevelController::class, 'index']);
// route::get('/kategori',action: [KategoriController::class, 'index']);
// route::get('/user',action: [UserController::class, 'index']);
// Route::get('/user/tambah', [UserController::class, 'tambah']);
// Route::post('/user/tambah_simpan', [UserController::class, 'tambah_simpan']);
// Route::get('/user/ubah/{id}', [UserController::class, 'ubah']);
// Route::put('/user/ubah_simpan/{id}', [UserController::class, 'ubah_simpan'])->name('user.ubah_simpan');
// Route::get('/user/hapus/{id}', [UserController::class, 'hapus'])->name('user.hapus');
