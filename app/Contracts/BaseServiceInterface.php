<?php

namespace App\Contracts;

interface BaseServiceInterface
{
    public function getAll();

    public function getPaginated(int $perPage = 15);

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function restore($id);

    public function bulkDelete(array $ids);

    public function export(string $format = 'csv');

    public function import(array $data);
}
