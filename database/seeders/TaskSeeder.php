<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tasks')->insert([
            [
                'title' => 'Tarea 1',
                'description' => 'Descripción de la tarea 1',
                'status' => 'pending',
                'due_date' => Carbon::now()->addDays(7)->toDateString(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Tarea 2',
                'description' => 'Descripción de la tarea 2',
                'status' => 'in_progress',
                'due_date' => Carbon::now()->addDays(14)->toDateString(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Tarea 3',
                'description' => 'Descripción de la tarea 3',
                'status' => 'completed',
                'due_date' => Carbon::now()->addDays(21)->toDateString(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
