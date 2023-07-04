<?php

namespace App\Http\Controllers;

use App\Models\Subdistrict;
use App\Http\Controllers\Controller;
use App\Http\Requests\DataTableRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class SubdistrictController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return Inertia::render('Subdistrict/Index');
	}

	/**
	 * @param \App\Http\Requests\DataTableRequest $request
	 * @return \Illuminate\Http\Response
	 */
	public function paginate(DataTableRequest $request)
	{
		$request->validated();

		return Subdistrict::where(function (Builder $query) use ($request) {
						$search = '%' . $request->search . '%';

						$query->orWhere('name', 'like', $search)
									->orWhere('district', 'like', $search)
									->orWhere('city', 'like', $search);
					})
					->orderBy($request->input('order.key') ?: 'name', $request->input('order.dir') ?: 'asc')
					->paginate($request->per_page ?: 10);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$validated = $request->validate([
			'name' => 'required|string|unique:subdistricts',
			'district' => 'required|string',
			'city' => 'required|string',
		]);

		DB::beginTransaction();

		try {
			Subdistrict::create($validated);
			
			DB::commit();

			return redirect()->back()->with('success', sprintf('Kelurahan dengan nama `%s` berhasil dibuat', $validated['name']));
		} catch (\Exception $e) {
			DB::rollBack();

			return redirect()->back()->with('error', 'Pembuatan data kelurahan gagal. Silahkan coba lagi.');
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\subdistrict $subdistrict
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, subdistrict $subdistrict)
	{
		$validated = $request->validate([
			'name' => ['required', 'string', Rule::unique('subdistricts')->ignore($subdistrict->id)],
			'district' => 'required|string',
			'city' => 'required|string',
		]);

		DB::beginTransaction();

		try {
			$subdistrict->update($validated);
			
			DB::commit();

			return redirect()->back()->with('success', sprintf('Data kelurahan berhasil diperbarui.'));
		} catch (\Exception $e) {
			DB::rollBack();

			return redirect()->back()->with('error', 'Pembaruan data kelurahan gagal. Silahkan coba lagi.');
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\subdistrict $subdistrict
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(subdistrict $subdistrict)
	{
		DB::beginTransaction();

		try {
			$subdistrict->delete();
			
			DB::commit();

			return redirect()->back()->with('success', sprintf('Data kelurahan `%s` berhasil dihapus.', $subdistrict->name));
		} catch (\Exception $e) {
			DB::rollBack();

			return redirect()->back()->with('error', 'Penghapusan data kelurahan gagal. Silahkan coba lagi.');
		}
	}

}