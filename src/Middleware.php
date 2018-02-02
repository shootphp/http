<?php
declare(strict_types=1);

namespace Shoot\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Shoot\Shoot\PipelineInterface;

final class Middleware implements MiddlewareInterface
{
    /** @var PipelineInterface */
    private $pipeline;

    /**
     * @param PipelineInterface $pipeline
     */
    public function __construct(PipelineInterface $pipeline)
    {
        $this->pipeline = $pipeline;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->pipeline->withContext($request, function () use ($request, $handler): ResponseInterface {
            return $handler->handle($request);
        });
    }
}
