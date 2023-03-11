<?php
namespace App\Console\Traits;

trait UsesDatedConsoleOutput {

    /**
     * display message to your screen
     *
     * @param string $string
     */
    public function line($string, $style = null, $verbosity = null) {
        parent::line(date('Y-m-d H:i:s') . ' - ' . $string, $style, $verbosity);
    }

    /**
     *
     * @param string $string
     */
    public function warnVerbose($string) {
        if ($this->option('verbose')) {
            $this->warn($string);
        }
    }

    /**
     *
     * @param string $string
     */
    public function infoVerbose($string) {
        if ($this->option('verbose')) {
            $this->info($string);
        }
    }
}
