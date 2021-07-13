<?php

declare(strict_types=1);

namespace chaser\stream\traits;

use chaser\stream\interfaces\SubscriberInterface;
use chaser\stream\event\{RecvBufferFull, UnpackingFail};

/**
 * 通信包装事件订阅部分特征
 *
 * @package chaser\stream\traits
 *
 * @see SubscriberInterface
 */
trait ConnectedCommunicationUnpackSubscribable
{
    /**
     * @inheritDoc
     */
    public static function events(): array
    {
        return [RecvBufferFull::class => 'receiveBufferFull', UnpackingFail::class => 'unpackingFail'];
    }

    /**
     * 接收缓冲区满事件响应
     *
     * @param RecvBufferFull $event
     */
    public function recvBufferFull(RecvBufferFull $event): void
    {
    }

    /**
     * 解包失败事件响应
     *
     * @param UnpackingFail $event
     */
    public function unpackingFail(UnpackingFail $event): void
    {
    }
}
