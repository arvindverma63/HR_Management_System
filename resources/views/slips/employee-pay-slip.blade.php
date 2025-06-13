@foreach ($report as $row)
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Payslip - {{ $row->name }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            @media print {
                .no-print {
                    display: none;
                }

                .payslip-container {
                    box-shadow: none;
                    border: none;
                }
            }
        </style>
    </head>

    <body class="bg-gray-100 font-sans p-6 md:p-10 print:p-0">
        <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-8 border border-gray-200 payslip-container">
            <!-- Company Header -->
            <div class="flex justify-between items-center mb-8 border-b pb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Kunal Globel</h1>
                    <p class="text-sm text-gray-600">123 Business Street, Kanpur, UP xxxxx</p>
                    <p class="text-sm text-gray-600">Email: payroll@acme.com | Phone: +91 22 1234 5678</p>
                </div>
                <div class="text-right">
                    <h2 class="text-xl font-semibold text-gray-800">Payslip</h2>
                    <p class="text-sm text-gray-600">Month: June 2025</p>
                </div>
            </div>

            <!-- Employee Information -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Employee Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                    <div class="flex">
                        <span class="font-medium w-1/3">Name:</span>
                        <span>{{ $row->name }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium w-1/3">Monthly Rate:</span>
                        <span>₹{{ number_format($row->rate_per_month, 2) }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium w-1/3">Hourly Pay:</span>
                        <span>₹{{ number_format($row->rate_of_wages, 2) }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium w-1/3">OT Rate:</span>
                        <span>₹{{ number_format($row->rate_of_ot, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Earnings -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Earnings</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                    <div class="flex">
                        <span class="font-medium w-1/3">Days Worked:</span>
                        <span>{{ $row->days_worked }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium w-1/3">Overtime Hours:</span>
                        <span>{{ $row->overtime_hours }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium w-1/3">Basic Earnings:</span>
                        <span>₹{{ number_format($row->basic_earnings, 2) }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium w-1/3">OT Earnings:</span>
                        <span>₹{{ number_format($row->overtime_earnings, 2) }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium w-1/3">Other Earnings:</span>
                        <span>₹{{ number_format($row->other_earnings, 2) }}</span>
                    </div>
                    <div class="flex font-semibold text-gray-800">
                        <span class="w-1/3">Total Earnings:</span>
                        <span>₹{{ number_format($row->basic_earnings + $row->overtime_earnings + $row->other_earnings, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Deductions -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Deductions</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                    <div class="flex">
                        <span class="font-medium w-1/3">Cash Deduction:</span>
                        <span>₹{{ number_format($row->cash_deduction, 2) }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium w-1/3">Misc Recovery:</span>
                        <span>₹{{ number_format($row->misc_recovery, 2) }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium w-1/3">Bank Advance:</span>
                        <span>₹{{ number_format($row->bank_adv, 2) }}</span>
                    </div>
                    <div class="flex font-semibold text-gray-800">
                        <span class="w-1/3">Total Deduction:</span>
                        <span>₹{{ number_format($row->total_deduction, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Net Payment -->
            <div class="border-t border-gray-300 pt-4 mb-8">
                <div class="text-lg font-semibold text-right text-green-700">
                    Net Payment: ₹{{ number_format($row->net_payments, 2) }}
                </div>
            </div>

            <!-- Download/Print Button -->
            <div class="text-right no-print">
                <button onclick="window.print()"
                    class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Download / Print
                </button>
            </div>

            <!-- Footer -->
            <div class="text-xs text-gray-400 mt-8 text-center">
                This is a system-generated payslip and does not require a signature.
            </div>
        </div>
    </body>

    </html>
@endforeach
