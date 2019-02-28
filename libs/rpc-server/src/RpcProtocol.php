<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/9/10
 * Time: 上午10:43
 */

namespace Swokit\Server\Rpc;

/**
 * Class RpcProtocol
 * @package Swokit\Server\Rpc
 */
class RpcProtocol
{
    public const KEY_SERVICE = 'Rpc-Service';
    public const KEY_META    = 'Rpc-Meta';

    public const KEY_PARAMS = 'Rpc-Params';
    public const KEY_RESULT = 'Rpc-Result';

    /**
     * @param string $service
     * @param string $params
     * @param string $metas
     * @return string
     */
    public function buildRequest($service, $metas, $params): string
    {
        return sprintf(
            "%s: %s\r\n%s: %s\r\n%s: %s\r\n\r\n",
            self::KEY_SERVICE, $service, self::KEY_META, $metas, self::KEY_PARAMS, $params
        );
    }

    /**
     * @param string $service
     * @param string $meta
     * @param string $result
     * @return string
     */
    public function buildResponse($service, $meta, $result): string
    {
        return sprintf(
            "%s: %s\r\n%s: %s\r\n%s: %s\r\n\r\n",
            self::KEY_SERVICE, $service, self::KEY_RESULT, $result, self::KEY_META, $meta
        );
    }

    public function parseRequest($buffer): void
    {
        // 解析客户端发送过来的协议
        $hasService = preg_match('/Rpc-Service:\s(.*);\r\n/i', $buffer, $service);
        $hasParams = preg_match('/Rpc-Params:\s(.*);\r\n/i', $buffer, $params);
        $hasMeta = preg_match('/Rpc-Meta:\s(.*);\r\n/i', $buffer, $meta);
    }

    public function parseResponse($buffer): void
    {
        // 解析服务端发送过来的协议
        $hasService = preg_match('/Rpc-Service:\s(.*);\r\n/i', $buffer, $service);
        $hasResult = preg_match('/Rpc-Result:\s(.*);\r\n/i', $buffer, $result);
        $hasMeta = preg_match('/Rpc-Meta:\s(.*);\r\n/i', $buffer, $meta);
    }
}
