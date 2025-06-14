<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // Import the PDF facade

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders.
     */
    public function index()
    {
        // Get the authenticated user's orders, newest first, and paginate them.
        $orders = Auth::user()->orders()->withCount('items')->latest()->paginate(10);

        return view('users.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        // Authorize: Ensure the user is viewing their own order.
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Eager load the relationships for efficiency
        $order->load('items.product.primaryImageModel');

        return view('users.orders.show', compact('order'));
    }

    /**
     * Download the specified order as a PDF invoice.
     */
    public function downloadInvoice(Order $order)
    {
        // Authorize: Ensure the user is downloading their own invoice.
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Eager load data for the PDF
        $order->load('items.product');

        // Pass the order data to the PDF view
        $pdf = PDF::loadView('pdf.order_invoice', compact('order'));

        // Generate a filename for the download
        $filename = 'invoice-' . $order->order_number . '.pdf';

        // Stream the PDF to the browser for viewing/downloading
        return $pdf->stream($filename);
        // Or use ->download($filename) to force an immediate download.
    }
}