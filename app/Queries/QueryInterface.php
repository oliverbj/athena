<?php

namespace App\Queries;

interface QueryInterface
{
    public function execute(array $params = []): array;
    public function getDefaultParams(): array;
    public function getTitle(): string;
    public function transformResults(array $results): array;
}