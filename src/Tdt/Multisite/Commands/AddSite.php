<?php

namespace Tdt\Multisite\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Illuminate\Database\Capsule\Manager;

class AddSite extends Command
{
/**
     * The console command name
     *
     * @var string
     */
    protected $name = 'multisite:add';

    /**
     * The console command description
     *
     * @var string
     */
    protected $description = "Add a site to the datatank.";

    /**
     * Execute the console command
     *
     * @return void
     */
    public function fire()
    {
        $sitename = $this->argument('sitename');

        // Check if the job exists
        $multisite = \MultiSite::where('sitename', '=', $sitename)->first();

        if (!empty($multisite)) {
            $this->error("The site with name $sitename already exists.\n");
            exit();
        }

        // Ask for the database name
        $database = $this->askNotNull('Provide the name of the database: ');

        // Ask for the driver
        $driver = $this->askNotNull('Provide the name of the database driver (e.g. MySQL): ');
        $driver = strtolower($driver);

        // Ask for the prefix
        $prefix = $this->ask('Provide the prefix of the tables (can be null): ');

        // Ask for the username of the database
        $user = $this->askNotNull('Provide the name of the user (needs write permissions): ');

        // Ask for the password of the user
        $password = $this->ask('Provide the password of the user: ');

        // Ask for the host of the database
        $host = $this->askNotNull('Provide the host of the database: ');

        // Ask for the charset of the database
        $charset = $this->ask('Provide the charset of the database (default is utf8): ');

        if (empty($charset)) {
            $charset = 'utf8';
        }

        // Ask for the collation of the database
        $collation = $this->ask('Provide the collation of the database (default is utf8_general_ci): ');

        if (empty($collation)) {
            $collation = 'utf8_general_ci';
        }

        // Ask for the domain of the multisite (optional)
        $domain = $this->ask(
            'Provide the domain that the site represents (optional). This will overwrite the need to use the name of the site as a slug.'
        );

        $site = new \MultiSite();

        $site->sitename = $sitename;
        $site->driver = $driver;
        $site->prefix = $prefix;
        $site->username = $user;
        $site->password = $password;
        $site->database = $database;
        $site->host = $host;
        $site->collation = $collation;
        $site->charset = $charset;
        $site->domain = $domain;

        $result = $site->save();

        // Migrate the database with the necessary tables
        \Config::set('database.connections', array($site->sitename => $site->toArray()));
        \Config::set('database.default', $site->sitename);

        \Artisan::call('migrate');
        \Artisan::call('migrate', array('--package' => 'cartalyst/sentry'));
        \Artisan::call('db:seed');

        if ($result) {
            $this->info("The new site was added successfully!");
        } else {
            $this->error('The new site was not added successfully, check the logs for error messages');
        }

    }

    private function askNotNull($message)
    {
        $val = $this->ask($message);

        while (empty($val)) {
            $val = $this->ask($message);
        }

        return $val;
    }

    /**
     * Get the console command arguments
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('sitename', InputArgument::REQUIRED, 'The name of the site to add.'),
        );
    }

    /**
     * Get the console command options
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(

        );
    }
}
