<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    // 1. GET /api/books
    public function index() {
        $books = Book::all();
        return response()->json([
            'status' => true,
            'message' => 'Daftar data buku',
            'data' => $books
        ], 200);
    }

    // 2. POST /api/books
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'author' => 'required',
            'year' => 'required|integer|min:1900',
            'stock' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $book = Book::create($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Buku berhasil ditambahkan',
            'data' => $book
        ], 201);
    }

    // 3. GET /api/books/{id}
    public function show($id) {
        $book = Book::find($id);
        if ($book) {
            return response()->json([
                'status' => true,
                'message' => 'Detail data buku',
                'data' => $book
            ], 200);
        }
        return response()->json([
            'status' => false,
            'message' => 'Data tidak ditemukan',
            'data' => null
        ], 404);
    }

    // 4. PUT /api/books/{id}
    public function update(Request $request, $id) {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan', 'data' => null], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'author' => 'required',
            'year' => 'required|integer|min:1900',
            'stock' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
        }

        $book->update($request->all());
        return response()->json(['status' => true, 'message' => 'Buku berhasil diperbarui', 'data' => $book], 200);
    }

    // 5. DELETE /api/books/{id}
    public function destroy($id) {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan', 'data' => null], 404);
        }

        $book->delete();
        return response()->json(['status' => true, 'message' => 'Buku berhasil dihapus', 'data' => null], 200);
    }
}
