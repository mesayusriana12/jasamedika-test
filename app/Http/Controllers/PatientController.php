<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Http\Controllers\Controller;
use App\Http\Requests\DataTableRequest;
use App\Models\Subdistrict;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PatientController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {
    return Inertia::render('Patient/Index', [
			'subdistricts' => Subdistrict::get(['id', 'name', 'district', 'city']),
		]);
  }
  
  /**
	 * @param \App\Http\Requests\DataTableRequest $request
	 * @return \Illuminate\Http\Response
	 */
	public function paginate(DataTableRequest $request)
	{
		$request->validated();

		return Patient::where(function (Builder $query) use ($request) {
						$search = '%' . $request->search . '%';

            $model = new Patient();

            foreach ($model->getFillable() as $key) {
              $query->orWhere($key, 'like', $search);
            }

            $query->orWhereRelation('subdistrict', function (Builder $query) use ($search) {
              $query->where('name', 'like', $search);
            });

					})
					->orderBy($request->input('order.key') ?: 'name', $request->input('order.dir') ?: 'asc')
					->paginate($request->per_page ?: 10);
	}

  /**
   * Generate ID for new patient
   */
  private function generateId()
  {
    $count = Patient::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();

    return sprintf('%s%s', date('ym'), str_pad($count + 1, 6, '0', STR_PAD_LEFT));
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
      'name' => 'required|string',
      'phone' => 'required|string',
      'gender' => 'required|string|in:L,P',
      'birth_date' => 'required|date',
      'address' => 'required|string',
      'rt' => 'required|integer',
      'rw' => 'required|integer',
      'subdistrict_id' => 'required|exists:subdistricts,id',
		]);

		DB::beginTransaction();

		try {
      $validated['id'] = $this->generateId();
			Patient::create($validated);
			
			DB::commit();

			return redirect()->back()->with('success', sprintf('Pasien dengan nama `%s` berhasil diregistrasi', $validated['name']));
		} catch (\Exception $e) {
			DB::rollBack();

			return redirect()->back()->with('error', 'Registrasi data pasien gagal. Silahkan coba lagi.');
		}
  }
  
  /**
  * Display the specified resource.
  *
  * @param  \App\Models\Patient  $patient
  * @return \Illuminate\Http\Response
  */
  public function show(Patient $patient)
  {
    //
  }
  
  /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  \App\Models\Patient  $patient
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, Patient $patient)
  {
    $validated = $request->validate([
      'name' => 'required|string',
      'phone' => 'required|string',
      'gender' => 'required|string|in:L,P',
      'birth_date' => 'required|date',
      'address' => 'required|string',
      'rt' => 'required|integer',
      'rw' => 'required|integer',
      'subdistrict_id' => 'required|exists:subdistricts,id',
		]);

		DB::beginTransaction();

		try {
      $patient->update($validated);
			
			DB::commit();

			return redirect()->back()->with('success', sprintf('Pasien dengan nama `%s` berhasil diperbarui', $validated['name']));
		} catch (\Exception $e) {
			DB::rollBack();

			return redirect()->back()->with('error', 'Pembaruan registrasi data pasien gagal. Silahkan coba lagi.');
		}
  }
  
  /**
  * Remove the specified resource from storage.
  *
  * @param  \App\Models\Patient  $patient
  * @return \Illuminate\Http\Response
  */
  public function destroy(Patient $patient)
  {
    DB::beginTransaction();

		try {
			$patient->delete();
			
			DB::commit();

			return redirect()->back()->with('success', sprintf('Data registrasi pasien `%s` berhasil dihapus.', $patient->name));
		} catch (\Exception $e) {
			DB::rollBack();

			return redirect()->back()->with('error', 'Penghapusan data registrasi pasien gagal. Silahkan coba lagi.');
		}
  }
}
