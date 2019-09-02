<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Core;

use App\Core\Exception\Request\ParameterNotFoundException;
use App\Core\Request\QueryParser;
use App\Core\Request\RequestParser;
use App\Core\Request\ServerVariablesReader;

final class Request
{
    /**
     * @var \ArrayObject
     */
    private $server_variables;

    /**
     * @var string
     */
    private $requested_path;

    /**
     * @var QueryParser
     */
    private $query;

    /**
     * @var RequestParser
     */
    private $request;

    /**
     * @var string
     */
    private $request_method;

    /**
     * Request constructor.
     * @throws ParameterNotFoundException
     */
    public function __construct()
    {
        $this->server_variables = ServerVariablesReader::getVars();
        $this->request_method = $this->server_variables->offsetExists('REQUEST_METHOD') ?
            $this->server_variables->offsetGet('REQUEST_METHOD') : null;
        $this->query = new QueryParser();
        $this->request = new RequestParser();
        $this->requested_path = $this->query->has('path') ? \urldecode($this->query->get('path')) : '/';
    }

    /**
     * @param \ArrayObject $params
     */
    public function __invoke(\ArrayObject $params)
    {
        $qp = &$this->query;
        $qp($params);
    }

    /**
     * @return \ArrayObject
     */
    public function getServerVariables(): \ArrayObject
    {
        return $this->server_variables;
    }

    /**
     * @return string
     */
    public function getRequestedPath(): string
    {
        return $this->requested_path;
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
        return $this->request_method;
    }
}
