<?php

namespace App\Contracts;

interface RepositoryInterface
{
    public function getAll(array $columns = ['*']);

    public function getPaginated(int $perPage = 15, array $columns = ['*']);

    public function getById($id, array $columns = ['*']);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function findByField(string $field, $value, array $columns = ['*']);

    public function findWhere(array $conditions, array $columns = ['*']);

    public function count(array $conditions = []);

    public function pluck(string $value, string $key = null);

    public function bulkDelete(array $ids);

    public function bulkInsert(array $data);

    public function firstOrCreate(array $attributes, array $values = []);

    public function updateOrCreate(array $attributes, array $values = []);

    public function with(array $relations);

    public function orderBy(string $column, string $direction = 'asc');
}
