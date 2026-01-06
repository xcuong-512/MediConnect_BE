<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        Branch::create([
            'name' => 'Bệnh viện Đa khoa MediConnect HN',
            'address' => '123 Đường Láng, Đống Đa, Hà Nội',
            'phone' => '02473008888'
        ]);

        Branch::create([
            'name' => 'Phòng khám MediConnect SG',
            'address' => '456 Quận 1, TP.HCM',
            'phone' => '02873008888'
        ]);
    }
}
