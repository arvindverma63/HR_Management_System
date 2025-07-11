<!DOCTYPE html>
<html lang="en">
@include('partials.head')

<body class="bg-gradient-to-br from-gray-50 to-gray-200 font-sans antialiased">
    <div class="flex min-h-screen">
        @include('partials.sidebar')

        <div class="flex-1 p-3 md:p-6 w-full">
            <button id="open-sidebar"
                class="md:hidden fixed top-3 left-3 z-30 bg-custom-blue text-white p-1.5 rounded-md focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            @include('partials.header')

            <section id="employee-additions">
                <div class="bg-white p-4 md:p-6 rounded-xl shadow-md">
                    <h3 class="text-lg md:text-xl font-semibold text-gray-800 mb-4">Manage Employee Additions</h3>

                    <!-- Success/Error Messages -->
                    @if (session('success'))
                        <div class="mb-3 p-3 bg-green-100 text-green-700 rounded-md text-sm">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-3 p-3 bg-red-100 text-red-700 rounded-md text-sm">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Form for Create/Edit Additions -->
                    <form id="addition-form" method="POST"
                        action="{{ isset($addition) ? route('additions.update', $addition->id) : route('additions.store') }}"
                        class="mb-6">
                        @csrf
                        @if (isset($addition))
                            @method('PUT')
                        @endif

                        <div class="mb-4">
                            <label for="location_id" class="block text-xs font-medium text-gray-700">Select
                                Location</label>
                            <select id="location_id" name="location_id"
                                class="mt-1 w-full md:w-48 p-1.5 border rounded-md focus:ring-1 focus:ring-custom-blue text-sm"
                                required>
                                <option value="">Select Location</option>
                                @foreach ($locations as $location)
                                    @if (Auth::user()->role == 'admin')
                                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                                    @endif
                                    @if (Auth::user()->role == 'hr')
                                        @if ($location->id == Session::get('location_id'))
                                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                                        @endif
                                    @endif
                                @endforeach
                            </select>
                            @error('location_id')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Employee Selection Table -->
                        <div id="employee-table-container" class="mb-4 hidden">
                            <h4 class="text-base font-medium text-gray-700 mb-2">Select Employees</h4>
                            <div class="overflow-x-auto">
                                <table id="employee-table" class="min-w-full bg-white border rounded-md text-sm">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="p-2 text-left text-xs font-medium text-gray-700">Select</th>
                                            <th class="p-2 text-left text-xs font-medium text-gray-700">Name</th>
                                            <th class="p-2 text-left text-xs font-medium text-gray-700">ID</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            @error('employee_ids')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Dynamic Additions Table -->
                        <div id="additions-container" class="mb-4 hidden">
                            <h4 class="text-base font-medium text-gray-700 mb-2">Additions</h4>
                            <div id="additions-rows">
                                <div class="addition-row grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                                    <div>
                                        <label for="type_0"
                                            class="block text-xs font-medium text-gray-700">Type</label>
                                        <input type="text" name="additions[0][type]" id="type_0"
                                            class="mt-1 w-full p-1.5 border rounded-md focus:ring-1 focus:ring-custom-blue text-sm"
                                            placeholder="Enter addition type" required>
                                        @error('additions.0.type')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="rate_0"
                                            class="block text-xs font-medium text-gray-700">Rate</label>
                                        <input type="number" step="0.01" name="additions[0][rate]" id="rate_0"
                                            class="mt-1 w-full p-1.5 border rounded-md focus:ring-1 focus:ring-custom-blue text-sm"
                                            placeholder="Enter rate" required>
                                        @error('additions.0.rate')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="flex items-end">
                                        <button type="button" onclick="removeAdditionRow(this)"
                                            class="bg-red-500 text-white p-1.5 rounded-md hover:bg-red-600 transition duration-200"
                                            title="Remove Addition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" onclick="addAdditionRow()"
                                class="bg-green-500 text-white p-1.5 rounded-md hover:bg-green-600 transition duration-200 text-sm flex items-center gap-1"
                                title="Add Another Addition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Add Another
                            </button>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit"
                                class="bg-custom-blue text-white p-1.5 rounded-md hover:bg-blue-600 transition duration-200 text-sm flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                {{ isset($addition) ? 'Update' : 'Add' }} Additions
                            </button>
                            @if (isset($addition))
                                <button type="button" onclick="resetForm()"
                                    class="bg-gray-500 text-white p-1.5 rounded-md hover:bg-gray-600 transition duration-200 text-sm flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Cancel
                                </button>
                            @endif
                        </div>
                    </form>

                    <!-- Filter by Location -->
                    <div class="mb-4">
                        <label for=" gets broken here location_filter"
                            class="block text-xs font-medium text-gray-700">Filter by Location</label>
                        <select id="location_filter"
                            class="mt-1 w-full md:w-48 p-1.5 border rounded-md focus:ring-1 focus:ring-custom-blue text-sm">
                            <option value="">All Locations</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Additions Table -->
                    <div class="overflow-x-auto">
                        <table id="additions-table" class="min-w-full bg-white border rounded-md text-sm">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="p-2 text-left text-xs font-medium text-gray-700">Employee</th>
                                    <th class="p-2 text-left text-xs font-medium text-gray-700">Type</th>
                                    <th class="p-2 text-left text-xs font-medium text-gray-700">Rate</th>
                                    <th class="p-2 text-left text-xs font-medium text-gray-700">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($additions as $addition)
                                    <tr class="hover:bg-gray-50">
                                        <td class="p-2 text-sm text-gray-700">{{ $addition->employee->name ?? 'N/A' }}
                                        </td>
                                        <td class="p-2 text-sm text-gray-700">{{ $addition->type }}</td>
                                        <td class="p-2 text-sm text-gray-700">{{ $addition->rate }}</td>
                                        <td class="p-2 flex items-center gap-1.5">
                                            <button onclick="editAddition({{ $addition->id }})"
                                                class="bg-blue-500 text-white p-1.5 rounded-md hover:bg-blue-600 transition duration-200"
                                                title="Edit Addition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <form action="{{ route('additions.destroy', $addition->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="bg-red-500 text-white p-1.5 rounded-md hover:bg-red-600 transition duration-200"
                                                    title="Delete Addition"
                                                    onclick="return confirm('Are you sure you want to delete this addition?')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V5a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>

    @include('partials.js')

    <script>
        let rowCount = 1;

        // Fetch employees based on selected location and display in table
        document.getElementById('location_id').addEventListener('change', function() {
            const locationId = this.value;
            const employeeTableContainer = document.getElementById('employee-table-container');
            const employeeTableBody = document.querySelector('#employee-table tbody');
            const additionsContainer = document.getElementById('additions-container');

            if (locationId) {
                fetch(`/employees-by-location/${locationId}`)
                    .then(response => response.json())
                    .then(data => {
                        employeeTableBody.innerHTML = '';
                        data.employees.forEach(employee => {
                            employeeTableBody.innerHTML += `
                                <tr class="hover:bg-gray-50">
                                    <td class="p-2 text-sm text-gray-700">
                                        <input type="checkbox" name="employee_ids[]" value="${employee.id}" class="employee-checkbox rounded focus:ring-1 focus:ring-custom-blue">
                                    </td>
                                    <td class="p-2 text-sm text-gray-700">${employee.name}</td>
                                    <td class="p-2 text-sm text-sm text-gray-700">${employee.employee_unique_id}</td>
                                </tr>
                            `;
                        });
                        employeeTableContainer.classList.remove('hidden');
                        additionsContainer.classList.remove('hidden');
                    })
                    .catch(error => console.error('Error fetching employees:', error));
            } else {
                employeeTableContainer.classList.add('hidden');
                additionsContainer.classList.add('hidden');
                employeeTableBody.innerHTML = '';
            }
        });

        // Add a new addition row
        function addAdditionRow() {
            const container = document.getElementById('additions-rows');
            const row = document.createElement('div');
            row.className = 'addition-row grid grid-cols-1 md:grid-cols-3 gap-3 mb-3';
            row.innerHTML = `
                <div>
                    <label for="type_${rowCount}" class="block text-xs font-medium text-gray-700">Type</label>
                    <input type="text" name="additions[${rowCount}][type]" id="type_${rowCount}" class="mt-1 w-full p-1.5 border rounded-md focus:ring-1 focus:ring-custom-blue text-sm" placeholder="Enter addition type" required>
                </div>
                <div>
                    <label for="rate_${rowCount}" class="block text-xs font-medium text-gray-700">Rate</label>
                    <input type="number" step="0.01" name="additions[${rowCount}][rate]" id="rate_${rowCount}" class="mt-1 w-full p-1.5 border rounded-md focus:ring-1 focus:ring-custom-blue text-sm" placeholder="Enter rate" required>
                </div>
                <div class="flex items-end">
                    <button type="button" onclick="removeAdditionRow(this)" class="bg-red-500 text-white p-1.5 rounded-md hover:bg-red-600 transition duration-200" title="Remove Addition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            `;
            container.appendChild(row);
            rowCount++;
        }

        // Remove an addition row
        function removeAdditionRow(button) {
            if (document.querySelectorAll('.addition-row').length > 1) {
                button.closest('.addition-row').remove();
            }
        }

        // Reset form for new addition
        function resetForm() {
            document.getElementById('addition-form').action = "{{ route('additions.store') }}";
            document.getElementById('addition-form').innerHTML = document.getElementById('addition-form').innerHTML.replace(
                '@method('PUT')', '');
            document.getElementById('location_id').value = '';
            document.getElementById('employee-table-container').classList.add('hidden');
            document.getElementById('additions-container').classList.add('hidden');
            document.querySelector('#employee-table tbody').innerHTML = '';
            document.getElementById('additions-rows').innerHTML = `
                <div class="addition-row grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                    <div>
                        <label for="type_0" class="block text-xs font-medium text-gray-700">Type</label>
                        <input type="text" name="additions[0][type]" id="type_0" class="mt-1 w-full p-1.5 border rounded-md focus:ring-1 focus:ring-custom-blue text-sm" placeholder="Enter addition type" required>
                    </div>
                    <div>
                        <label for="rate_0" class="block text-xs font-medium text-gray-700">Rate</label>
                        <input type="number" step="0.01" name="additions[0][rate]" id="rate_0" class="mt-1 w-full p-1.5 border rounded-md focus:ring-1 focus:ring-custom-blue text-sm" placeholder="Enter rate" required>
                    </div>
                    <div class="flex items-end">
                        <button type="button" onclick="removeAdditionRow(this)" class="bg-red-500 text-white p-1.5 rounded-md hover:bg-red-600 transition duration-200" title="Remove Addition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            `;
            rowCount = 1;
            document.querySelector('button[type="submit"]').innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Add Additions
            `;
            document.querySelector('.flex.gap-2').innerHTML = `
                <button type="submit" class="bg-custom-blue text-white p-1.5 rounded-md hover:bg-blue-600 transition duration-200 text-sm flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Add Additions
                </button>
            `;
        }

        // Fetch and populate form for editing
        function editAddition(id) {
            fetch(`/additions/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('addition-form').action = `/additions/${id}`;
                    document.getElementById('location_id').value = data.addition.employee.location_id;

                    // Populate employee table
                    const employeeTableBody = document.querySelector('#employee-table tbody');
                    fetch(`/employees-by-location/${data.addition.employee.location_id}`)
                        .then(response => response.json())
                        .then(employeeData => {
                            employeeTableBody.innerHTML = '';
                            employeeData.employees.forEach(employee => {
                                employeeTableBody.innerHTML += `
                                    <tr class="hover:bg-gray-50">
                                        <td class="p-2 text-sm text-gray-700">
                                            <input type="checkbox" name="employee_ids[]" value="${employee.id}" class="employee-checkbox rounded focus:ring-1 focus:ring-custom-blue" ${employee.id == data.addition.employee_id ? 'checked' : ''}>
                                        </td>
                                        <td class="p-2 text-sm text-gray-700">${employee.name}</td>
                                        <td class="p-2 text-sm text-gray-700">${employee.employee_unique_id}</td>
                                    </tr>
                                `;
                            });
                            document.getElementById('employee-table-container').classList.remove('hidden');
                            document.getElementById('additions-container').classList.remove('hidden');
                        });

                    // Populate single addition row
                    document.getElementById('additions-rows').innerHTML = `
                        <div class="addition-row grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                            <div>
                                <label for="type_0" class="block text-xs font-medium text-gray-700">Type</label>
                                <input type="text" name="additions[0][type]" id="type_0" value="${data.addition.type}" class="mt-1 w-full p-1.5 border rounded-md focus:ring-1 focus:ring-custom-blue text-sm" placeholder="Enter addition type" required>
                            </div>
                            <div>
                                <label for="rate_0" class="block text-xs font-medium text-gray-700">Rate</label>
                                <input type="number" step="0.01" name="additions[0][rate]" id="rate_0" value="${data.addition.rate}" class="mt-1 w-full p-1.5 border rounded-md focus:ring-1 focus:ring-custom-blue text-sm" placeholder="Enter rate" required>
                            </div>
                            <div class="flex items-end">
                                <button type="button" onclick="removeAdditionRow(this)" class="bg-red-500 text-white p-1.5 rounded-md hover:bg-red-600 transition duration-200" title="Remove Addition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    `;
                    rowCount = 1;
                    document.querySelector('button[type="submit"]').innerHTML = `
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Additions
                    `;

                    // Add or update @method('PUT') in the form
                    let form = document.getElementById('addition-form');
                    if (!form.querySelector('input[name="_method"]')) {
                        let methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'PUT';
                        form.insertBefore(methodInput, form.firstChild);
                    }

                    // Add Cancel button if not already present
                    let buttonContainer = document.querySelector('.flex.gap-2');
                    if (!buttonContainer.querySelector('button[onclick="resetForm()"]')) {
                        let cancelButton = document.createElement('button');
                        cancelButton.type = 'button';
                        cancelButton.onclick = resetForm;
                        cancelButton.className =
                            'bg-gray-500 text-white p-1.5 rounded-md hover:bg-gray-600 transition duration-200 text-sm flex items-center gap-1';
                        cancelButton.innerHTML = `
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancel
                        `;
                        buttonContainer.appendChild(cancelButton);
                    }
                });
        }

        // Filter table by location
        document.getElementById('location_filter').addEventListener('change', function() {
            let locationId = this.value;
            fetch(`/additions?location_id=${locationId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    let tbody = document.querySelector('#additions-table tbody');
                    tbody.innerHTML = '';
                    data.additions.forEach(addition => {
                        tbody.innerHTML += `
                            <tr class="hover:bg-gray-50">
                                <td class="p-2 text-sm text-gray-700">${addition.employee_name}</td>
                                <td class="p-2 text-sm text-gray-700">${addition.type}</td>
                                <td class="p-2 text-sm text-gray-700">${addition.rate}</td>
                                <td class="p-2 flex items-center gap-1.5">
                                    <button onclick="editAddition(${addition.id})" class="bg-blue-500 text-white p-1.5 rounded-md hover:bg-blue-600 transition duration-200" title="Edit Addition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <form action="/additions/${addition.id}" method="POST" class="inline">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                        <button type="submit" class="bg-red-500 text-white p-1.5 rounded-md hover:bg-red-600 transition duration-200" title="Delete Addition" onclick="return confirm('Are you sure you want to delete this addition?')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V5a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        `;
                    });
                });
        });
    </script>
