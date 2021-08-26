<?php

namespace Framework\Http;

class Request
{
    private $queryParams;
    private $parseBody;

    public function __construct(array $queryParams = [], $parseBody = null)
    {
        $this->queryParams = $queryParams;
        $this->parseBody = $parseBody;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function withQueryParams(array $query): self
    {
        $new = clone $this;
        $new->queryParams = $query;
        return $new;
    }

    public function getParsedBody()
    {
        return $this->parseBody;
    }

    public function withParsedBody($data): self
    {
        $new = clone $this;
        $new->parseBody = $data;
        return $new;
    }
}