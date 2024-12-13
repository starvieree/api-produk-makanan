<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="API Dokumentasi Produk",
 *     version="1.0.0",
 *     description="Dokumentasi API untuk pengelolaan produk.",
 *     @OA\Contact(
 *         email="ahmadputrafirdaus@example.com"
 *     )
 * )
 */

class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/products",
     *     summary="Daftar produk",
     *     description="Mengambil daftar semua produk atau mencari produk berdasarkan nama.",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Nama produk untuk pencarian",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Daftar produk berhasil diambil"
     *     )
     * )
     */


    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        $products = $query->get();

        return response()->json($products, 200);
    }

    /**
     * Menyimpan produk baru.
     *
     * @OA\Post(
     *     path="/products",
     *     summary="Tambah produk",
     *     description="Menyimpan produk baru ke database.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Produk Baru"),
     *             @OA\Property(property="description", type="string", example="Deskripsi produk"),
     *             @OA\Property(property="price", type="number", format="float", example=10000),
     *             @OA\Property(property="stock", type="number", format="int", example=20)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Produk berhasil disimpan"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0'
        ]);

        $product = Product::create($validated);
        return response()->json($product, 201);
    }

    /**
     * Menampilkan detail produk.
     *
     * @OA\Get(
     *     path="/products/{id}",
     *     summary="Detail produk",
     *     description="Mengambil detail produk berdasarkan ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID produk"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detail produk berhasil diambil"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produk tidak ditemukan"
     *     )
     * )
     */
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        return response()->json($product, 200);
    }

    /**
     * Mengupdate data produk.
     *
     * @OA\Put(
     *     path="/products/{id}",
     *     summary="Update produk",
     *     description="Mengupdate data produk berdasarkan ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID produk"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Produk Baru"),
     *             @OA\Property(property="description", type="string", example="Deskripsi produk"),
     *             @OA\Property(property="price", type="number", format="float", example=12000)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produk berhasil diupdate"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produk tidak ditemukan"
     *     )
     * )
     */


    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        $product->update($validated);
        return response()->json($product, 200);
    }

    /**
     * Menghapus produk.
     *
     * @OA\Delete(
     *     path="/products/{id}",
     *     summary="Hapus produk",
     *     description="Menghapus produk berdasarkan ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID produk"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produk berhasil dihapus"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produk tidak ditemukan"
     *     )
     * )
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $product->delete();
        return response()->json(['message' => 'Produk berhasil dihapus'], 200);
    }
}
