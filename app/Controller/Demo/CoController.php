<?php

namespace App\Controller\Demo;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\Context;

/**
 * @AutoController()
 */
class CoController
{
    /**
     * @Inject
     * @var RequestInterface
     */
    private $request;

    public $foo = 1;

    public $id;

    /**
     * DI管理长生命周期的对象，即单例，且协程下，一个进程会同一时间周期内处理多个请求
     * 所有由DI管理的对象内部成员属性，去设置任何跟请求或跟协程相关的状态数据，这些状态数据应该通过协程上下文处理
     * 所以所有的状态值 都要存储到协程上下文。
     */
    public function error()
    {
        $this->id = $this->request->input('id');
        return $this->id;
    }

    /**
     * 需要把worker_num设置为1 getError和updateError不是独立的请求 是相互混淆的请求
     */
    public function getError()
    {
        return $this->foo;
    }
    public function updateError()
    {
        $foo       = $this->request->input('foo');
        $this->foo = $foo;

        return $this->foo;
    }


    public function getRight()
    {
        return Context::get('name', 'swoole');
    }
    public function updateRight()
    {
        $name = $this->request->input('name');
        Context::set('name', 'hyperf');
        return Context::get('name');
    }
}
