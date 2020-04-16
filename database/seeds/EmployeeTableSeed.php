<?php

use App\Employee;
use Illuminate\Database\Seeder;

class EmployeeTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Employee::create([
            'name' => 'Yogie Setya Nugraha',
            'salary' => 12000000,
            'age' => '22'
        ]);
    }
}
