<!DOCTYPE html>
<html lang="en">
@include('partials.head')

<body class="bg-gradient-to-br from-gray-100 to-gray-300 font-sans antialiased">
    <div class="flex min-h-screen">
        @include('partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 p-4 md:p-10 w-full">
            <!-- Mobile Menu Button -->
            <button id="open-sidebar"
                class="md:hidden fixed top-4 left-4 z-30 bg-[#134a6b] text-white p-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#134a6b] transition"
                aria-label="Open sidebar">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            @include('partials.header')

            <!-- Session Messages -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-xl flex items-center shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100 text-red-800 rounded-xl shadow-md">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mt-8">
                <!-- Add Designation Form -->
                <div class="bg-white p-8 rounded-xl shadow-lg mb-8">
                    <h2 class="text-xl font-semibold mb-6 text-[#134a6b]">Add Designation</h2>
                    <form method="POST" action="{{ route('designations.store') }}">
                        @csrf
                        <div class="mb-6">
                            <label for="designation" class="block text-sm font-semibold text-gray-700 mb-2">
                                Designation Name
                            </label>
                            <input type="text" id="designation" name="name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#134a6b] focus:outline-none transition duration-200 bg-gray-50"
                                placeholder="Enter designation name" value="{{ old('name') }}"
                                aria-describedby="designation-error" required>
                            @error('name')
                                <span id="designation-error" class="text-red-500 text-sm mt-2">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit"
                            class="bg-[#134a6b] text-white px-6 py-3 rounded-lg hover:bg-[#0f3a55] focus:ring-2 focus:ring-[#134a6b] focus:outline-none transition duration-200 font-semibold">
                            Add Designation
                        </button>
                    </form>
                </div>

                <!-- Designation List -->
                <div class="bg-white p-8 rounded-xl shadow-lg">
                    <h2 class="text-xl font-semibold mb-6 text-[#134a6b]">Designation List</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        ID</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Designation</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($designations as $designation)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                            {{ $designation->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                            {{ $designation->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-3">
                                            <button
                                                onclick="openEditModal({{ $designation->id }}, '{{ str_replace("'", "\\'", $designation->name) }}')"
                                                class="inline-block px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 focus:ring-2 focus:ring-yellow-400 focus:outline-none transition duration-200 font-medium"
                                                aria-label="Edit designation {{ $designation->name }}">
                                                Edit
                                            </button>
                                            <form action="{{ route('designations.destroy', $designation->id) }}"
                                                method="POST" class="inline-block"
                                                onsubmit="return confirm('Are you sure you want to delete {{ str_replace("'", "\\'", $designation->name) }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 focus:ring-2 focus:ring-red-400 focus:outline-none transition duration-200 font-medium"
                                                    aria-label="Delete designation {{ $designation->name }}">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($designations->isEmpty())
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500 font-medium">
                                            No designations found.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal"
        class="modal hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-2xl p-8 w-full max-w-md mx-4 transform transition-transform duration-300 scale-100"
            role="dialog" aria-labelledby="edit-modal-title">
            <h2 id="edit-modal-title" class="text-xl font-semibold mb-6 text-[#134a6b]">Edit Designation</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-6">
                    <label for="edit_name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Designation Name
                    </label>
                    <input type="text" id="edit_name" name="name"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#134a6b] focus:outline-none transition duration-200 bg-gray-50"
                        aria-describedby="edit-name-error" required>
                    @error('name')
                        <span id="edit-name-error" class="text-red-500 text-sm mt-2">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" id="closeModalBtn"
                        class="px-5 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 focus:ring-2 focus:ring-gray-400 focus:outline-none transition duration-200 font-medium">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-5 py-2 bg-[#134a6b] text-white rounded-lg hover:bg-[#0f3a55] focus:ring-2 focus:ring-[#134a6b] focus:outline-none transition duration-200 font-medium">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const editModal = document.getElementById('editModal');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const editForm = document.getElementById('editForm');
        const editNameInput = document.getElementById('edit_name');

        // Open the modal
        function openEditModal(id, name) {
            console.log('Opening modal with ID:', id, 'Name:', name); // Debug log
            editForm.action = `/designations/${id}`;
            editNameInput.value = name;
            editModal.classList.remove('hidden');
            editModal.classList.add('opacity-100');
            editModal.querySelector('div').classList.remove('scale-95');
            editModal.querySelector('div').classList.add('scale-100');
        }

        // Close the modal
        closeModalBtn.addEventListener('click', () => {
            console.log('Closing modal'); // Debug log
            editModal.classList.add('hidden');
            editModal.classList.remove('opacity-100');
            editModal.querySelector('div').classList.add('scale-95');
            editModal.querySelector('div').classList.remove('scale-100');
            editNameInput.value = ''; // Clear input
        });

        // Close modal on click outside
        editModal.addEventListener('click', (event) => {
            if (event.target === editModal) {
                console.log('Closing modal via outside click'); // Debug log
                editModal.classList.add('hidden');
                editModal.classList.remove('opacity-100');
                editModal.querySelector('div').classList.add('scale-95');
                editModal.querySelector('div').classList.remove('scale-100');
                editNameInput.value = ''; // Clear input
            }
        });

        // Auto-dismiss session messages after 5 seconds
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                const messages = document.querySelectorAll('.bg-green-100, .bg-red-100');
                messages.forEach(msg => {
                    msg.classList.add('opacity-0', 'transition', 'duration-500');
                    setTimeout(() => msg.remove(), 500);
                });
            }, 5000);
        });
    </script>

    @include('partials.js')
</body>

</html>
