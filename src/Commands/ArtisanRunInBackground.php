<?php

namespace Waz\WazArtisanBackground\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ArtisanRunInBackground extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:background';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $commands = Artisan::all();
        $output = $this->choice('What would you like to run?', array_keys($commands));
        $command = $commands[$output];
        if ($command instanceof Command) {
            $options = [];
            $args = [];
//            InputOption::
            foreach ($command->getDefinition()->getOptions() as $key => $option) {
                $value = $this->ask('Enter value for ' . $key);
                if ($option instanceof InputOption) {
                    if ($option->isValueRequired() && $option->getDefault() == null && ($value === null || trim($value) === '')) {
                        $this->error($key . ' is required');
                        return 1;
                    }
                }
                if ($option->getDefault() === false && $value === null) {
                    continue;
                }
                if ($option->getDefault() === false && $value === 'true' || $value === '1') {
                    $options['--' . $key] = null;
                    continue;
                }
                $options['--' . $key] = $value;
            }

            foreach ($command->getDefinition()->getArguments() as $key => $arg) {
                $value = $this->ask('Enter value for ' . $key);
                if ($arg instanceof InputArgument) {
                    if ($arg->getDefault() == null && ($value === null || trim($value) === '')) {
                        $this->error($key . ' is required');
                        return 1;
                    }
                }
                $args[$key] = $value;
            }
//            dd($options, $args);
            dispatch(function () use ($output, $options, $args) {
//                $this->call($output, [...$options, ...$args]);
                Artisan::call($output, [...$options, ...$args]);
            })->onQueue('default_long')->onConnection('redis-long-running');
        }
    }
}
