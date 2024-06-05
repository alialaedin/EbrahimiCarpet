<?php

namespace Modules\Employee\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\Foundation\Application as App;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Modules\Core\Traits\BreadCrumb;
use Modules\Employee\Http\Requests\Admin\EmployeeStoreRequest;
use Modules\Employee\Http\Requests\Admin\EmployeeUpdateRequest;
use Modules\Employee\Models\Employee;

class EmployeeController extends Controller implements HasMiddleware
{
  public static function middleware(): array
  {
    return [
      new Middleware('can:view employees', ['index', 'show']),
      new Middleware('can:create employees', ['create', 'store']),
      new Middleware('can:edit employees', ['edit', 'update']),
      new Middleware('can:delete employees', ['destroy']),
    ];
  }

  public function index(): View|Application|Factory|App
  {
    $fullName = request('full_name');
    $mobile = request('mobile');
    $fromEmploymentAt = request('from_employmented_at');
    $toEmploymentAt = request('to_employmented_at');

    $employees = Employee::query()
      ->select('id', 'name', 'mobile', 'employmented_at', 'salary', 'national_code')
      ->when($fullName, fn(Builder $query) => $query->where('name', 'like', "%{$fullName}%"))
      ->when($mobile, fn(Builder $query) => $query->where('mobile', $mobile))
      ->when($fromEmploymentAt, fn(Builder $query) => $query->whereDate('employmented_at', '>=', $fromEmploymentAt))
      ->when($toEmploymentAt, fn(Builder $query) => $query->whereDate('employmented_at', '<=', $toEmploymentAt))
      ->latest('id')
      ->paginate(15)
      ->withQueryString();

    $totalEmployees = $employees->total();

    return view('employee::index', compact('employees', 'totalEmployees'));
  }

  public function show(Employee $employee): View|Application|Factory|App
  {
    return 'Employee Show';
    return view('employee::show', compact('employee'));
  }

  public function create(): View|Application|Factory|App
  {
    return view('employee::create', compact('breadcrumbItems'));
  }

  public function store(EmployeeStoreRequest $request): RedirectResponse
  {
    $employee = Employee::query()->create($request->validated());
    toastr()->success("کارمند جدید به نام {$employee->name} با موفقیت ساخته شد.");

    return to_route('admin.employees.index');
  }

  public function edit(Employee $employee): View|Application|Factory
  {
    return view('employee::edit', compact('employee'));
  }

  public function update(EmployeeUpdateRequest $request, Employee $employee): RedirectResponse
  {
    $employee->update($request->validated());
    toastr()->success("کارمند با نام {$employee->name} با موفقیت ویرایش شد.");

    return redirect()->back()->withInput();
  }

  public function destroy(Employee $employee): RedirectResponse
  {
    $employee->delete();
    toastr()->success("کارمند با نام {$employee->name} با موفقیت حذف شد.");

    return redirect()->back();
  }
}
