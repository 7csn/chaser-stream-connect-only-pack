<?php

declare(strict_types=1);

namespace chaser\stream\event;

use chaser\stream\traits\PropertyReadable;
use Throwable;

/**
 * 解包失败事件类
 *
 * @package chaser\stream\event
 *
 * @property-read Throwable $exception
 */
class UnpackingFail
{
    use PropertyReadable;

    /**
     * 初始化数据
     *
     * @param Throwable $exception
     */
    public function __construct(private Throwable $exception)
    {
    }
}
