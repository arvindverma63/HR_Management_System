<html lang="en">
@include('partials.head')

<body class="bg-gradient-to-br from-gray-50 to-gray-200 font-sans antialiased">
    <div class="flex min-h-screen">
        @include('HRAdmin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 p-4 md:p-8 w-full">
            <!-- Mobile Menu Button -->
            <button id="open-sidebar"
                class="md:hidden fixed top-4 left-4 z-30 bg-custom-blue text-white p-2 rounded-lg focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            @include('partials.header')

            <!-- New Workmen Joining & Screening Form -->
            <section id="new-workmen">
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-lg transform hover:scale-[1.01] transition-all">
                    <h3 class="text-xl md:text-2xl font-semibold text-gray-800 mb-4 md:mb-6">New Employee Joining &
                        Screening Form</h3>

                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('new-employee.store') }}"
                        class="grid grid-cols-1 gap-4 md:gap-6">
                        @csrf
                        <!-- Location Selection -->
                        <div class="border-b pb-4">
                            <h4 class="text-lg font-medium text-gray-700 mb-4">Work Location</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                <div>
                                    <label for="location_id"
                                        class="block text-sm font-medium text-gray-700">Location</label>
                                    <select id="location_id" name="location_id"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        required>
                                        <option value="">Select Location</option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('location_id')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Personal Details -->
                        <div class="border-b pb-4">
                            <h4 class="text-lg font-medium text-gray-700 mb-4">Personal Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter name" required>
                                    @error('name')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="surname"
                                        class="block text-sm font-medium text-gray-700">Surname</label>
                                    <input type="text" id="surname" name="surname" value="{{ old('surname') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter surname" required>
                                    @error('surname')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="sex" class="block text-sm font-medium text-gray-700">Sex
                                        (Male/Female)</label>
                                    <select id="sex" name="sex"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                                        <option value="">Select Sex</option>
                                        <option value="male" {{ old('sex') == 'male' ? 'selected' : '' }}>Male
                                        </option>
                                        <option value="female" {{ old('sex') == 'female' ? 'selected' : '' }}>Female
                                        </option>
                                    </select>
                                    @error('sex')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="dob" class="block text-sm font-medium text-gray-700">Date of
                                        Birth</label>
                                    <input type="date" id="dob" name="dob" value="{{ old('dob') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                                    @error('dob')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="blood_group" class="block text-sm font-medium text-gray-700">Blood
                                        Group</label>
                                    <select id="blood_group" name="blood_group"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                                        <option value="">Select Blood Group</option>
                                        <option value="A+" {{ old('blood_group') == 'A+' ? 'selected' : '' }}>A+
                                        </option>
                                        <option value="A-" {{ old('blood_group') == 'A-' ? 'selected' : '' }}>A-
                                        </option>
                                        <option value="B+" {{ old('blood_group') == 'B+' ? 'selected' : '' }}>B+
                                        </option>
                                        <option value="B-" {{ old('blood_group') == 'B-' ? 'selected' : '' }}>B-
                                        </option>
                                        <option value="AB+" {{ old('blood_group') == 'AB+' ? 'selected' : '' }}>AB+
                                        </option>
                                        <option value="AB-" {{ old('blood_group') == 'AB-' ? 'selected' : '' }}>AB-
                                        </option>
                                        <option value="O+" {{ old('blood_group') == 'O+' ? 'selected' : '' }}>O+
                                        </option>
                                        <option value="O-" {{ old('blood_group') == 'O-' ? 'selected' : '' }}>O-
                                        </option>
                                    </select>
                                    @error('blood_group')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="designation" class="block text-sm font-medium text-gray-700">Designation
                                        (HSW/SSW/USW)</label>
                                    <select id="designation" name="designation"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                                        <option value="">Select Designation</option>
                                        @foreach ($designations as $d)
                                            <option value="{{ $d->id }}"
                                                {{ old('designation') == $d->id ? 'selected' : '' }}>
                                                {{ $d->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                    @error('designation')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="monthly_rate" class="block text-sm font-medium text-gray-700">Monthly
                                        Rate</label>
                                    <input type="number" id="monthly_rate" name="monthly_rate"
                                        value="{{ old('monthly_rate') }}" onkeyup="mothlyPay()"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter monthly rate">
                                    @error('monthly_rate')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="hourly_pay" class="block text-sm font-medium text-gray-700">Hourly
                                        Pay</label>
                                    <input type="number" id="hourly_pay" name="hourly_pay"
                                        value="{{ old('hourly_pay') }}" onkeyup="hourlyPay()"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter qualification">
                                    @error('hourly_pay')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="employee_unique_id"
                                        class="block text-sm font-medium text-gray-700">Employee
                                        Id</label>
                                    <input type="number" id="employee_unique_id" name="employee_unique_id"
                                        value="{{ old('employee_unique_id') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter Employee Id">
                                    @error('employee_unique_id')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="handicapped"
                                        class="block text-sm font-medium text-gray-700">Handicapped
                                        (True/False)</label>
                                    <select id="handicapped" name="handicapped"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                                        <option value="">Select</option>
                                        <option value="1" {{ old('handicapped') == '1' ? 'selected' : '' }}>True
                                        </option>
                                        <option value="0" {{ old('handicapped') == '0' ? 'selected' : '' }}>False
                                        </option>
                                    </select>
                                    @error('handicapped')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="border-b pb-4 mt-6">
                            <h4 class="text-lg font-medium text-gray-700 mb-4">Allowance Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">

                                <div>
                                    <label for="basic_pay" class="block text-sm font-medium text-gray-700">Basic
                                        Pay</label>
                                    <input type="number" step="0.01" id="basic_pay" name="basic_pay"
                                        value="{{ old('basic_pay') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter Basic Pay">
                                    @error('basic_pay')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="house_rent_allowance"
                                        class="block text-sm font-medium text-gray-700">House Rent Allowance</label>
                                    <input type="number" step="0.01" id="house_rent_allowance"
                                        name="house_rent_allowance" value="{{ old('house_rent_allowance') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter HRA">
                                    @error('house_rent_allowance')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="conveyance_allowance"
                                        class="block text-sm font-medium text-gray-700">Conveyance Allowance</label>
                                    <input type="number" step="0.01" id="conveyance_allowance"
                                        name="conveyance_allowance" value="{{ old('conveyance_allowance') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter Conveyance Allowance">
                                    @error('conveyance_allowance')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="food_allowance" class="block text-sm font-medium text-gray-700">Food
                                        Allowance</label>
                                    <input type="number" step="0.01" id="food_allowance" name="food_allowance"
                                        value="{{ old('food_allowance') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter Food Allowance">
                                    @error('food_allowance')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="site_allowance" class="block text-sm font-medium text-gray-700">Site
                                        Allowance</label>
                                    <input type="number" step="0.01" id="site_allowance" name="site_allowance"
                                        value="{{ old('site_allowance') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter Site Allowance">
                                    @error('site_allowance')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="statutory_bonus"
                                        class="block text-sm font-medium text-gray-700">Statutory Bonus</label>
                                    <input type="number" step="0.01" id="statutory_bonus" name="statutory_bonus"
                                        value="{{ old('statutory_bonus') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter Statutory Bonus">
                                    @error('statutory_bonus')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="retrenchment_allowance"
                                        class="block text-sm font-medium text-gray-700">Retrenchment Allowance</label>
                                    <input type="number" step="0.01" id="retrenchment_allowance"
                                        name="retrenchment_allowance" value="{{ old('retrenchment_allowance') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter Retrenchment Allowance">
                                    @error('retrenchment_allowance')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="medical"
                                        class="block text-sm font-medium text-gray-700">Medical</label>
                                    <input type="number" step="0.01" id="medical" name="medical"
                                        value="{{ old('medical') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter Medical Allowance">
                                    @error('medical')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        <!-- Identification -->
                        <div class="border-b pb-4">
                            <h4 class="text-lg font-medium text-gray-700 mb-4">Identification</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                <div>
                                    <label for="pan_number" class="block text-sm font-medium text-gray-700">PAN
                                        Number</label>
                                    <input type="text" id="pan_number" name="pan_number"
                                        value="{{ old('pan_number') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter PAN number">
                                    @error('pan_number')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="aadhar_number" class="block text-sm font-medium text-gray-700">Aadhar
                                        Number</label>
                                    <input type="text" id="aadhar_number" name="aadhar_number"
                                        value="{{ old('aadhar_number') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter Aadhar number">
                                    @error('aadhar_number')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="qualification"
                                        class="block text-sm font-medium text-gray-700">Qualification</label>
                                    <input type="text" id="qualification" name="qualification"
                                        value="{{ old('qualification') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter qualification">
                                    @error('qualification')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="mobile_number" class="block text-sm font-medium text-gray-700">Mobile
                                        Number</label>
                                    <input type="tel" id="mobile_number" name="mobile_number"
                                        value="{{ old('mobile_number') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter mobile number">
                                    @error('mobile_number')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>


                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="border-b pb-4">
                            <h4 class="text-lg font-medium text-gray-700 mb-4">Contact Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                <div>
                                    <label for="local_address" class="block text-sm font-medium text-gray-700">Local
                                        Address</label>
                                    <textarea id="local_address" name="local_address"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter local address" rows="3">{{ old('local_address') }}</textarea>
                                    @error('local_address')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="emergency_contact"
                                        class="block text-sm font-medium text-gray-700">Emergency Contact Name</label>
                                    <input type="text" id="emergency_contact" name="emergency_contact"
                                        value="{{ old('emergency_contact') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter emergency contact name">
                                    @error('emergency_contact')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="father_name" class="block text-sm font-medium text-gray-700">Father
                                        Name</label>
                                    <input type="text" id="father_name" name="father_name"
                                        value="{{ old('father_name') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter father's name">
                                    @error('father_name')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="permanent_address"
                                        class="block text-sm font-medium text-gray-700">Permanent Address</label>
                                    <textarea id="permanent_address" name="permanent_address"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter permanent address" rows="3">{{ old('permanent_address') }}</textarea>
                                    @error('permanent_address')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                    <input type="text" id="city" name="city" value="{{ old('city') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter city">
                                    @error('city')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="state"
                                        class="block text-sm font-medium text-gray-700">State</label>
                                    <input type="text" id="state" name="state" value="{{ old('state') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter state">
                                    @error('state')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="phone"
                                        class="block text-sm font-medium text-gray-700">Phone</label>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter phone number">
                                    @error('phone')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="refer_by" class="block text-sm font-medium text-gray-700">Refer
                                        By</label>
                                    <input type="text" id="refer_by" name="refer_by"
                                        value="{{ old('refer_by') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Refer Bt">
                                    @error('refer_by')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Documents Information -->
                        <div class="border-b pb-4">
                            <h4 class="text-lg font-medium text-gray-700 mb-4">Documents Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                <!-- Aadhar File Upload -->
                                <div>
                                    <label for="aadhar_file"
                                        class="block text-sm font-medium text-gray-700">Aadhar</label>
                                    <input type="file" id="aadhar_file"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                                    <input type="hidden" name="aadhar" id="aadhar">
                                    @error('aadhar')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- PAN Card Upload -->
                                <div>
                                    <label for="pancard_file" class="block text-sm font-medium text-gray-700">PAN
                                        Card</label>
                                    <input type="file" id="pancard_file"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                                    <input type="hidden" name="pancard" id="pancard">
                                    @error('pancard')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Bank Statement Upload -->
                                <div>
                                    <label for="bank_statement_file"
                                        class="block text-sm font-medium text-gray-700">Bank Statement</label>
                                    <input type="file" id="bank_statement_file"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                                    <input type="hidden" name="bank_statement" id="bank_statement">
                                    @error('bank_statement')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Passbook Upload -->
                                <div>
                                    <label for="passbook_file"
                                        class="block text-sm font-medium text-gray-700">Passbook</label>
                                    <input type="file" id="passbook_file"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                                    <input type="hidden" name="passbook" id="passbook">
                                    @error('passbook')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Employment Details -->
                        <div class="border-b pb-4">
                            <h4 class="text-lg font-medium text-gray-700 mb-4">Employment Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                <div>
                                    <label for="type_of_employment"
                                        class="block text-sm font-medium text-gray-700">Type of Employment</label>
                                    <input type="text" id="type_of_employment" name="type_of_employment"
                                        value="{{ old('type_of_employment') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter type of employment">
                                    @error('type_of_employment')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="identification_mark"
                                        class="block text-sm font-medium text-gray-700">Identification Mark
                                        (Optional)</label>
                                    <input type="text" id="identification_mark" name="identification_mark"
                                        value="{{ old('identification_mark') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter identification mark">
                                    @error('identification_mark')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Financial & Insurance Details -->
                        <div class="border-b pb-4">
                            <h4 class="text-lg font-medium text-gray-700 mb-4">Financial & Insurance Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                <div>
                                    <label for="pf_uan" class="block text-sm font-medium text-gray-700">PF UAN
                                        Number (Optional)</label>
                                    <input type="text" id="pf_uan" name="pf_uan" value="{{ old('pf_uan') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter PF UAN number">
                                    @error('pf_uan')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="esic_no" class="block text-sm font-medium text-gray-700">ESIC No.
                                        (Optional)</label>
                                    <input type="text" id="esic_no" name="esic_no"
                                        value="{{ old('esic_no') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter ESIC number">
                                    @error('esic_no')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="pwjby_policy" class="block text-sm font-medium text-gray-700">PWJBY
                                        Policy Number</label>
                                    <input type="text" id="pwjby_policy" name="pwjby_policy"
                                        value="{{ old('pwjby_policy') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter PWJBY policy number">
                                    @error('pwjby_policy')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="pmsby_start_date"
                                        class="block text-sm font-medium text-gray-700">PMSBY Start Date</label>
                                    <input type="date" id="pmsby_start_date" name="pmsby_start_date"
                                        value="{{ old('pmsby_start_date') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                                    @error('pmsby_start_date')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="pmsby_insurance" class="block text-sm font-medium text-gray-700">PMSBY
                                        Insurance Company</label>
                                    <input type="text" id="pmsby_insurance" name="pmsby_insurance"
                                        value="{{ old('pmsby_insurance') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter PMSBY insurance company">
                                    @error('pmsby_insurance')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="agency_number" class="block text-sm font-medium text-gray-700">Agency
                                        Number</label>
                                    <input type="text" id="agency_number" name="agency_number"
                                        value="{{ old('agency_number') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter agency number">
                                    @error('agency_number')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="bank_ifsc" class="block text-sm font-medium text-gray-700">Bank IFSC
                                        Code</label>
                                    <input type="text" id="bank_ifsc" name="bank_ifsc"
                                        value="{{ old('bank_ifsc') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter bank IFSC code">
                                    @error('bank_ifsc')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="bank_account" class="block text-sm font-medium text-gray-700">Bank
                                        Account Number</label>
                                    <input type="text" id="bank_account" name="bank_account"
                                        value="{{ old('bank_account') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter bank account number">
                                    @error('bank_account')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Nominee Details -->
                        <div class="pb-4">
                            <h4 class="text-lg font-medium text-gray-700 mb-4">Nominee Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                <div>
                                    <label for="medical_condition"
                                        class="block text-sm font-medium text-gray-700">Medical Condition</label>
                                    <input type="text" id="medical_condition" name="medical_condition"
                                        value="{{ old('medical_condition') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter medical condition">
                                    @error('medical_condition')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="nationality"
                                        class="block text-sm font-medium text-gray-700">Nationality</label>
                                    <input type="text" id="nationality" name="nationality"
                                        value="{{ old('nationality') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter nationality">
                                    @error('nationality')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="nominee_name" class="block text-sm font-medium text-gray-700">Nominee
                                        Name</label>
                                    <input type="text" id="nominee_name" name="nominee_name"
                                        value="{{ old('nominee_name') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter nominee name">
                                    @error('nominee_name')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="nominee_address"
                                        class="block text-sm font-medium text-gray-700">Nominee Address</label>
                                    <textarea id="nominee_address" name="nominee_address"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter nominee address" rows="3">{{ old('nominee_address') }}</textarea>
                                    @error('nominee_address')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="nominee_relation"
                                        class="block text-sm font-medium text-gray-700">Nominee Relation</label>
                                    <input type="text" id="nominee_relation" name="nominee_relation"
                                        value="{{ old('nominee_relation') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter nominee relation">
                                    @error('nominee_relation')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="nominee_phone" class="block text-sm font-medium text-gray-700">Nominee
                                        Phone</label>
                                    <input type="tel" id="nominee_phone" name="nominee_phone"
                                        value="{{ old('nominee_phone') }}"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter nominee phone">
                                    @error('nominee_phone')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="w-full bg-custom-blue text-white py-2 md:py-3 rounded-lg hover:bg-custom-blue-dark transition-all">Submit
                            Form</button>
                    </form>
                </div>
            </section>
        </div>
    </div>

    @include('partials.js')
</body>
<script>
    function hourlyPay() {
        var hourlypay_rate = document.getElementById("hourly_pay").value;
        console.log(hourlypay_rate);
        document.getElementById("monthly_rate").value = hourlypay_rate * 8 * 28;
    }

    function mothlyPay() {
        var monthly_rate = document.getElementById("monthly_rate").value;
        console.log(monthly_rate);
        document.getElementById("hourly_pay").value = (monthly_rate / 8 / 28).toFixed(2);

    }
</script>

<!-- JavaScript to convert files to base64 -->
<script>
    function convertToBase64(inputId, hiddenId) {
        const input = document.getElementById(inputId);
        const hidden = document.getElementById(hiddenId);
        input.addEventListener('change', function() {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onloadend = function() {
                    hidden.value = reader.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Init for each document field
    convertToBase64('aadhar_file', 'aadhar');
    convertToBase64('pancard_file', 'pancard');
    convertToBase64('bank_statement_file', 'bank_statement');
    convertToBase64('passbook_file', 'passbook');
</script>

</html>
