<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/10/11
 * Time: 下午11:38
 */

namespace Swokit\Server\Component;

use Swoole\Process;

/**
 * Class MicroTimer - a Microsecond timer
 *
 * @link https://wiki.swoole.com/wiki/page/p-alarm.html
 * @package Swokit\Server\Component
 *
 * ```php
 * $t = new MicroTimer;
 * $t->create()->start(100 * 1000);
 *
 * ```
 */
class MicroTimer
{
    /**
     * @return $this
     */
    public function create(): self
    {
        Process::signal(\SIGALRM, function () {
            $this->run();
        });

        return $this;
    }

    /**
     * @param int $uSecond
     */
    public function start(int $uSecond): void
    {
        Process::alarm($uSecond);
    }

    /**
     *
     */
    protected function run(): void
    {
        static $i = 0;
        echo "#{$i}\talarm\n";
        $i++;

        if ($i > 20) {
            $this->clear(1);
        }
    }

    /**
     * @param bool $exit
     */
    public function clear($exit = false): void
    {
        Process::alarm(-1);

        if ($exit) {
            exit(0);
        }
    }
}
