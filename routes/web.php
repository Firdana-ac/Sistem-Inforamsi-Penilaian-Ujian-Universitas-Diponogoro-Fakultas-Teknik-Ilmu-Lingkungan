<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/welcome', function () {
  return view('welcome');
});

Route::get('/clear-cache', function () {
  Artisan::call('config:clear');
  Artisan::call('cache:clear');
  Artisan::call('config:cache');
  return 'DONE';
});

Auth::routes();
Route::get('/login/cek_email/json', 'UserController@cek_email');
Route::get('/login/cek_password/json', 'UserController@cek_password');
Route::post('/cek-email', 'UserController@email')->name('cek-email')->middleware('guest');
Route::get('/reset/password/{id}', 'UserController@password')->name('reset.password')->middleware('guest');
Route::patch('/reset/password/update/{id}', 'UserController@update_password')->name('reset.password.update')->middleware('guest');

Route::middleware(['auth'])->group(function () {
  Route::get('/', 'HomeController@index')->name('home');
  Route::get('/home', 'HomeController@index')->name('home');
  Route::get('/jadwal/sekarang', 'JadwalController@jadwalSekarang');
  Route::get('/profile', 'UserController@profile')->name('profile');
  Route::get('/pengaturan/profile', 'UserController@edit_profile')->name('pengaturan.profile');
  Route::post('/pengaturan/ubah-profile', 'UserController@ubah_profile')->name('pengaturan.ubah-profile');
  Route::get('/pengaturan/edit-foto', 'UserController@edit_foto')->name('pengaturan.edit-foto');
  Route::post('/pengaturan/ubah-foto', 'UserController@ubah_foto')->name('pengaturan.ubah-foto');
  Route::get('/pengaturan/email', 'UserController@edit_email')->name('pengaturan.email');
  Route::post('/pengaturan/ubah-email', 'UserController@ubah_email')->name('pengaturan.ubah-email');
  Route::get('/pengaturan/password', 'UserController@edit_password')->name('pengaturan.password');
  Route::post('/pengaturan/ubah-password', 'UserController@ubah_password')->name('pengaturan.ubah-password');

  Route::middleware(['siswa'])->group(function () {
    Route::get('/jadwal/siswa', 'JadwalController@siswa')->name('jadwal.siswa');
    Route::get('/ulangan/siswa', 'UlanganController@siswa')->name('ulangan.siswa');
    Route::get('/sikap/siswa', 'SikapController@siswa')->name('sikap.siswa');
    Route::get('/rapot/siswa', 'RapotController@siswa')->name('rapot.siswa');
  });

  Route::middleware(['dosen'])->group(function () {
    Route::get('/absen/harian', 'DosenController@absen')->name('absen.harian');
    Route::post('/absen/simpan', 'DosenController@simpan')->name('absen.simpan');
    Route::get('/jadwal/dosen', 'JadwalController@dosen')->name('jadwal.dosen');
    Route::resource('/nilai', 'NilaiController');
    Route::resource('/ulangan', 'UlanganController');
    Route::resource('/sikap', 'SikapController');
    Route::get('/rapot/predikat', 'RapotController@predikat');
    Route::resource('/rapot', 'RapotController');
  });

  Route::middleware(['admin'])->group(function () {
    Route::middleware(['trash'])->group(function () {
      Route::get('/jadwal/trash', 'JadwalController@trash')->name('jadwal.trash');
      Route::get('/jadwal/restore/{id}', 'JadwalController@restore')->name('jadwal.restore');
      Route::delete('/jadwal/kill/{id}', 'JadwalController@kill')->name('jadwal.kill');
      Route::get('/dosen/trash', 'DosenController@trash')->name('dosen.trash');
      Route::get('/dosen/restore/{id}', 'DosenController@restore')->name('dosen.restore');
      Route::delete('/dosen/kill/{id}', 'DosenController@kill')->name('dosen.kill');
      Route::get('/kelas/trash', 'KelasController@trash')->name('kelas.trash');
      Route::get('/kelas/restore/{id}', 'KelasController@restore')->name('kelas.restore');
      Route::delete('/kelas/kill/{id}', 'KelasController@kill')->name('kelas.kill');
      Route::get('/siswa/trash', 'SiswaController@trash')->name('siswa.trash');
      Route::get('/siswa/restore/{id}', 'SiswaController@restore')->name('siswa.restore');
      Route::delete('/siswa/kill/{id}', 'SiswaController@kill')->name('siswa.kill');
      Route::get('/mapel/trash', 'MapelController@trash')->name('mapel.trash');
      Route::get('/mapel/restore/{id}', 'MapelController@restore')->name('mapel.restore');
      Route::delete('/mapel/kill/{id}', 'MapelController@kill')->name('mapel.kill');
      Route::get('/user/trash', 'UserController@trash')->name('user.trash');
      Route::get('/user/restore/{id}', 'UserController@restore')->name('user.restore');
      Route::delete('/user/kill/{id}', 'UserController@kill')->name('user.kill');
    });
    Route::get('/admin/home', 'HomeController@admin')->name('admin.home');
    Route::get('/admin/pengumuman', 'PengumumanController@index')->name('admin.pengumuman');
    Route::post('/admin/pengumuman/simpan', 'PengumumanController@simpan')->name('admin.pengumuman.simpan');
    Route::get('/dosen/absensi', 'DosenController@absensi')->name('dosen.absensi');
    Route::get('/dosen/kehadiran/{id}', 'DosenController@kehadiran')->name('dosen.kehadiran');
    Route::get('/absen/json', 'DosenController@json');
    Route::get('/dosen/mapel/{id}', 'DosenController@mapel')->name('dosen.mapel');
    Route::get('/dosen/ubah-foto/{id}', 'DosenController@ubah_foto')->name('dosen.ubah-foto');
    Route::post('/dosen/update-foto/{id}', 'DosenController@update_foto')->name('dosen.update-foto');
    Route::post('/dosen/upload', 'DosenController@upload')->name('dosen.upload');
    Route::get('/dosen/export_excel', 'DosenController@export_excel')->name('dosen.export_excel');
    Route::post('/dosen/import_excel', 'DosenController@import_excel')->name('dosen.import_excel');
    Route::delete('/dosen/deleteAll', 'DosenController@deleteAll')->name('dosen.deleteAll');
    Route::resource('/dosen', 'DosenController');
    Route::get('/kelas/edit/json', 'KelasController@getEdit');
    Route::resource('/kelas', 'KelasController');
    Route::get('/siswa/kelas/{id}', 'SiswaController@kelas')->name('siswa.kelas');
    Route::get('/siswa/view/json', 'SiswaController@view');
    Route::get('/listsiswapdf/{id}', 'SiswaController@cetak_pdf');
    Route::get('/siswa/ubah-foto/{id}', 'SiswaController@ubah_foto')->name('siswa.ubah-foto');
    Route::post('/siswa/update-foto/{id}', 'SiswaController@update_foto')->name('siswa.update-foto');
    Route::get('/siswa/export_excel', 'SiswaController@export_excel')->name('siswa.export_excel');
    Route::post('/siswa/import_excel', 'SiswaController@import_excel')->name('siswa.import_excel');
    Route::delete('/siswa/deleteAll', 'SiswaController@deleteAll')->name('siswa.deleteAll');
    Route::resource('/siswa', 'SiswaController');
    Route::get('/mapel/getMapelJson', 'MapelController@getMapelJson');
    Route::resource('/mapel', 'MapelController');
    Route::get('/jadwal/view/json', 'JadwalController@view');
    Route::get('/jadwalkelaspdf/{id}', 'JadwalController@cetak_pdf');
    Route::get('/jadwal/export_excel', 'JadwalController@export_excel')->name('jadwal.export_excel');
    Route::post('/jadwal/import_excel', 'JadwalController@import_excel')->name('jadwal.import_excel');
    Route::delete('/jadwal/deleteAll', 'JadwalController@deleteAll')->name('jadwal.deleteAll');
    Route::resource('/jadwal', 'JadwalController');
    Route::get('/ulangan-kelas', 'UlanganController@create')->name('ulangan-kelas');
    Route::get('/ulangan-siswa/{id}', 'UlanganController@edit')->name('ulangan-siswa');
    Route::get('/ulangan-show/{id}', 'UlanganController@ulangan')->name('ulangan-show');
    Route::get('/sikap-kelas', 'SikapController@create')->name('sikap-kelas');
    Route::get('/sikap-siswa/{id}', 'SikapController@edit')->name('sikap-siswa');
    Route::get('/sikap-show/{id}', 'SikapController@sikap')->name('sikap-show');
    Route::get('/rapot-kelas', 'RapotController@create')->name('rapot-kelas');
    Route::get('/rapot-siswa/{id}', 'RapotController@edit')->name('rapot-siswa');
    Route::get('/rapot-show/{id}', 'RapotController@rapot')->name('rapot-show');
    Route::get('/predikat', 'NilaiController@create')->name('predikat');
    Route::resource('/user', 'UserController');
  });
});
