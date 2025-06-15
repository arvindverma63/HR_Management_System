<!DOCTYPE html>
<html lang="en">
@include('partials.head')
<!-- DataTables CSS -->


<body class="bg-gradient-to-br from-gray-50 to-gray-200 font-sans antialiased">
    <div class="flex min-h-screen">
        @include('partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 p-4 md:p-8 w-full">
            @include('partials.header')

            <!-- Session Flash Messages -->
            @if (session('success'))
                <div
                    class="max-w-3xl mx-auto mb-4 px-4 py-3 rounded bg-green-100 text-green-800 border border-green-300 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div
                    class="max-w-3xl mx-auto mb-4 px-4 py-3 rounded bg-red-100 text-red-800 border border-red-300 shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div
                    class="max-w-3xl mx-auto mb-4 px-4 py-3 rounded bg-red-100 text-red-800 border border-red-300 shadow-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- 🔍 Search Section -->
            <div class="max-w-2xl mx-auto my-8 bg-white shadow-lg rounded-xl p-6">
                <h2 class="text-2xl font-bold mb-4 text-gray-800">Find Location</h2>
                <form method="GET" action="{{ route('workman-deductions') }}"
                    class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
                    <div>
                        <label for="location_id" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <select id="location_id" name="location_id"
                            class="w-full border-gray-300 rounded-lg shadow-sm p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            required>
                            <option value="">Select Location</option>
                            @foreach ($locations as $loc)
                                <option value="{{ $loc->id }}"
                                    {{ request('location_id') == $loc->id ? 'selected' : '' }}>
                                    {{ $loc->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('location_id')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-700 transition w-full md:w-auto">
                        Search
                    </button>
                </form>
            </div>

            @if ($selectedLocation)
                <!-- Location Details -->
                <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-xl p-6 mb-8">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Location Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <p><strong>Location ID:</strong> {{ $selectedLocation->id }}</p>
                        <p><strong>Name:</strong> {{ $selectedLocation->name ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Workmen List -->
                <div class="max-w-5xl mx-auto bg-white shadow-lg rounded-xl p-6">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Workmen</h3>
                    @if ($workmen->isEmpty())
                        <p class="text-gray-600">No workmen found for this location.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full border border-gray-200 rounded-lg" id="workmenTable">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Deductions
                                        </th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($workmen as $workman)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4">{{ $workman->name }}</td>
                                            <td class="px-6 py-4">{{ $workman->deductions->count() }} Deduction(s)</td>
                                            <td class="px-6 py-4 space-x-2">
                                                <button
                                                    onclick="openAddDeductionModal('{{ $workman->id }}', '{{ $workman->name }}')"
                                                    class="bg-green-600 text-white px-3 py-1 rounded-lg hover:bg-green-700 transition">
                                                    Add Deduction
                                                </button>
                                                @if ($workman->deductions->isNotEmpty())
                                                    <button
                                                        onclick="openDeductionsModal('{{ $workman->id }}', '{{ $workman->name }}')"
                                                        class="bg-blue-600 text-white px-3 py-1 rounded-lg hover:bg-blue-700 transition">
                                                        View Deductions
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Add Deduction Modal -->
    <div id="addDeductionModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">Add Deduction for <span id="addDeductionWorkmanName"></span></h3>
            <form id="addDeductionForm" method="POST" action="{{ route('workman-deductions.store') }}">
                @csrf
                <input type="hidden" name="workman_id" id="addDeductionWorkmanId">
                <div class="mb-4">
                    <label for="addDeductionType" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select id="addDeductionType" name="type"
                        class="w-full border-gray-300 rounded-lg shadow-sm p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        required>
                        <option value="">Select Type</option>
                        <option value="CASH">CASH</option>
                        <option value="MISC">MISC</option>
                        <option value="BANK ADV">BANK ADV</option>
                    </select>
                    @error('type')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="addDeductionRate" class="block text-sm font-medium text-gray-700 mb-1">Rate</label>
                    <input type="number" id="addDeductionRate" name="rate" step="0.01"
                        class="w-full border-gray-300 rounded-lg shadow-sm p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        required>
                    @error('rate')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeAddDeductionModal()"
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">Cancel</button>
                    <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">Add</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Deductions Modal -->
    <div id="deductionsModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-4xl">
            <h3 class="text-lg font-semibold mb-4">Deductions for <span id="deductionsWorkmanName"></span></h3>
            <div id="deductionsTableContainer" class="overflow-x-auto">
                <!-- Deductions table will be populated via JavaScript -->
            </div>
            <div class="flex justify-end mt-4">
                <button onclick="closeDeductionsModal()"
                    class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">Close</button>
            </div>
        </div>
    </div>

    <!-- Edit Deduction Modal -->
    <div id="editDeductionModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">Edit Deduction</h3>
            <form id="editDeductionForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="deduction_id" id="editDeductionId">
                <div class="mb-4">
                    <label for="editDeductionType" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select id="editDeductionType" name="type"
                        class="w-full border-gray-300 rounded-lg shadow-sm p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        required>
                        <option value="CASH">CASH</option>
                        <option value="MISC">MISC</option>
                        <option value="BANK ADV">BANK ADV</option>
                    </select>
                    @error('type')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="editDeductionRate" class="block text-sm font-medium text-gray-700 mb-1">Rate</label>
                    <input type="number" id="editDeductionRate" name="rate" step="0.01"
                        class="w-full border-gray-300 rounded-lg shadow-sm p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        required>
                    @error('rate')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeEditDeductionModal()"
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">Cancel</button>
                    <button type="submit"
                        class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteDeductionModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">Confirm Deletion</h3>
            <p class="text-gray-600 mb-4">Are you sure you want to delete this deduction?</p>
            <form id="deleteDeductionForm" method="POST">
                @csrf
                @method('DELETE')
                <input type="hidden" name="deduction_id" id="deleteDeductionId">
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeDeleteDeductionModal()"
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">Cancel</button>
                    <button type="submit"
                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">Delete</button>
                </div>
            </form>
        </div>
    </div>

    @include('partials.js')

    <!-- Tailwind DataTables via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new DataTable('#workmenTable', {
                paging: true, // Enable pagination
                searching: true, // Enable search bar
                ordering: true, // Enable column sorting
                info: true, // Show table info text
                responsive: true // Make table responsive
            });
        });


        function openAddDeductionModal(workmanId, workmanName) {
            document.getElementById('addDeductionWorkmanId').value = workmanId;
            document.getElementById('addDeductionWorkmanName').textContent = workmanName;
            document.getElementById('addDeductionModal').classList.remove('hidden');
        }

        function closeAddDeductionModal() {
            document.getElementById('addDeductionModal').classList.add('hidden');
            document.getElementById('addDeductionForm').reset();
        }

        function openDeductionsModal(workmanId, workmanName) {
            document.getElementById('deductionsWorkmanName').textContent = workmanName;
            const deductionsTableContainer = document.getElementById('deductionsTableContainer');
            const workmen = @json($workmen);
            const workman = workmen.find(w => w.id == workmanId);
            const deductions = workman ? workman.deductions : [];
            let tableHtml = `
                <table class="min-w-full border border-gray-200 rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600">Rate</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600">Created At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600">Updated At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
            `;
            deductions.forEach(deduction => {
                tableHtml += `
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">${deduction.type}</td>
                        <td class="px-6 py-4">${deduction.rate}</td>
                        <td class="px-6 py-4">${new Date(deduction.created_at).toLocaleString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })}</td>
                        <td class="px-6 py-4">${new Date(deduction.updated_at).toLocaleString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })}</td>
                        <td class="px-6 py-4 space-x-2">
                            <button onclick="openEditDeductionModal('${deduction.id}', '${deduction.type}', '${deduction.rate}')"
                                    class="bg-yellow-500 text-white px-3 py-1 rounded-lg hover:bg-yellow-600 transition">
                                Edit
                            </button>
                            <button onclick="openDeleteDeductionModal('${deduction.id}')"
                                    class="bg-red-600 text-white px-3 py-1 rounded-lg hover:bg-red-700 transition">
                                Delete
                            </button>
                        </td>
                    </tr>
                `;
            });
            tableHtml += '</tbody></table>';
            deductionsTableContainer.innerHTML = tableHtml;
            document.getElementById('deductionsModal').classList.remove('hidden');
        }

        function closeDeductionsModal() {
            document.getElementById('deductionsModal').classList.add('hidden');
            document.getElementById('deductionsTableContainer').innerHTML = '';
        }

        function openEditDeductionModal(deductionId, type, rate) {
            document.getElementById('editDeductionId').value = deductionId;
            document.getElementById('editDeductionType').value = type;
            document.getElementById('editDeductionRate').value = rate;
            document.getElementById('editDeductionForm').action = '{{ route('workman-deductions.update', ':id') }}'
                .replace(':id', deductionId);
            document.getElementById('editDeductionModal').classList.remove('hidden');
        }

        function closeEditDeductionModal() {
            document.getElementById('editDeductionModal').classList.add('hidden');
            document.getElementById('editDeductionForm').reset();
        }

        function openDeleteDeductionModal(deductionId) {
            document.getElementById('deleteDeductionId').value = deductionId;
            document.getElementById('deleteDeductionForm').action = '{{ route('workman-deductions.destroy', ':id') }}'
                .replace(':id', deductionId);
            document.getElementById('deleteDeductionModal').classList.remove('hidden');
        }

        function closeDeleteDeductionModal() {
            document.getElementById('deleteDeductionModal').classList.add('hidden');
            document.getElementById('deleteDeductionForm').reset();
        }
    </script>
</body>

</html>
