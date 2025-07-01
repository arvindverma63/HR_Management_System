<!DOCTYPE html>
<html lang="en">
@include('partials.head')

<body class="bg-gradient-to-br from-gray-50 to-gray-200 font-sans antialiased">
    <div class="flex min-h-screen">
        @include('partials.sidebar')

        <div class="flex-1 p-4 md:p-8 w-full">
            <button id="open-sidebar" class="md:hidden fixed top-4 left-4 z-30 bg-custom-blue text-white p-2 rounded-lg focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            @include('partials.header')

            <section id="employee-additions">
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-lg">
                    <h3 class="text-xl md:text-2xl font-semibold text-gray-800 mb-4 md:mb-6">Manage Employee Additions</h3>

                    <!-- Success/Error Messages -->
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Form for Create/Edit Additions -->
                    <form id="addition-form" method="POST" action="{{ isset($addition) ? route('additions.update', $addition->id) : route('additions.store') }}" class="mb-8">
                        @csrf
                        @if(isset($addition)) @method('PUT') @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="location_id" class="block text-sm font-medium text-gray-700">Location</label>
                                <select id="location_id" name="location_id" class="mt-1 w-full p-2 border rounded-lg focus:ring-2 focus:ring-custom-blue" required>
                                    <option value="">Select Location</option>
                                    @foreach ($locations as $location)
                                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                                    @endforeach
                                </select>
                                @error('location_id')<span class="text-red-500 text-sm mt-1">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label for="employee_id" class="block text-sm font-medium text-gray-700">Employee</label>
                                <select id="employee_id" name="employee_id" class="mt-1 w-full p-2 border rounded-lg focus:ring-2 focus:ring-custom-blue" required>
                                    <option value="">Select Employee</option>
                                </select>
                                @error('employee_id')<span class="text-red-500 text-sm mt-1">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <!-- Dynamic Additions Table -->
                        <div id="additions-container" class="mb-4">
                            <h4 class="text-lg font-medium text-gray-700 mb-2">Additions</h4>
                            <div id="additions-rows">
                                <div class="addition-row grid grid-cols-1 md:grid-cols-3 gap-4 mb-2">
                                    <div>
                                        <label for="type_0" class="block text-sm font-medium text-gray-700">Type</label>
                                        <input type="text" name="additions[0][type]" id="type_0" class="mt-1 w-full p-2 border rounded-lg focus:ring-2 focus:ring-custom-blue" placeholder="Enter type" required>
                                        @error('additions.0.type')<span class="text-red-500 text-sm mt-1">{{ $message }}</span>@enderror
                                    </div>
                                    <div>
                                        <label for="rate_0" class="block text-sm font-medium text-gray-700">Rate</label>
                                        <input type="number" step="0.01" name="additions[0][rate]" id="rate_0" class="mt-1 w-full p-2 border rounded-lg focus:ring-2 focus:ring-custom-blue" placeholder="Enter rate" required>
                                        @error('additions.0.rate')<span class="text-red-500 text-sm mt-1">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="flex items-end">
                                        <button type="button" onclick="removeAdditionRow(this)" class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600 transition-all">Remove</button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" onclick="addAdditionRow()" class="bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 transition-all mt-2">Add Another Addition</button>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" class="bg-custom-blue text-white py-2 px-4 rounded-lg hover:bg-custom-blue-dark transition-all">
                                {{ isset($addition) ? 'Update' : 'Add' }} Additions
                            </button>
                            @if(isset($addition))
                                <button type="button" onclick="resetForm()" class="bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600 transition-all">Cancel Edit</button>
                            @endif
                        </div>
                    </form>

                    <!-- Filter by Location -->
                    <div class="mb-4">
                        <label for="location_filter" class="block text-sm font-medium text-gray-700">Filter by Location</label>
                        <select id="location_filter" class="mt-1 w-full md:w-1/4 p-2 border rounded-lg focus:ring-2 focus:ring-custom-blue">
                            <option value="">All Locations</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Additions Table -->
                    <div class="overflow-x-auto">
                        <table id="additions-table" class="min-w-full bg-white border rounded-lg">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="p-3 text-left text-sm font-medium text-gray-700">Employee</th>
                                    <th class="p-3 text-left text-sm font-medium text-gray-700">Type</th>
                                    <th class="p-3 text-left text-sm font-medium text-gray-700">Rate</th>
                                    <th class="p-3 text-left text-sm font-medium text-gray-700">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($additions as $addition)
                                    <tr>
                                        <td class="p-3 text-sm text-gray-700">{{ $addition->employee->name ?? 'N/A' }}</td>
                                        <td class="p-3 text-sm text-gray-700">{{ $addition->type }}</td>
                                        <td class="p-3 text-sm text-gray-700">{{ $addition->rate }}</td>
                                        <td class="p-3 text-sm">
                                            <button onclick="editAddition({{ $addition->id }})" class="text-blue-500 hover:underline">Edit</button>
                                            <form action="{{ route('additions.destroy', $addition->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:underline ml-2" onclick="return confirm('Are you sure you want to delete this addition?')">Delete</button>
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
</body>

