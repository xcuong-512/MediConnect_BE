<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\Person;
use App\Models\Staff; // Đảm bảo bạn đã có model này
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctorRole = Role::where('name', 'doctor')->first();

        $doctors = [
            [
                'email' => 'bacsi.xc@mediconnect.com',
                'full_name' => 'BS. Nguyễn Trần Xuân Cường',
                'phone' => '0899939682',
                'gender' => 'male',
                'staff_code' => 'DOC-001'
            ],
            [
                'email' => 'bacsi.thanhthao@mediconnect.com',
                'full_name' => 'BS. Nguyễn Thanh Thảo',
                'phone' => '0901234568',
                'gender' => 'female',
                'staff_code' => 'DOC-002'
            ]
        ];

        foreach ($doctors as $doc) {
            DB::transaction(function () use ($doc, $doctorRole) {
                $account = Account::create([
                    'email' => $doc['email'],
                    'password' => Hash::make('123456'),
                    'status' => 'active',
                ]);

                if ($doctorRole) {
                    $account->roles()->attach($doctorRole->id);
                }

                $person = Person::create([
                    'account_id' => $account->id,
                    'full_name' => $doc['full_name'],
                    'phone' => $doc['phone'],
                    'gender' => $doc['gender'],
                    'address' => 'Hà Nội, Việt Nam',
                ]);

                Staff::create([
                    'person_id' => $person->id,
                    'staff_code' => $doc['staff_code'],
                ]);
            });
        }
    }
}
