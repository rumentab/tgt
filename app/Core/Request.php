<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

declare(strict_types=1);

namespace App\Core;

use App\Core\Exception\Request\ParameterNotFoundException;
use App\Core\Request\QueryParser;
use App\Core\Request\RequestParser;
use App\Core\Request\ServerVariablesReader;
use ArrayObject;
use function urldecode;

final class Request
{
    /**
     * @var ArrayObject
     */
    private ArrayObject $serverVariables;

    /**
     * @var string
     */
    private string $requestedPath;

    /**
     * @var QueryParser
     */
    private QueryParser $query;

    /**
     * @var RequestParser
     */
    private RequestParser $request;

    /**
     * @var string|null
     */
    private ?string $requestMethod;

    /**
     * Request constructor.
     * @throws ParameterNotFoundException
     */
    public function __construct()
    {
        $this->serverVariables = ServerVariablesReader::getVars();
        $this->requestMethod = $this->serverVariables->offsetExists('REQUEST_METHOD') ?
            $this->serverVariables->offsetGet('REQUEST_METHOD') : null;
        $this->query = new QueryParser();
        $this->request = new RequestParser();
        $this->requestedPath = $this->query->has('path') ? urldecode($this->query->get('path')) : '/';
    }

    /**
     * @param ArrayObject $params
     */
    public function __invoke(ArrayObject $params)
    {
        $qp = &$this->query;
        $qp($params);
    }

    /**
     * @return ArrayObject
     */
    public function getServerVariables(): ArrayObject
    {
        return $this->serverVariables;
    }

    /**
     * @return string
     */
    public function getRequestedPath(): string
    {
        return $this->requestedPath;
    }

    /**
     * @return QueryParser
     */
    public function getQuery(): QueryParser
    {
        return $this->query;
    }

    /**
     * @return RequestParser
     */
    public function getRequest(): RequestParser
    {
        return $this->request;
    }

    /**
     * @return string
     */
    public function getRequestMethod(): string
    {
        return $this->requestMethod;
    }
}
