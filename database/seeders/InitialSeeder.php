<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\{Bidang, Pengurus, Jobdesk, User};
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;


class InitialSeeder extends Seeder {
public function run(): void {
// Roles
$rPengurus = Role::firstOrCreate(['name'=>'pengurus']);
$rAdminBid = Role::firstOrCreate(['name'=>'admin_bidang']);
$rAdminUtm = Role::firstOrCreate(['name'=>'admin_utama']);


// Users
$admin = User::firstOrCreate(
['email'=>'admin@imdad.test'],
['name'=>'Admin Utama','password'=>Hash::make('admin12345')]
);
$admin->assignRole('admin_utama');


$ab = User::firstOrCreate(
['email'=>'adminbapakamar@imdad.test'],
['name'=>'Admin Bapak Kamar','password'=>Hash::make('admin12345')]
);
$ab->assignRole('admin_bidang');


$peng = User::firstOrCreate(
['email'=>'pengurus@imdad.test'],
['name'=>'Pengurus Umum','password'=>Hash::make('pengurus123')]
);
$peng->assignRole('pengurus');


// Bidang
$bidangData = [
['slug'=>'bapakamar','name'=>'Pengurus Bapak Kamar','icon'=>'fa-bed','color'=>'bg-blue-500'],
['slug'=>'bk_keamanan','name'=>'Pengurus BK dan Keamanan','icon'=>'fa-shield-alt','color'=>'bg-green-500'],
['slug'=>'minat_bakat','name'=>'Pengurus Minat Bakat','icon'=>'fa-palette','color'=>'bg-purple-500'],
['slug'=>'kebersihan','name'=>'Pengurus Kebersihan','icon'=>'fa-broom','color'=>'bg-yellow-500'],
['slug'=>'sarpras','name'=>'Pengurus Sarpras','icon'=>'fa-tools','color'=>'bg-orange-500'],
['slug'=>'kesehatan','name'=>'Pengurus Kesehatan','icon'=>'fa-heartbeat','color'=>'bg-red-500'],
];
foreach($bidangData as $b){ Bidang::firstOrCreate(['slug'=>$b['slug']], $b); }
$bBpk = Bidang::where('slug','bapakamar')->first();


// Pengurus contoh (bapakamar)
$kelasList = ['7','8','9','10','11','12','tahfidz'];
foreach(['Arif Hermawan','Gading Wandira','Hasan Ngulwi','Iqbal Rofiq'] as $i=>$nama){
Pengurus::firstOrCreate(['nama'=>$nama,'bidang_id'=>$bBpk->id], ['kelas'=>$kelasList[$i%count($kelasList)]]);
}


// Jobdesk contoh
$jobMalam = [
'Mengkondisikan santri piket kamar dan sekitarnya bakda jamaah Asar',
'Mengkondisikan semua santri mandi dan makan sore pukul 17 sampai Magrib',
'Memastikan santri berangkat jamaah Magrib sebelum adzan',
'Mengunci gerbang kamar setelah santri berangkat jamaah Magrib',
];
}
}