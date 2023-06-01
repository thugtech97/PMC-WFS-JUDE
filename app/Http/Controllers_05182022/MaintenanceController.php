<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use App\AllowedTransaction;
use App\Template;

class MaintenanceController extends Controller
{



// Applications
	public function transaction_index()
	{
		$apps = AllowedTransaction::all();

		return view('maintenance.transactions.index',compact('apps'));
	}

	public function transaction_create()
	{
		$templates = Template::all();

		return view('maintenance.transactions.create',compact('templates'));
	}

	public function transaction_store(Request $req)
	{
		$app = AllowedTransaction::create([
			'name' => $req->name,
			'token' => $req->token,
			'template_id' => $req->temp_id
		]);

		return redirect(route('allowed-transactions.index'))->with('successMsg', ' Application has been added to allowed apps.');
	}

	public function transaction_edit($id)
	{
		$templates = Template::all();
		$data = AllowedTransaction::find($id);

		return view('maintenance.transactions.edit',compact('data','templates'));
	}

	public function transaction_update(Request $req)
	{
		AllowedTransaction::find($req->id)->update([
			'name' => $req->name,
			'token' => $req->token,
			'template_id' => $req->temp_id,
			'update_at' => Carbon::now()
		]);

		return redirect(route('allowed-transactions.index'))->with('successMsg', ' Application details has been updated.');
	}

	public function app_remove(Request $request)
    {
        $app = AllowedTransaction::find($request->id);
        $app->delete();

        return response()->json();
    }

}
