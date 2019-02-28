<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/9/10
 * Time: 上午9:45
 */

namespace Swokit\Server\Rpc\Inside;

use Swokit\Server\Rpc\ServiceInterface;

/**
 * Class MonitorService
 * @package Swokit\Server\Rpc\Inside
 */
class MonitorService implements ServiceInterface
{
    public function services(): array
    {
        return [
            'name' => 'config',
            'name1' => 'config',
        ];
    }
}
