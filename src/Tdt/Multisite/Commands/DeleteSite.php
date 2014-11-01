<?php

namespace Tdt\Multisite\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Illuminate\Database\Capsule\Manager;

class DeleteSite extends Command
{
/**
     * The console command name
     *
     * @var string
     */
    protected $name = 'multisite:delete';

    /**
     * The console command description
     *
     * @var string
     */
    protected $description = "Delete a site.";

    /**
     * Execute the console command
     *
     * @return void
     */
    public function fire()
    {
        $sitename = $this->argument('sitename');

        // Check if the job exists
        $site = \MultiSite::where('sitename', '=', $sitename)->first();

        if (empty($site)) {
            $this->error("The site with name $sitename does not exist.");
            exit();
        }

        if ($this->confirm("You are about to delete the site " . $sitename . ". Are you sure you want to delete it? (Y/n)")) {

            if ($site->delete()) {
                $this->info("The multisite entry $sitename was successfully removed, resetting the used database of this site.");
            }

            // Migrate the database with the necessary tables
            \Config::set('database.connections', array($site->sitename => $site->toArray()));
            \Config::set('database.default', $site->sitename);

            \Artisan::call('migrate:reset');

            $this->info("The database that was used for the multisite $sitename, was successfully reset.");
        } else {
            $this->info("Deletion of the site $sitename aborted.");
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
