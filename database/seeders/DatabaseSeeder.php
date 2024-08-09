<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\OIPRequest;
use App\Models\Team;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create a team
        $team = Team::create([
            'name' => 'Germany',
            'country' => 'DE',
            'company_pk' => '45d62a1c-b676-43c4-ae70-1da66b06aa9d'
        ]);

        $user = User::factory()->create([
            'name' => 'Oliver Busk',
            'email' => 'obj@ntg.com',
            'password' => bcrypt('123456'),
        ]);

        $team->users()->attach($user);

        //Craete OIP requests based on the OIPRequests() array
        foreach ($this->OIPRequests() as $request) {
            OIPRequest::create($request);
        }

        //Create branches based on a SQL query using the "cargowise" connection.
        $branches = DB::connection('cargowise')->select('SELECT GB_BranchName, GB_Code, GB_PK FROM branches WHERE GB_IsActive = 1 AND GB_GC = ' .$team->company_pk .'');
        foreach ($branches as $branch) {
            Branch::create([
                'team_id' => $team->id,
                'name' => $branch->GB_BranchName,
                'code' => $branch->GB_Code,
                'branch_pk' => $branch->GB_PK,
            ]);
        }

        //Create departments based on a SQL query using the "cargowise" connection.
        $departments = DB::connection('cargowise')->select('SELECT GE_Desc, GE_Code, GE_PK FROM departments WHERE GE_IsActive = 1');
        foreach ($departments as $department) {
            Department::create([
                'name' => $department->GE_Desc,
                'code' => $department->GE_Code,
                'department_pk' => $department->GE_PK,
            ]);
        }

    }

    public function OIPRequests()
    {
        return [
            [
                'user_id' => 1,
                'business_type' => 'new_account',
                'organization_code' => 'JHPACKMAN',
                'status' => 'rejected',
                'expire_at' => '2025-08-08 11:28:16',
                'comment' => 'haeh',
                'reject_reason' => 'I dont like u',
                'status_updated_by' => 1,
                'status_updated_at' => '2024-08-08 11:29:58',
                'created_at' => '2024-08-08 11:29:37',
                'updated_at' => '2024-08-08 11:29:58',
            ],
            [
                'user_id' => 1,
                'business_type' => 'new_account',
                'organization_code' => 'JHPACKMAN',
                'status' => 'pending',
                'expire_at' => '2025-08-08 11:28:16',
                'comment' => 'I won this account.',
                'created_at' => '2024-08-09 08:07:28',
                'updated_at' => '2024-08-09 08:07:28',
            ],
            [
                'user_id' => 1,
                'business_type' => 'existing_account_new_business',
                'organization_code' => 'KJSAFCPH',
                'status' => 'pending',
                'expire_at' => '2025-08-08 11:28:16',
                'comment' => 'New business I sold',
                'origin' => 'DEFRA',
                'destination' => 'INBOM',
                'mode' => 'air',
                'created_at' => '2024-08-09 08:08:11',
                'updated_at' => '2024-08-09 08:08:11',
            ],
            [
                'user_id' => 1,
                'business_type' => 'value_add_sale_existing_business',
                'organization_code' => 'TEXASSFRA',
                'status' => 'pending',
                'expire_at' => '2025-08-08 11:28:16',
                'comment' => 'Sold it for good',
                'shipment_number' => 'SNTG24023203',
                'created_at' => '2024-08-09 08:08:34',
                'updated_at' => '2024-08-09 08:08:34',
            ],
            [
                'user_id' => 1,
                'business_type' => 'new_account',
                'organization_code' => 'NEWCLIENT1',
                'status' => 'approved',
                'expire_at' => '2025-09-15 14:30:00',
                'comment' => 'Promising new client in tech sector',
                'status_updated_by' => 1,
                'status_updated_at' => '2024-09-10 09:45:22',
                'created_at' => '2024-09-08 16:20:11',
                'updated_at' => '2024-09-10 09:45:22',
            ],
            [
                'user_id' => 1,
                'business_type' => 'existing_account_new_business',
                'organization_code' => 'EXISTCLIENT2',
                'status' => 'pending',
                'expire_at' => '2025-10-01 23:59:59',
                'comment' => 'Expanding services to include sea freight',
                'origin' => 'USLAX',
                'destination' => 'CNSHA',
                'mode' => 'sea',
                'created_at' => '2024-09-20 11:05:33',
                'updated_at' => '2024-09-20 11:05:33',
            ],
            [
                'user_id' => 1,
                'business_type' => 'value_add_sale_existing_business',
                'organization_code' => 'VALUECLIENT3',
                'status' => 'approved',
                'expire_at' => '2025-11-30 18:00:00',
                'comment' => 'Added insurance and custom clearance services',
                'shipment_number' => 'SNTG24056789',
                'status_updated_by' => 1,
                'status_updated_at' => '2024-10-05 14:22:17',
                'created_at' => '2024-10-01 09:30:45',
                'updated_at' => '2024-10-05 14:22:17',
            ],
        ];
    }
}
