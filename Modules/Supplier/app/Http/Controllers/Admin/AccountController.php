<?php

namespace Modules\Supplier\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Supplier\Http\Requests\Admin\Account\AccountStoreRequest;
use Modules\Supplier\Http\Requests\Admin\Account\AccountUpdateRequest;
use Modules\Supplier\Models\Account;
use Modules\Supplier\Models\Supplier;

class AccountController extends Controller
{
  public static function middleware(): array
  {
    return [
      new Middleware('can:view accounts', ['index']),
      new Middleware('can:create accounts', ['store']),
      new Middleware('can:edit accounts', ['update']),
      new Middleware('can:delete accounts', ['destroy']),
    ];
  }

  public function index(): View
  {
    $accounts = Account::query()
      ->select('id', 'supplier_id', 'bank_name', 'account_number', 'card_number')
      ->with('supplier:id,name,mobile')
      ->latest('id')
      ->paginate()
      ->withQueryString();

    $totalAccounts = $accounts->total();
    $suppliers = Supplier::all('id', 'name', 'mobile');

    return view('supplier::account.index', compact(['accounts', 'totalAccounts', 'suppliers']));
  }

  public function store(AccountStoreRequest $request): RedirectResponse
  {
    Account::query()->create($request->validated());

    return redirect()->back();
  }

  public function update(AccountUpdateRequest $request, Account $account): RedirectResponse
  {
    $account->update($request->validated());

    return redirect()->back();
  }

  public function destroy(Account $account): RedirectResponse
  {
    $account->delete();

    return redirect()->back();
  }
}
