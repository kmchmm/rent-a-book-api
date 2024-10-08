<?php


namespace App\Http\Controllers\Api;

use App\Models\Book; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;



class BookController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::paginate(3);

        if ($books->count() > 0) {
            return response()->json([
                'status' => 200,
                'books' => $books
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No records found'
            ], 404);
        }
    }

public function store(Request $request)
{
    
    $validator = Validator::make($request->all(), [
        'author' => 'required|string|max:191',
        'published' => 'required|string|max:191',
        'publisher' => 'required|string|max:191',
        'format' => 'required|string|max:191',
        'title' => 'nullable|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 422,
            'errors' => $validator->messages()
        ], 422);
    } else {
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('images', 'public');
        }

        $book = Book::create([
            'author' => $request->author,
            'published' => $request->published,
            'publisher' => $request->publisher,
            'format' => $request->format,
            'title' => $request->title,
            'image' => $imagePath, 
            'random_number_13' => $this->generateRandomNumber13(),
            'random_number_10' => $this->generateRandomNumber10(),
        ]);

        if ($book) {
            return response()->json([
                'status' => 200,
                'message' => "Book created successfully"
            ], 200);
        } else {
            return response()->json([
                'status' => 500,
                'message' => "Something went wrong"
            ], 500);
        }
    }
}
    private function generateRandomNumber13() {
        return '9' . str_pad(rand(0, 999999999999), 12, '0', STR_PAD_LEFT);
    }

    private function generateRandomNumber10() {
        return '0' . str_pad(rand(0, 999999999), 9, '0', STR_PAD_LEFT);
    }
    public function show($id){
        $book = Book::find($id);
        if($book){
            return response()->json([
                'status' => 200,
                'book' => $book
            ], 200); 
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No book found"
            ], 404); 
        }
    }

    public function edit($id){
        $book = Book::find($id);
        if($book){
            logger('Image path:', [$book->image]); 
            return response()->json([
                'status' => 200,
                'book' => $book
            ], 200); 
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No book found"
            ], 404); 
        }
    }



    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'author' => 'required|string|max:191',
            'published' => 'required|string|max:191',
            'publisher' => 'required|string|max:191',
            'format' => 'required|string|max:191',
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|string',  
            'random_number_13' => 'nullable|digits:13|regex:/^9\d{12}$/',
            'random_number_10' => 'nullable|digits:10|regex:/^0\d{9}$/',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }
    
        $book = Book::find($id);
    
        if ($book) {
            if ($request->has('image') && $request->image) {
                $imageData = $request->image;
                $fileName = uniqid() . '.png'; 
                Storage::disk('public')->put('images/' . $fileName, base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData)));
                $imagePath = 'images/' . $fileName;
            } else {
                $imagePath = $book->image;
            }
    
            $book->update([
                'author' => $request->author,
                'published' => $request->published,
                'publisher' => $request->publisher,
                'format' => $request->format,
                'title' => $request->title,
                'image' => $imagePath,  
                'random_number_13' => $request->random_number_13,
                'random_number_10' => $request->random_number_10,
            ]);
    
            return response()->json([
                'status' => 200,
                'message' => "Book updated successfully"
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No book found"
            ], 404);
        }
    }
    public function destroy($id)
    {
        $book = Book::find($id);

        if ($book) {
            $book->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Book deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No book found'
            ], 404);
        }
    }

    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->file('image')) {
            $file = $request->file('image');
            $path = $file->store('images', 'public'); 
            return response()->json(['imagePath' => Storage::url($path)]);
        }

        return response()->json(['message' => 'Image upload failed'], 500);
    }
}

