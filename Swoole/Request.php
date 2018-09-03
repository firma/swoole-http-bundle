<?php
/**
 * Created by Date: 2018/9/3
 */
declare(strict_types=1);

namespace Firma\Bundle\SwooleBundle\Swoole;

use Swoole\Http\Request as SwooleRequest;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Request
{
    /**
     * @param \Swoole\Http\Request $request
     *
     * @return SymfonyRequest
     */
    public static function toSymfony(SwooleRequest $request): SymfonyRequest
    {
        $headers = [];
        foreach ($request->header as $key => $value) {
            if ($key == 'x-forwarded-proto' && $value == 'https') {
                $request->server['HTTPS'] = 'on';
            }
            $headerKey           = 'HTTP_' . strtoupper(str_replace('-', '_', $key));
            $headers[$headerKey] = $value;
        }
        $_SERVER        = array_change_key_case(array_merge($request->server, $headers), CASE_UPPER);
        $_GET           = $request->get ?? [];
        $_POST          = $request->post ?? [];
        $_COOKIE        = $request->cookie ?? [];
        $_FILES         = $request->files ?? [];
        $content        = $request->rawContent() ? : null;
        $symfonyRequest = new SymfonyRequest(
            $_GET,
            $_POST,
            [],
            $_COOKIE,
            $_FILES,
            $_SERVER,
            $content
        );
        if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? false) {
            $symfonyRequest::setTrustedProxies(explode(',', $trustedProxies), SymfonyRequest::HEADER_X_FORWARDED_ALL ^ SymfonyRequest::HEADER_X_FORWARDED_HOST);
        }
        if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? false) {
            $symfonyRequest::setTrustedHosts(explode(',', $trustedHosts));
        }
        return $symfonyRequest;
    }
}
