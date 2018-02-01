<?php
declare(strict_types=1);

namespace Shoot\Http;

use Psr\Http\Message\ServerRequestInterface;
use Shoot\Shoot\ContextInterface;

final class Context implements ContextInterface
{
    /** @var ServerRequestInterface */
    private $request;

    /**
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @param string $name    The name of the attribute.
     * @param mixed  $default A default value is the attribute does not exist.
     *
     * @return mixed The value of the attribute, or the default if it does not exist.
     */
    public function getAttribute(string $name, $default = null)
    {
        return $this->request->getAttribute($name, $default);
    }

    /**
     * @param string  $name      The name of the method being called.
     * @param mixed[] $arguments The parameters passed to the method.
     *
     * @return mixed
     */
    public function __call(string $name, array $arguments = [])
    {
        return call_user_func_array([$this->request, $name], $arguments);
    }
}
