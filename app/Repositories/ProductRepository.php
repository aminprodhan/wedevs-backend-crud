<?php
namespace App\Repositories;
use App\Models\Product;
use App\Traits\UploadAble;
use App\Contracts\ProductContract;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
class ProductRepository extends BaseRepository 
    implements ProductContract
{
    use UploadAble;
    public function __construct(Product $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
    public function listProducts(string $order = 'id', 
        string $sort = 'desc', array $columns = ['*'])
    {
        return $this->all($columns, $order, $sort);
    }
    public function findProductById(int $id)
    {
        try {
            return $this->findOneOrFail($id);
 
        } catch (ModelNotFoundException $e) {
 
            throw new ModelNotFoundException($e);
        }
 
    }
    public function createOrUpdateProduct(array $params)
    {
        try {
            $collection = collect($params);
            if($params["product_id"])
                {
                    $product = $this->findProductById($params['product_id']);
                    if(!$product)
                        return 0;
                }
            else 
                $product = new Product();
                if ($collection->has('image') && count($params['image']) > 0) 
                {
                    $base64Img=$params['image'][0]["thumbUrl"];
                    $base64_str = substr($base64Img, strpos($base64Img, ",")+1);
                    $file = base64_decode($base64_str);
                    $folderName = '/products/';
                    $random = \Str::random(25);
                    $extension = explode('/', mime_content_type($base64Img))[1];
                    $fileName=$random.".".$extension;
                    $image = $this->uploadOne($file, $folderName,$fileName);
                    $product->image=$fileName;
                    if($params["product_id"]){
                        $image = $this->deleteOne(public_path()."/products/".$product->image);
                    }
                }
            $product->title=$collection["title"];
            $product->description=$collection["description"];
            $product->price=$collection["price"];
            $product->save();
            return $product;
 
        } catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }
    public function updateProduct(array $params)
    {
        $product = $this->findProductById($params['product_id']);
        $collection = collect($params)->except('_token');
    }
    public function deleteProduct($id)
    {
        $product = $this->findProductById($id);
        if($product->image){
            $image = $this->deleteOne(public_path()."/products/".$product->image);
        }
        $product->delete();
        return $product;
    }
}