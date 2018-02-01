<?php
declare(strict_types=1);

namespace Shoot\Http\Tests;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\MethodProphecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Shoot\Http\Context;
use Shoot\Http\Middleware;
use Shoot\Shoot\PipelineInterface;

final class MiddlewareTest extends TestCase
{
    /** @var RequestHandlerInterface */
    private $handler;

    /** @var ServerRequestInterface */
    private $request;

    /** @var ResponseInterface */
    private $response;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->request = $this->prophesize(ServerRequestInterface::class)->reveal();
        $this->response = $this->prophesize(ResponseInterface::class)->reveal();

        $handler = $this->prophesize(RequestHandlerInterface::class);
        $handler
            ->handle(Argument::type(ServerRequestInterface::class))
            ->willReturn($this->response);

        $this->handler = $handler->reveal();
    }

    /**
     * @return void
     */
    public function testHttpContextShouldBeSetOnPipeline()
    {
        $pipelineProphecy = $this->prophesize(PipelineInterface::class);

        /** @var MethodProphecy $withContextMethod */
        $withContextMethod = $pipelineProphecy
            ->withContext(Argument::type(Context::class), Argument::type('callable'))
            ->willReturn($this->response);

        /** @var PipelineInterface $pipeline */
        $pipeline = $pipelineProphecy->reveal();

        $middleware = new Middleware($pipeline);
        $middleware->process($this->request, $this->handler);

        $withContextMethod->shouldHaveBeenCalled();
    }
}
