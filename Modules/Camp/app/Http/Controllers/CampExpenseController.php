<?php

declare(strict_types=1);

namespace Modules\Camp\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Camp\Models\CampExpense;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class CampExpenseController extends Controller
{
    use AuthorizesRequests;

    public function downloadReceipt(CampExpense $expense): StreamedResponse
    {
        $this->authorize('view', $expense);

        if (! $expense->receipt) {
            abort(404);
        }

        return Storage::disk('local')->download($expense->receipt);
    }
}
