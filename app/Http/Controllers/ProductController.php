<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\PaginatedProductsRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\Request;
use App\Models\product;
use App\Models\category;

class ProductController extends Controller
{
    public function __construct(private ProductService $ProductService)
    {
        // Middleware or other initializations can be done here
    }

    public function store(CreateProductRequest $request)
    {
        $productData = $request->validated();

        try {
            $newProduct = $this->ProductService->addNewProduct($productData);
            if (request()->expectsJson())
                return response()->json(['message' => 'Product stored successfully', 'data' => $newProduct]);
            else
                return redirect()->route('admin.Product.show', $newProduct->id);
        } catch (\Exception $e) {
            if (request()->expectsJson())
                return response()->json(['message' => 'Error creating Product', 'error' => $e->getMessage()], 500);
            else
                return redirect()->back()->with(['message' => 'Error creating Product', 'error' => $e->getMessage()])->withInput();
        }
    }


    public function all()
    {
        try {
            $products = $this->productService->all();
            $count = $this->productService->getCount();
            return response()->json(['message' => 'All products fetched successfully', 'data' => $products, 'count' => $count]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching products', 'error' => $e->getMessage()], 500);
        }
    }

    public function pagination(PaginatedProductsRequest $request, ?string $sort_direction='asc', ?string $sort_by='created_at', ?int $page=-1, ?int $per_page=-1)
    {
        try {
            $products = $this->productService->paginateProducts($request->validated(), $sort_direction, $sort_by, $page, $per_page);
            return response()->json(['message' => 'Products fetched successfully', 'data' => $products['data'], 'count' => $products['count']]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching products', 'error' => $e->getMessage()], 500);
        }
    }

    public function findById(int $id)
    {
        try {
            $product = $this->productService->getProductDetails($id);
            return response()->json(['message' => 'Product fetched successfully', 'data' => $product]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching product', 'error' => $e->getMessage()], 500);
        }
    }

    public function edit(int $id)
    {
        $product = $this->productService->getProductDetails($id);
        $data = $this->getDropDownData() + ['product' => $this->toRecursiveArray($product)];
        return view('pages.editProduct', $data);
    }


    public function update(int $id, UpdateProductRequest $request)
    {
        $updatedProductData = $request->validated();
        try {
            $updatedProduct = $this->productService->updateProduct($id, $updatedProductData);
            if (request()->expectsJson())
                return response()->json(['message' => 'Product Updated successfully', 'data' => $updatedProduct]);
            else
                return redirect()->route('admin.products')->with('success', 'Product Updated successfully');
        } catch (\Exception $e) {
            if (request()->expectsJson())
                return response()->json(['message' => 'Error Update product', 'error' => $e->getMessage()], 500);
            else
                return redirect()->route('admin.products')->with('error', $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->productService->deleteProduct($id);
            return redirect()->route('admin.products')->with('success', 'Product deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.products')->with('error', $e->getMessage());
        }
    }

    public function add()
    {
        return view('pages.addProduct', $this->getDropDownData());
    }

    public function getDropDownData() : array
    {
        return [
            'categories' => category::all()
        ];
    }

    public function show(int $id)
    {
        try {
            $product = $this->productService->getProductDetails($id);
            return view('pages.showProduct', ['product' => $this->toRecursiveArray($product)]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => 'Error fetching product', 'error' => $e->getMessage()]);
        }
    }

    public function index()
    {
        $paginatedProducts = product::with(['category', 'images'])->latest()->paginate(10);

        $productResources = ProductResource::collection($paginatedProducts);

        $transformedProducts = $productResources->getCollection()->map(function ($product) {
            return $this->toRecursiveArray($product);
        });

        $productResources->setCollection($transformedProducts);

        return view('pages.products', ['products' => $productResources]);
    }

    public function toRecursiveArray(ProductResource $product)
    {
        $productArray = $product->toArray(request());
        $productArray['images'] = $productArray['images']->toArray(request());

        return $productArray;
    }

    public function myProducts(PaginatedProductsRequest $request, ?string $sort_direction='asc', ?string $sort_by='created_at', ?int $page=-1, ?int $per_page=-1)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $request->merge(['owner_id' => $user->id]);
        return $this->pagination($request, $sort_direction, $sort_by, $page, $per_page);
    }
}