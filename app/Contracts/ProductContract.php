<?php
namespace App\Contracts;
interface ProductContract
{
    public function listProducts(string $order = 'id', string $sort = 'desc', array $columns = ['*']);
    public function findProductById(int $id);
    public function createOrUpdateProduct(array $params);
    public function updateProduct(array $params);
    public function deleteProduct($id);
}