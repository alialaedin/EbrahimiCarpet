<?php

namespace Modules\Accounting\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Modules\Accounting\Http\Requests\Admin\Salary\SalaryStoreRequest;
use Modules\Accounting\Http\Requests\Admin\Salary\SalaryUpdateRequest;
use Modules\Accounting\Models\Salary;
use Modules\Employee\Models\Employee;
use Illuminate\Database\Eloquent\Collection;

class SalaryController extends Controller implements HasMiddleware
{
  public static function middleware(): array
  {
    return [
      new Middleware('can:view salaries', ['index']),
      new Middleware('can:create salaries', ['create', 'store']),
      new Middleware('can:edit salaries', ['edit', 'update']),
      new Middleware('can:delete salaries', ['destroy']),
    ];
  }

  public function index(): View
  {
    $employeeId = request('employee_id');
    $fromPaymentDate = request('from_payment_date');
    $toPaymentDate = request('to_payment_date');

    $salaries = Salary::query()
      ->select('id', 'employee_id', 'amount', 'overtime','payment_date', 'receipt_image', 'created_at')
      ->when($employeeId, fn(Builder $query) => $query->where('employee_id', $employeeId))
      ->when($fromPaymentDate, fn(Builder $query) => $query->whereDate('payment_date', '>=', $fromPaymentDate))
      ->when($toPaymentDate, fn(Builder $query) => $query->whereDate('payment_date', '<=', $toPaymentDate))
      ->with(['employee' => fn($query) => $query->select('id', 'name', 'mobile')])
      ->latest('id')
      ->paginate()
      ->withQueryString();

    $totalSalaries = $salaries->total();
    $employees = $this->getEmployees();

    return view('accounting::salary.index', compact(['salaries', 'totalSalaries', 'employees']));
  }

  public function create(): View
  {
    $employees = $this->getEmployees();

    return view('accounting::salary.create', compact('employees'));
  }

  public function store(SalaryStoreRequest $request): RedirectResponse
  {
    $inputs = $this->getFormInputs($request);

    if ($request->hasFile('receipt_image') && $request->file('receipt_image')->isValid()) {
      $inputs['receipt_image'] = $request->file('receipt_image')->store('images/employee-salaries', 'public');
    }

    Salary::query()->create($inputs);

    return to_route('admin.salaries.index');
  }

  public function edit(Salary $salary): View
  {
    $employees = $this->getEmployees();

    return view('accounting::salary.edit', compact(['salary', 'employees']));
  }

  public function update(SalaryUpdateRequest $request, Salary $salary): RedirectResponse
  {
    $inputs = $this->getFormInputs($request);

    if ($request->hasFile('receipt_image') && $request->file('receipt_image')->isValid()) {
      if (!is_null($salary->receipt_image)) {
        Storage::delete($salary->receipt_image);
      }
      $inputs['receipt_image'] = $request->file('receipt_image')->store('images/employee-salaries', 'public');
    }

    $salary->update($inputs);

    return to_route('admin.payments.index', $payment->supplier);
  }

  public function destroyImage(Salary $salary): RedirectResponse
  {
    Storage::disk('public')->delete($salary->receipt_image);
    $salary->receipt_image = null;
    $salary->save();
    toastr()->success("عکس با موفقیت حذف شد.");

    return redirect()->back();
  }

  public function destroy(Salary $salary): RedirectResponse
  {
    $salary->delete();

    return redirect()->back();
  }

  public function getEmployeeSalary(Request $request): JsonResponse
  {
    $employee = Employee::query()->findOrFail($request->input('employee_id', ['id', 'salary']));

    return response()->json(number_format($employee->salary));
  }

  private function getEmployees(): Collection
  {
    return Employee::query()
      ->select('id', 'name', 'mobile')
      ->orderBy('name')
      ->get();
  }

  private function getFormInputs(Request $request): array
  {
    return [
      'employee_id' => $request->input('employee_id'),
      'amount' => $request->input('amount'),
      'overtime' => $request->input('overtime'),
      'payment_date' => $request->input('payment_date'),
      'description' => $request->input('description'),
    ];
  }
}
