<?php
declare(strict_types=1);

namespace Shoot\Http\Tests;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Http\Message\ServerRequestInterface;
use Shoot\Http\Context;
use Shoot\Shoot\ContextInterface;

final class ContextTest extends TestCase
{
    /** @var ContextInterface|ServerRequestInterface */
    private $context;

    /**
     * @return void
     */
    protected function setUp()
    {
        $requestProphecy = $this->prophesize(ServerRequestInterface::class);
        $requestProphecy
            ->getAttribute('key', Argument::any())
            ->willReturn('value');
        $requestProphecy
            ->getQueryParams()
            ->willReturn(['key' => 'value']);

        /** @var ServerRequestInterface $request */
        $request = $requestProphecy->reveal();

        $this->context = new Context($request);
    }

    /**
     * @return void
     */
    public function testContextCanBeTypedAsRequestObject()
    {
        $this->assertArrayHasKey('key', $this->context->getQueryParams());
    }

    /**
     * @return void
     */
    public function testContextForwardsGetAttributeToRequestObject()
    {
        $this->assertSame('value', $this->context->getAttribute('key', ''));
    }
}
