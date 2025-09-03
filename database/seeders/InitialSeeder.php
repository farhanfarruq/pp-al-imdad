<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Bidang;
use App\Models\Pengurus;
use App\Models\Jobdesk;
use Illuminate\Support\Facades\Hash;

class InitialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membersihkan cache permission agar tidak terjadi konflik
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create Roles
        $roleAdminUtama = Role::firstOrCreate(['name' => 'admin_utama']);
        $roleAdminBidang = Role::firstOrCreate(['name' => 'admin_bidang']);
        $rolePengurus = Role::firstOrCreate(['name' => 'pengurus']);

        // 2. Create Bidang (DIPINDAHKAN KE ATAS)
        $bidangData = [
            ['id' => 1, 'slug' => 'bapakamar', 'name' => 'Pengurus Bapak Kamar', 'icon' => 'fas fa-bed', 'color' => 'bg-blue-500'],
            ['id' => 2, 'slug' => 'bk_keamanan', 'name' => 'Pengurus BK dan Keamanan', 'icon' => 'fas fa-shield-alt', 'color' => 'bg-green-500'],
            ['id' => 3, 'slug' => 'minat_bakat', 'name' => 'Pengurus Minat Bakat', 'icon' => 'fas fa-palette', 'color' => 'bg-purple-500'],
            ['id' => 4, 'slug' => 'kebersihan', 'name' => 'Pengurus Kebersihan', 'icon' => 'fas fa-broom', 'color' => 'bg-yellow-500'],
            ['id' => 5, 'slug' => 'sarpras', 'name' => 'Pengurus Sarpras', 'icon' => 'fas fa-tools', 'color' => 'bg-orange-500'],
            ['id' => 6, 'slug' => 'kesehatan', 'name' => 'Pengurus Kesehatan', 'icon' => 'fas fa-heartbeat', 'color' => 'bg-red-500'],
        ];
        foreach ($bidangData as $data) {
            Bidang::updateOrCreate(['id' => $data['id']], $data);
        }

        // 3. Create Users (SETELAH BIDANG DIBUAT)
        $adminUtama = User::firstOrCreate(
            ['email' => 'adminutama@gmail.com'],
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('adminpengurus2025'),
            ]
        );
        $adminUtama->assignRole($roleAdminUtama);

        // Sekarang aman untuk membuat user ini karena Bidang ID 1 sudah ada
        $adminBapakKamar = User::firstOrCreate(
            ['email' => 'adminbapakamar@gmail.com'],
            [
                'name' => 'Admin Bapak Kamar',
                'password' => Hash::make('admin123'),
                'bidang_id' => 1,
            ]
        );
        $adminBapakKamar->assignRole($roleAdminBidang);

        $pengurusUser = User::firstOrCreate(
            ['email' => 'pengurus@gmail.com'],
            [
                'name' => 'Pengurus Umum',
                'password' => Hash::make('pengurus123'),
            ]
        );
        $pengurusUser->assignRole($rolePengurus);

        // 4. Create Pengurus
        $pengurusData = [
            1 => [ // Bapak Kamar
                ['nama' => 'Arif Hermawan', 'kelas' => '7'], ['nama' => 'Gading Wandira Putra Khoiri', 'kelas' => '7'],
                ['nama' => 'Hasan Ngulwi Mufti', 'kelas' => '7'], ['nama' => 'Iqbal Rofiqul Azhar', 'kelas' => '8'],
                ['nama' => 'Mohamad Mauludul Fadilah', 'kelas' => '8'], ['nama' => 'Muhammad Sulthan Maulana Asy Syauqi', 'kelas' => '8'],
                ['nama' => 'M Iqbal Mirza Hidayat', 'kelas' => '9'], ['nama' => 'Muhammad Ngainun Najib', 'kelas' => '9'],
                ['nama' => 'Muhammad Dzunnurain', 'kelas' => '10'], ['nama' => 'Syahrizal Nur Faizin', 'kelas' => '10'],
                ['nama' => 'Arif Herianto', 'kelas' => '11'], ['nama' => 'Miftahurroyyan', 'kelas' => '11'],
                ['nama' => 'Muhammad Latif Baharuddin', 'kelas' => '12'], ['nama' => 'Rafi Luthfan Zaky', 'kelas' => '12'],
                ['nama' => 'Muhamad Dafa Nur Rohman', 'kelas' => 'tahfidz'], ['nama' => 'Muhamad Dafi Nur Rohim', 'kelas' => 'tahfidz']
            ],
            2 => [['nama' => 'Ustad Hasan'], ['nama' => 'Ustad Ahmad']],
            3 => [['nama' => 'Ustadzah Siti'], ['nama' => 'Ustad Yusuf']],
            4 => [['nama' => 'Pak Budi'], ['nama' => 'Pak Slamet']],
            5 => [['nama' => 'Pak Joko'], ['nama' => 'Pak Andi']],
            6 => [['nama' => 'Dokter Rina'], ['nama' => 'Perawat Sari']],
        ];
        foreach ($pengurusData as $bidangId => $list) {
            foreach($list as $p) {
                Pengurus::firstOrCreate(['bidang_id' => $bidangId, 'nama' => $p['nama']], ['kelas' => $p['kelas'] ?? null]);
            }
        }

        // 5. Create Jobdesk
        $jobdeskData = [
            ['bidang_id' => 1, 'waktu' => 'malam', 'deskripsi' => 'Mengkondisikan santri piket kamar dan sekitarnya bakda jamaah Asar'],
            ['bidang_id' => 1, 'waktu' => 'malam', 'deskripsi' => 'Mengkondisikan semua santri mandi dan makan sore pukul 17 sampai Magrib'],
            ['bidang_id' => 1, 'waktu' => 'malam', 'deskripsi' => 'Memastikan santri berangkat jamaah Magrib sebelum adzan'],
            ['bidang_id' => 1, 'waktu' => 'malam', 'deskripsi' => 'Mengunci gerbang kamar setelah santri berangkat jamaah Magrib'],
            ['bidang_id' => 1, 'waktu' => 'malam', 'deskripsi' => 'Mendampingi dan mengabsen santri jamaah Magrib'],
            ['bidang_id' => 1, 'waktu' => 'malam', 'deskripsi' => 'Memastikan santri berangkat sorogan Quran dan Muhafazhah'],
            ['bidang_id' => 1, 'waktu' => 'malam', 'deskripsi' => 'Memastikan santri berangkat Madin'],
            ['bidang_id' => 1, 'waktu' => 'malam', 'deskripsi' => 'Memastikan santri mengikuti bandongan talaqqi dan mengabsennya'],
            ['bidang_id' => 1, 'waktu' => 'malam', 'deskripsi' => 'Mendampingi dan mengabsen santri jamaah Isya'],
            ['bidang_id' => 1, 'waktu' => 'malam', 'deskripsi' => 'Mendampingi belajar malam bakda Isya'],
            ['bidang_id' => 1, 'waktu' => 'malam', 'deskripsi' => 'Mengkondisikan santri agar segera tidur pukul 23'],
            ['bidang_id' => 1, 'waktu' => 'malam', 'deskripsi' => 'Mengabsen santri yang berada di pondok pukul 23'],
            ['bidang_id' => 1, 'waktu' => 'malam', 'deskripsi' => 'Lainnya'],

            ['bidang_id' => 1, 'waktu' => 'subuh', 'deskripsi' => 'Membangunkan santri untuk jamaah Subuh sebelum Subuh'],
            ['bidang_id' => 1, 'waktu' => 'subuh', 'deskripsi' => 'Menata bantal selimut dan memastikan kamar bersih setelah Subuh'],
            ['bidang_id' => 1, 'waktu' => 'subuh', 'deskripsi' => 'Mengunci gerbang setelah santri berangkat Subuh'],
            ['bidang_id' => 1, 'waktu' => 'subuh', 'deskripsi' => 'Mendampingi dan mengabsen santri jamaah Subuh'],
            ['bidang_id' => 1, 'waktu' => 'subuh', 'deskripsi' => 'Memastikan sorogan Quran dan Kitab bakda Subuh'],
            ['bidang_id' => 1, 'waktu' => 'subuh', 'deskripsi' => 'Mengkondisikan santri piket kamar bakda atau saat sorogan'],
            ['bidang_id' => 1, 'waktu' => 'subuh', 'deskripsi' => 'Mengkondisikan mandi dan sarapan bakda sorogan'],
            ['bidang_id' => 1, 'waktu' => 'subuh', 'deskripsi' => 'Memastikan santri berangkat sekolah sebelum pukul 07'],
            ['bidang_id' => 1, 'waktu' => 'subuh', 'deskripsi' => 'Lainnya'],

            ['bidang_id' => 2, 'waktu' => null, 'deskripsi' => 'Melakukan patroli keamanan pondok'],
            ['bidang_id' => 2, 'waktu' => null, 'deskripsi' => 'Mengecek kondisi gerbang dan pagar'],
            ['bidang_id' => 3, 'waktu' => null, 'deskripsi' => 'Mengkoordinir kegiatan ekstrakurikuler'],
            ['bidang_id' => 3, 'waktu' => null, 'deskripsi' => 'Mempersiapkan perlombaan'],
            ['bidang_id' => 4, 'waktu' => null, 'deskripsi' => 'Mengecek kebersihan kamar santri'],
            ['bidang_id' => 4, 'waktu' => null, 'deskripsi' => 'Koordinasi piket kebersihan'],
            ['bidang_id' => 5, 'waktu' => null, 'deskripsi' => 'Pemeliharaan fasilitas pondok'],
            ['bidang_id' => 5, 'waktu' => null, 'deskripsi' => 'Perbaikan kerusakan ringan'],
            ['bidang_id' => 6, 'waktu' => null, 'deskripsi' => 'Pemeriksaan kesehatan santri'],
            ['bidang_id' => 6, 'waktu' => null, 'deskripsi' => 'Penanganan santri sakit'],
        ];
        foreach ($jobdeskData as $data) {
            Jobdesk::firstOrCreate($data);
        }
    }
}