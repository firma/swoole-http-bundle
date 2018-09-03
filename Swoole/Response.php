<?php
/**
 * Created by Date: 2018/9/3
 */
declare(strict_types=1);

namespace Firma\Bundle\SwooleBundle\Swoole;

use Swoole\Http\Response as SwooleResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class Response
{
    /**
     * @param SwooleResponse $response
     * @param SymfonyResponse $symfonyResponse
     */
    public static function toSwoole(SwooleResponse $response, SymfonyResponse $symfonyResponse): void
    {
        // headers have already been sent by the developer
        if (headers_sent()) {
            return;
        }
        $allHeadersWithoutCookies = $symfonyResponse->headers->allPreserveCaseWithoutCookies();
        // headers
        foreach ($allHeadersWithoutCookies as $name => $values) {
            foreach ($values as $value) {
                $response->header((string) $name, (string) $value);
            }
        }
        // status
        $response->status($symfonyResponse->getStatusCode());
        // cookies
        foreach ($symfonyResponse->headers->getCookies() as $cookie) {
            $response->cookie(
                $cookie->getName(),
                $cookie->getValue(),
                $cookie->getExpiresTime(),
                $cookie->getPath(),
                $cookie->getDomain(),
                $cookie->isSecure(),
                $cookie->isHttpOnly()
            );
        }
        $response->end($symfonyResponse->getContent());
    }
}
