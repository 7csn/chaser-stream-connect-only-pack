<?php

declare(strict_types=1);

namespace chaser\stream\interfaces\part;

/**
 * 有连接通信解包部分接口
 *
 * @package chaser\stream\interfaces\part
 */
interface ConnectedCommunicationUnpackInterface
{
    /**
     * 接收缓冲区默认上限 10M
     *
     * @var int
     */
    public const MAX_RECV_BUFFER_SIZE = 10 << 10 << 10;
}
