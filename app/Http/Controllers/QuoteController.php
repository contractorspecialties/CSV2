<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\Customer;
use App\Models\PricebookItem;
use App\Services\TwilioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class QuoteController extends Controller
{
    protected TwilioService $twilio;

    public function __construct(TwilioService $twilio)
    {
        $this->twilio = $twilio;
    }

    /**
     * View all ongoing project estimates and pipeline metrics.
     */
    public function index()
    {
        $quotes = Quote::where('company_id', Auth::user()->company_id)
            ->with('customer')
            ->latest()
            ->get();

        return view('estimates.index', compact('quotes'));
    }

    /**
     * Display the new project estimate build form canvas.
     */
    public function create(Request $request)
    {
        $companyId = Auth::user()->company_id;

        $customers = Customer::where('company_id', $companyId)->orderBy('last_name')->get();
        $pricebookItems = PricebookItem::where('company_id', $companyId)->orderBy('name')->get();

        if ($customers->isEmpty()) {
            return redirect()->route('customers.create')->with('error', '🛑 Please register a customer profile before creating job estimates.');
        }

        $preselectedCustomerId = $request->query('customer_id');

        return view('estimates.create', compact('customers', 'pricebookItems', 'preselectedCustomerId'));
    }

    /**
     * Process creation form array data and commit rows to standard prefix scopes.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|integer',
            'notes' => 'nullable|string',

            // Grid Row Arrays
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.save_to_pricebook' => 'nullable|boolean',

            // Continuous Service Agreement Loop Flags
            'is_recurring' => 'nullable|boolean',
            'recurrence_interval' => 'nullable|string|in:weekly,bi_weekly,monthly',
            'recurrence_cycles' => 'nullable|integer|min:1',

            // Financial Rules Matrix
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'require_deposit' => 'nullable|boolean',
            'deposit_amount' => 'nullable|numeric|min:0',
        ]);

        $customer = Customer::where('company_id', Auth::user()->company_id)->findOrFail($validated['customer_id']);

        $quote = Quote::create([
            'company_id' => Auth::user()->company_id,
            'client_id' => $customer->id, // Maps smoothly to base migration lines
            'status' => 'draft',
            'view_token' => Str::random(40), // Unique homeowner token link key
            'notes' => $validated['notes'],
            'total_amount' => 0.00,

            // Service Agreement Loops
            'is_recurring' => $request->has('is_recurring'),
            'recurrence_interval' => $request->input('recurrence_interval'),
            'recurrence_cycles' => $request->input('recurrence_cycles'),

            // Taxes & Downpayments
            'tax_rate' => $validated['tax_rate'] ?? 0.00,
            'require_deposit' => $request->has('require_deposit'),
            'deposit_amount' => $validated['deposit_amount'] ?? 0.00,
        ]);

        $grandTotal = 0;
        foreach ($validated['items'] as $item) {
            $lineTotal = $item['quantity'] * $item['unit_price'];
            $grandTotal += $lineTotal;

            $quote->lineItems()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $lineTotal,
            ]);

            // Save row modifications backward into Pricebook catalog on request
            if (isset($item['save_to_pricebook']) && $item['save_to_pricebook']) {
                PricebookItem::updateOrCreate(
                    ['company_id' => Auth::user()->company_id, 'name' => $item['description']],
                    ['base_unit_cost' => $item['unit_price'], 'markup_percentage' => 0.00, 'unit_type' => 'flat_rate']
                );
            }
        }

        $quote->update(['total_amount' => $grandTotal]);

        return redirect()->route('dashboard')->with('success', '⚡ Estimate successfully compiled and stored.');
    }

    /**
     * Send secure estimate bypass portal link out over Twilio rest channels.
     */
    public function sendEstimateSms($id)
    {
        $quote = Quote::where('company_id', Auth::user()->company_id)->with('customer')->findOrFail($id);
        $customer = $quote->customer;

        if (empty($customer->phone)) {
            return back()->with('error', '🛑 No phone hardware address mapped to this customer file.');
        }

        $link = url('/portal?token=' . $quote->view_token);
        $companyName = Auth::user()->company->name ?? 'Our Team';
        $body = "Hi {$customer->first_name}, this is {$companyName}. Your job estimate is ready for your review. Tap here to view the line items, sign, and book the job: {$link}";

        $smsSent = $this->twilio->sendSms($customer->phone, $body);

        if ($smsSent) {
            if ($quote->status === 'draft') {
                $quote->update(['status' => 'sent']);
            }
            return back()->with('status', '⚡ Estimate link securely texted directly to customer phone.');
        }

        return back()->with('error', 'Failed messaging dispatch engine routes. See logs.');
    }

    /**
     * Settle active metrics and auto-trigger Reputation Engine loops.
     */
    public function closeJob($id)
    {
        $quote = Quote::where('company_id', Auth::user()->company_id)->with('customer')->findOrFail($id);

        $quote->update(['status' => 'completed']);
        $statusMessage = '🎉 Job successfully marked complete and locked into archive files.';

        if (!empty($customer->phone)) {
            $reviewLink = "https://g.page/r/your-custom-local-profile-link/review";
            $body = "Hi {$customer->first_name}, thank you for working with us! We would love your quick feedback. Tap here to leave us a fast Google review: {$reviewLink}";

            $this->twilio->sendSms($customer->phone, $body);
            $statusMessage = '🎉 Job complete. Auto-text review loop successfully deployed directly to customer.';
        }

        return back()->with('status', $statusMessage);
    }
}
