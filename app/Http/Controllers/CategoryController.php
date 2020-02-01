<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
	/**
	 * Displays a listing of all categories.
	 */
	public function index()
	{
		return view('categories.categories');
	}

	public function getAll()
	{
		return response()->json(Category::all('id', 'title', 'description'));
	}

	/**
	 * Performs a case insensitive serach using
	 * the title to see if a model already
	 * exists in the cache
	 *
	 * @param string $title
	 * @return boolean True if it exists, flase otherwise
	 */
	private function categoryExists($title)
	{
		return Category::where('title', $title)->get()->isNotEmpty();
	}

	/**
	 * Creates a new category using the
	 * title and description
	 *
	 * @param Request $request object
	 * @param string $title The category title
	 * @param string $description The category description
	 * @return string A json object containting the id, title and description
	 * of the newly created category
	 */
	public function create(Request $request, $title, $description)
	{
		if($this->categoryExists($title)) {
			return response()->json([
				'error' => 'Category title already exists, please try a different one.',
			]);
		}

		$category              = new Category;
		$category->title       = $title;
		$category->description = $description;

		$category->save();

		return response()->json([
			'id'          => $category->id,
			'title'       => $category->title,
			'description' => $category->description,
		]);
	}


	/**
	 * Updates a category
	 *
	 * @param Request $request object
	 * @param Category $category model
	 * @return string A json object containting the id, title and description
	 * of the updated category or a string containing an error
	 */
	public function update(Request $request, Category $category)
	{
		$result     = $category::where('title', $request->title)->first();
		$request_id = intval($request->segments()[2]);

		if(!empty($result)) {
			// This means item belongs to another record and not itself
			if($request_id !== $result->id) {
				return response()->json([
					'error' => 'Category title already exists, please try a different one.',
				]);
			}
		}

		// This means we are updating row and not inserting dups
		$category->title       = $request->title;
		$category->description = $request->description;

		$category->save();

		return response()->json([
			'id'          => $category->id,
			'title'       => $category->title,
			'description' => $category->description,
		]);
	}

	/**
	 * Remove the specified model from the database
	 * and cache.
	 *
	 * @param Category  $category model
	 */
	public function destroy(Category $category)
	{
		$category->delete();
	}
}
