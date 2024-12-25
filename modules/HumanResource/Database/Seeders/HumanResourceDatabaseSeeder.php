<?php

namespace Modules\HumanResource\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HumanResourceDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        DB::table('pay_frequencies')->delete();
        $frequency_names = ['Weekly', 'Biweekly', 'Monthly', 'Yearly'];
        foreach ($frequency_names as $name) {
            DB::table('pay_frequencies')->insert([
                'uuid' => (string) Str::uuid(),
                'frequency_name' => $name,
                'is_active' => 1,
            ]);
        }

        DB::table('employee_types')->delete();
        $employee_types = ['Intern', 'Contractual', 'Full Time', 'Remote'];
        foreach ($employee_types as $type) {
            DB::table('employee_types')->insert([
                'uuid' => (string) Str::uuid(),
                'name' => $type,
                'is_active' => 1,
            ]);
        }

        DB::table('duty_types')->delete();
        $duty_types = ['Full Time', 'Part Time', 'Contractual', 'Other'];
        foreach ($duty_types as $duty_type) {
            DB::table('duty_types')->insert([
                'uuid' => (string) Str::uuid(),
                'type_name' => $duty_type,
                'is_active' => 1,
            ]);
        }

        DB::table('setup_rules')->delete();
        DB::table('setup_rules')->insert([
            'uuid' => (string) Str::uuid(),
            'name' => 'Basic',
            'type' => 'basic',
            'is_active' => 1,
        ]);

        DB::table('setup_rules')->insert([
            'uuid' => (string) Str::uuid(),
            'name' => 'Allowance',
            'type' => 'allowance',
            'is_active' => 1,
        ]);

        DB::table('setup_rules')->insert([
            'uuid' => (string) Str::uuid(),
            'name' => 'Deduction',
            'type' => 'deduction',
            'is_active' => 1,
        ]);

        DB::table('setup_rules')->insert([
            'uuid' => (string) Str::uuid(),
            'name' => 'Bonus',
            'type' => 'bonus',
            'is_active' => 1,
        ]);

        DB::table('setup_rules')->insert([
            'uuid' => (string) Str::uuid(),
            'name' => 'Regular Working days',
            'start_time' => '09:00:00',
            'end_time' => '18:00:00',
            'type' => 'time',
            'is_active' => 1,
        ]);

        DB::table('setup_rules')->insert([
            'uuid' => (string) Str::uuid(),
            'name' => 'Winter',
            'start_time' => '10:00:00',
            'end_time' => '19:00:00',
            'type' => 'time',
            'is_active' => 1,
        ]);

        DB::table('marital_statuses')->delete();
        $marital_names = ['Single', 'Married', 'Divorced', 'Widowed', 'Other'];
        foreach ($marital_names as $name) {
            DB::table('marital_statuses')->insert([
                'uuid' => (string) Str::uuid(),
                'name' => $name,
                'is_active' => 1,
            ]);
        }

        DB::table('employee_performance_evaluation_types')->truncate();
        DB::table('employee_performance_evaluation_types')->insert([
            [
                'uuid'      => (string) Str::uuid(),
                'type_name' => 'Number',
                'type_no'   => 1
            ],
            [
                'uuid'      => (string) Str::uuid(),
                'type_name' => 'Quality',
                'type_no'   => 2
            ],
            [
                'uuid'      => (string) Str::uuid(),
                'type_name' => 'User Input',
                'type_no'   => 3
            ],
            [
                'uuid'      => (string) Str::uuid(),
                'type_name' => 'Checkbox',
                'type_no'   => 4
            ],
        ]);

        DB::table('employee_performance_evaluations')->truncate();
        DB::table('employee_performance_evaluations')->insert([
            [
                'uuid' => (string) Str::uuid(),
                'title' => 'Poor',
                'score' => 0,
                'short_code' => 'P',
                'evaluation_type_id' => 1
            ],
            [
                'uuid' => (string) Str::uuid(),
                'title' => 'Need Improvement',
                'score' => 0,
                'short_code' => 'NI',
                'evaluation_type_id' => 1
            ],
            [
                'uuid' => (string) Str::uuid(),
                'title' => 'Good',
                'score' => 0,
                'short_code' => 'G',
                'evaluation_type_id' => 1
            ],
            [
                'uuid' => (string) Str::uuid(),
                'title' => 'Very Good',
                'score' => 0,
                'short_code' => 'VG',
                'evaluation_type_id' => 1
            ],
            [
                'uuid' => (string) Str::uuid(),
                'title' => 'Excellent',
                'score' => 0,
                'short_code' => 'E',
                'evaluation_type_id' => 1
            ],
        ]);

        DB::table('employee_performance_types')->truncate();
        DB::table('employee_performance_types')->insert([
            [
                'uuid' => (string) Str::uuid(),
                'title' => 'Assessment of Goals / Objective set during the review period',
                'type' => 1,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'title' => 'Assessment of other performance standards and indicators',
                'type' => 1,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'title' => 'Quality Of Work',
                'type' => 1,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'title' => 'Lesson Content',
                'type' => 1,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'title' => 'Punctuality',
                'type' => 1,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'title' => 'Behavior',
                'type' => 1,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'title' => 'Extra Services',
                'type' => 1,
            ],
        ]);

        DB::table('employee_performance_criterias')->truncate();
        DB::table('employee_performance_criterias')->insert([
            [
                'uuid' => (string) Str::uuid(),
                'title' => 'Demonstrated Knowledge of duties & Quality of Work',
                'performance_type_id' => 1,
                'evaluation_type_id' => 1,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'title' => 'Timeliness of Delivery',
                'performance_type_id' => 1,
                'evaluation_type_id' => 1,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'title' => 'Impact of Achievement',
                'performance_type_id' => 1,
                'evaluation_type_id' => 1,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'title' => 'Overall Achievement of Goals/Objectives',
                'performance_type_id' => 1,
                'evaluation_type_id' => 1,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'title' => 'Going beyond the call of Duty',
                'performance_type_id' => 1,
                'evaluation_type_id' => 1,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'title' => 'Interpersonal skills & ability to work in a team environment',
                'performance_type_id' => 2,
                'evaluation_type_id' => 1,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'title' => 'Attendance and Punctuality',
                'performance_type_id' => 2,
                'evaluation_type_id' => 1,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'title' => 'Communication Skills',
                'performance_type_id' => 2,
                'evaluation_type_id' => 1,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'title' => 'Contributing to IIHT Gambia’s mission',
                'performance_type_id' => 2,
                'evaluation_type_id' => 1,
            ],
        ]);
    }
}
