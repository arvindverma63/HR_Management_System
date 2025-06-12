<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payslip</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">

    <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg p-8 border border-gray-300">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold">Employee Payslip</h1>
            <p class="text-gray-600">Month: June 2025</p>
        </div>

        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-2">Employee Information</h2>
            <div class="grid grid-cols-2 gap-4 text-sm text-gray-700">
                <p><span class="font-medium">Name:</span> John Doe</p>
                <p><span class="font-medium">Monthly Rate:</span> ₹50,000</p>
                <p><span class="font-medium">Hourly Pay:</span> ₹250</p>
                <p><span class="font-medium">OT Rate:</span> ₹350</p>
            </div>
        </div>

        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-2">Attendance & Earnings</h2>
            <div class="grid grid-cols-2 gap-4 text-sm text-gray-700">
                <p><span class="font-medium">Days Worked:</span> 22</p>
                <p><span class="font-medium">Overtime Hours:</span> 10</p>
                <p><span class="font-medium">Basic Earnings:</span> ₹44,000</p>
                <p><span class="font-medium">OT Earnings:</span> ₹3,500</p>
                <p><span class="font-medium">Other Earnings:</span> ₹1,500</p>
            </div>
        </div>

        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-2">Deductions</h2>
            <div class="grid grid-cols-2 gap-4 text-sm text-gray-700">
                <p><span class="font-medium">Cash Deduction:</span> ₹500</p>
                <p><span class="font-medium">Misc Recovery:</span> ₹300</p>
                <p><span class="font-medium">Bank Advance:</span> ₹2,000</p>
                <p><span class="font-medium">Total Deduction:</span> ₹2,800</p>
            </div>
        </div>

        <div class="border-t pt-4 text-lg font-semibold text-right text-green-700">
            Net Payment: ₹46,200
        </div>

        <div class="text-xs text-gray-400 mt-8 text-center">
            This is a system-generated payslip and does not require a signature.
        </div>
    </div>

</body>
</html>
