<?php

declare(strict_types=1);

namespace chaser\stream\traits;

use chaser\stream\interfaces\part\ConnectedCommunicationUnpackInterface;
use chaser\stream\event\{Message, RecvBufferFull, UnpackingFail};
use chaser\stream\exception\UnpackedException;

/**
 * 通信包装部分特征
 *
 * @package chaser\stream\traits
 *
 * @property-read int $maxRecvBufferSize
 *
 * @uses CommunicationConnected
 */
trait ConnectedCommunicationUnpack
{
    /**
     * 接收缓冲区内容
     *
     * @var string
     */
    protected string $recvBuffer = '';

    /**
     * @inheritDoc
     */
    public function configurations(): array
    {
        return ['maxRecvBufferSize' => ConnectedCommunicationUnpackInterface::MAX_RECV_BUFFER_SIZE];
    }

    /**
     * @inheritDoc
     */
    public function readHandle(string $data): void
    {
        $this->recvBuffer .= $data;
        try {
            $message = $this->unpack();
            if ($message) {
                $this->dispatch(Message::class, $data);
            } elseif (strlen($this->recvBuffer) >= $this->maxRecvBufferSize) {
                $this->dispatch(RecvBufferFull::class);
                $this->destroy(true);
            }
        } catch (UnpackedException $e) {
            $this->dispatch(UnpackingFail::class, $e);
            $this->destroy(true);
        }
    }

    /**
     * 尝试解包
     *
     * @return string
     */
    protected function unpack(): string
    {
        $data = $this->recvBuffer;
        $this->recvBuffer = '';
        return $data;
    }
}
