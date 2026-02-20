<?php
namespace App\Http\Controllers\Operator;

use App\Enums\OperatorAuthStatus;
use App\Enums\OperatorType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Bus\OperatorStoreRequest;
use App\Services\Bus\OperatorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct(private OperatorService $operatorService)
    {
    }

    public function create(): View
    {
        return view('operator.auth.login');
    }

    public function register(OperatorStoreRequest $request): RedirectResponse
    {
        $this->operatorService->store([
            'name'        => $request->name,
            'phone'       => $request->phone,
            'pin'         => $request->register_pin,
            'type'        => $request->type,
            'auth_status' => OperatorAuthStatus::PENDING,
        ]);

        return redirect()->route('operator.login')->with('success', 'You have been registered successfully. Please login to continue.');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['pin' => 'required', 'phone' => 'required']);

        $operator = $this->operatorService->find(['phone' => $request->phone, 'pin' => $request->pin, 'type' => OperatorType::PRIVATE]);

        if (! $operator) {
            return back()->withErrors(['email' => 'Invalid credentials']);
        }

        // Direct login (no password)
        Auth::guard('operator')->login($operator);

        return redirect()->route('operator.dashboard');
    }

    public function dashboard(): View
    {
        return view('operator.dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('operator')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('operator.login');
    }
}
