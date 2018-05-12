<?php namespace AgelxNash\FirebaseDynamicLinks;

use Illuminate\Console\Command as BaseCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use FirebaseDynamicLinks;

class Command extends BaseCommand
{
    /**
     * @var string $name The console command name.
     */
    protected $name = 'firebase-dynamic-links:make';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'firebase-dynamic-links:make {url}';

    /**
     * @var string $description The console command description.
     */
    protected $description = 'Firebase dynamic links';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $url = $this->argument('url');

        $link = FirebaseDynamicLinks::shorten($url);

        $this->output->writeln($link);
    }
}
