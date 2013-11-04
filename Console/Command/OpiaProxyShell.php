<?php
App::uses('AppShell', 'Console/Command');

/**
 * Base class for the OpiaProxy Shell
 *
 * @package       OpiaProxy.Console.Command
 */
class OpiaProxyShell extends AppShell
{
    /**
     * Contains tasks to load and instantiate
     *
     * @var array
     */
    public $tasks = array('OpiaProxy.ClearExpired');

    /**
     * Prints version information
     *
     * @return void
     */
    public function version()
    {
        $this->con_log(CAKE_OPIA_VER);
    }

    /**
     * Sends messages to the console to form our help text
     *
     */
    public function help()
    {
        $optionParser = $this->getOptionParser();
        $this->con_log($optionParser->help());
    }

    /**
     * get the option parser.
     *
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        $parser->description('The OpiaProxy cache allows us to cache OPIA API responses in a number of persistent storage systems.')
            ->addSubcommand('clear_expired', array(
                'help' => 'Simulate trips in the specified quadrant.',
                'parser' => $this->ClearExpired->getOptionParser()))
            ->addSubcommand('help', array(
                'help' => 'Prints this help.',))
            ->addSubcommand('version', array(
                'help' => 'Prints the current version of the NetworkSimulator Plugin.',
            ));


        //Add a epilog listing some examples
        $parser->epilog(array('Examples:',
            '    OpiaProxy expire',
        ));

        //configure parser
        return $parser;
    }
}