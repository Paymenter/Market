<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class User extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'm:create:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $name = $this->ask('What is your Username?');
        $email = $this->ask('What is your email?');
        $password = $this->secret('What is the password?');
        $admin = $this->confirm('Do you want to make this user an admin?');

        $user = new \App\Models\User();
        $user->username = $name;
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->is_admin = $admin;

        $user->save();
        return Command::SUCCESS;
    }
}
