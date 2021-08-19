<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersQuestionsAnswersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('answers')->delete(); //NB deleting is in reverse order to avoid errors.
        DB::table('questions')->delete();
        DB::table('users')->delete();

        \App\Models\User::factory(3)->create()->each(function($u){
            $u->questions()
                ->saveMany(
                    \App\Models\Question::factory(rand(1,5))->make()
                )
                ->each(function ($q){
                    $q->answers()->saveMany(
                        \App\Models\Answer::factory(rand(1,5))->make()
                    );
                });
        });
    }
}
