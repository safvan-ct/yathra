<?php
namespace App\Http\Controllers\Operator;

use App\Enums\OperatorType;
use App\Http\Controllers\Controller;
use App\Models\Operator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('operator.auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'pin'           => 'required',
            'mobile_number' => 'required',
        ]);

        $operator = Operator::where(['phone' => $request->mobile_number, 'pin' => $request->pin, 'type' => OperatorType::PRIVATE])->first();

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