<script>
    let rowCount = 1;

    // Fetch employees based on selected location
    document.getElementById('location_id').addEventListener('change', function() {
        const locationId = this.value;
        const employeeSelect = document.getElementById('employee_id');
        employeeSelect.innerHTML = '<option value="">Select Employee</option>';

        if (locationId) {
            fetch(`/employees-by-location/${locationId}`)
                .then(response => response.json())
                .then(data => {
                    data.employees.forEach(employee => {
                        const option = document.createElement('option');
                        option.value = employee.id;
                        option.textContent = employee.name;
                        employeeSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching employees:', error));
        }
    });

    // Add a new addition row
    function addAdditionRow() {
        const container = document.getElementById('additions-rows');
        const row = document.createElement('div');
        row.className = 'addition-row grid grid-cols-1 md:grid-cols-3 gap-4 mb-2';
        row.innerHTML = `
            <div>
                <label for="type_${rowCount}" class="block text-sm font-medium text-gray-700">Type</label>
                <input type="text" name="additions[${rowCount}][type]" id="type_${rowCount}" class="mt-1 w-full p-2 border rounded-lg focus:ring-2 focus:ring-custom-blue" placeholder="Enter type" required>
            </div>
            <div>
                <label for="rate_${rowCount}" class="block text-sm font-medium text-gray-700">Rate</label>
                <input type="number" step="0.01" name="additions[${rowCount}][rate]" id="rate_${rowCount}" class="mt-1 w-full p-2 border rounded-lg focus:ring-2 focus:ring-custom-blue" placeholder="Enter rate" required>
            </div>
            <div class="flex items-end">
                <button type="button" onclick="removeAdditionRow(this)" class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600 transition-all">Remove</button>
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
        document.getElementById('addition-form').innerHTML = document.getElementById('addition-form').innerHTML.replace('@method("PUT")', '');
        document.getElementById('location_id').value = '';
        document.getElementById('employee_id').innerHTML = '<option value="">Select Employee</option>';
        document.getElementById('additions-rows').innerHTML = `
            <div class="addition-row grid grid-cols-1 md:grid-cols-3 gap-4 mb-2">
                <div>
                    <label for="type_0" class="block text-sm font-medium text-gray-700">Type</label>
                    <input type="text" name="additions[0][type]" id="type_0" class="mt-1 w-full p-2 border rounded-lg focus:ring-2 focus:ring-custom-blue" placeholder="Enter type" required>
                </div>
                <div>
                    <label for="rate_0" class="block text-sm font-medium text-gray-700">Rate</label>
                    <input type="number" step="0.01" name="additions[0][rate]" id="rate_0" class="mt-1 w-full p-2 border rounded-lg focus:ring-2 focus:ring-custom-blue" placeholder="Enter rate" required>
                </div>
                <div class="flex items-end">
                    <button type="button" onclick="removeAdditionRow(this)" class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600 transition-all">Remove</button>
                </div>
            </div>
        `;
        rowCount = 1;
        document.querySelector('button[type="submit"]').textContent = 'Add Additions';
        document.querySelector('.flex.gap-4').innerHTML = '<button type="submit" class="bg-custom-blue text-white py-2 px-4 rounded-lg hover:bg-custom-blue-dark transition-all">Add Additions</button>';
    }

    // Fetch and populate form for editing
    function editAddition(id) {
        fetch(`/additions/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('addition-form').action = `/additions/${id}`;
                document.getElementById('location_id').value = data.addition.employee.location_id;

                // Populate employee dropdown
                const employeeSelect = document.getElementById('employee_id');
                employeeSelect.innerHTML = '<option value="">Select Employee</option>';
                fetch(`/employees-by-location/${data.addition.employee.location_id}`)
                    .then(response => response.json())
                    .then(employeeData => {
                        employeeData.employees.forEach(employee => {
                            const option = document.createElement('option');
                            option.value = employee.id;
                            option.textContent = employee.name;
                            if (employee.id == data.addition.employee_id) option.selected = true;
                            employeeSelect.appendChild(option);
                        });
                    });

                // Populate single addition row
                document.getElementById('additions-rows').innerHTML = `
                    <div class="addition-row grid grid-cols-1 md:grid-cols-3 gap-4 mb-2">
                        <div>
                            <label for="type_0" class="block text-sm font-medium text-gray-700">Type</label>
                            <input type="text" name="additions[0][type]" id="type_0" value="${data.addition.type}" class="mt-1 w-full p-2 border rounded-lg focus:ring-2 focus:ring-custom-blue" placeholder="Enter type" required>
                        </div>
                        <div>
                            <label for="rate_0" class="block text-sm font-medium text-gray-700">Rate</label>
                            <input type="number" step="0.01" name="additions[0][rate]" id="rate_0" value="${data.addition.rate}" class="mt-1 w-full p-2 border rounded-lg focus:ring-2 focus:ring-custom-blue" placeholder="Enter rate" required>
                        </div>
                        <div class="flex items-end">
                            <button type="button" onclick="removeAdditionRow(this)" class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600 transition-all">Remove</button>
                        </div>
                    </div>
                `;
                rowCount = 1;
                document.querySelector('button[type="submit"]').textContent = 'Update Additions';

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
                let buttonContainer = document.querySelector('.flex.gap-4');
                if (!buttonContainer.querySelector('button[onclick="resetForm()"]')) {
                    let cancelButton = document.createElement('button');
                    cancelButton.type = 'button';
                    cancelButton.onclick = resetForm;
                    cancelButton.className = 'bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600 transition-all';
                    cancelButton.textContent = 'Cancel Edit';
                    buttonContainer.appendChild(cancelButton);
                }
            });
    }

    // Filter table by location
    document.getElementById('location_filter').addEventListener('change', function() {
        let locationId = this.value;
        fetch(`/additions?location_id=${locationId}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.json())
            .then(data => {
                let tbody = document.querySelector('#additions-table tbody');
                tbody.innerHTML = '';
                data.additions.forEach(addition => {
                    tbody.innerHTML += `
                        <tr>
                            <td class="p-3 text-sm text-gray-700">${addition.employee_name}</td>
                            <td class="p-3 text-sm text-gray-700">${addition.type}</td>
                            <td class="p-3 text-sm text-gray-700">${addition.rate}</td>
                            <td class="p-3 text-sm">
                                <button onclick="editAddition(${addition.id})" class="text-blue-500 hover:underline">Edit</button>
                                <form action="/additions/${addition.id}" method="POST" class="inline">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                    <button type="submit" class="text-red-500 hover:underline ml-2" onclick="return confirm('Are you sure you want to delete this addition?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    `;
                });
            });
    });
</script>
</html>
