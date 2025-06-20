<!DOCTYPE html>
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

            <!-- Edit Workman Section -->
            <section id="edit-workman">
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-lg">
                    <h3 class="text-xl md:text-2xl font-semibold text-gray-800 mb-4 md:mb-6">Edit Employee</h3>

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
                    <form method="POST" action="{{ route('employee.update', ['id' => $employee->id]) }}">

                        @csrf
                        @method('PUT')

                        <!-- Location -->
                        <div class="mb-4">
                            <label for="location_id" class="block text-sm font-medium text-gray-700">Location</label>
                            <select id="location_id" name="location_id"
                                class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}"
                                        {{ $employee->location_id == $location->id ? 'selected' : '' }}>
                                        {{ $location->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Name and Surname -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" id="name" name="name" value="{{ $employee->name }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                    required>
                            </div>
                            <div>
                                <label for="surname" class="block text-sm font-medium text-gray-700">Surname</label>
                                <input type="text" id="surname" name="surname" value="{{ $employee->surname }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                    required>
                            </div>
                        </div>

                        <!-- Sex and DOB -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="sex" class="block text-sm font-medium text-gray-700">Sex</label>
                                <select id="sex" name="sex"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                                    <option value="" {{ is_null($employee->sex) ? 'selected' : '' }}>Select
                                    </option>
                                    <option value="male" {{ $employee->sex == 'male' ? 'selected' : '' }}>Male
                                    </option>
                                    <option value="female" {{ $employee->sex == 'female' ? 'selected' : '' }}>Female
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label for="dob" class="block text-sm font-medium text-gray-700">Date of
                                    Birth</label>
                                <input type="date" id="dob" name="dob" value="{{ $employee->dob }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                        </div>

                        <!-- Blood Group and Designation -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="blood_group" class="block text-sm font-medium text-gray-700">Blood
                                    Group</label>
                                <select id="blood_group" name="blood_group"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                                    <option value="" {{ is_null($employee->blood_group) ? 'selected' : '' }}>
                                        Select</option>
                                    <option value="A+" {{ $employee->blood_group == 'A+' ? 'selected' : '' }}>A+
                                    </option>
                                    <option value="A-" {{ $employee->blood_group == 'A-' ? 'selected' : '' }}>A-
                                    </option>
                                    <option value="B+" {{ $employee->blood_group == 'B+' ? 'selected' : '' }}>B+
                                    </option>
                                    <option value="B-" {{ $employee->blood_group == 'B-' ? 'selected' : '' }}>B-
                                    </option>
                                    <option value="AB+" {{ $employee->blood_group == 'AB+' ? 'selected' : '' }}>AB+
                                    </option>
                                    <option value="AB-" {{ $employee->blood_group == 'AB-' ? 'selected' : '' }}>AB-
                                    </option>
                                    <option value="O+" {{ $employee->blood_group == 'O+' ? 'selected' : '' }}>O+
                                    </option>
                                    <option value="O-" {{ $employee->blood_group == 'O-' ? 'selected' : '' }}>O-
                                    </option>
                                </select>
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
                        </div>

                        <!-- Monthly Rate and Handicapped -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="monthly_rate" class="block text-sm font-medium text-gray-700">Monthly
                                    Rate</label>
                                <input type="number" id="monthly_rate" name="monthly_rate"
                                    value="{{ $employee->monthly_rate }}" onkeyup="mothlyPay()"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                    step="0.01">
                            </div>

                            <div>
                                <label for="hourly_pay" class="block text-sm font-medium text-gray-700">Hourly
                                    Rate</label>
                                <input type="number" id="hourly_pay" name="hourly_pay"
                                    value="{{ $employee->hourly_pay }}" onkeyup="hourlyPay()"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                    step="0.01">
                            </div>

                            <div>
                                <label for="employee_unique_id"
                                    class="block text-sm font-medium text-gray-700">Employee
                                    Id</label>
                                <input type="number" id="employee_unique_id" name="employee_unique_id"
                                    value="{{ $employee->employee_unique_id }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                    placeholder="Enter Employee Id">
                                @error('employee_unique_id')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="handicapped"
                                    class="block text-sm font-medium text-gray-700">Handicapped</label>
                                <select id="handicapped" name="handicapped"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                                    <option value="0" {{ !$employee->handicapped ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ $employee->handicapped ? 'selected' : '' }}>Yes</option>
                                </select>
                            </div>
                        </div>

                        <!-- Allowance Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="basic_pay" class="block text-sm font-medium text-gray-700">Basic
                                    Pay</label>
                                <input type="number" id="basic_pay" name="basic_pay"
                                    value="{{ $employee->basic_pay }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                    step="0.01">
                            </div>

                            <div>
                                <label for="house_rent_allowance"
                                    class="block text-sm font-medium text-gray-700">House Rent Allowance</label>
                                <input type="number" id="house_rent_allowance" name="house_rent_allowance"
                                    value="{{ $employee->house_rent_allowance }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                    step="0.01">
                            </div>

                            <div>
                                <label for="conveyance_allowance"
                                    class="block text-sm font-medium text-gray-700">Conveyance Allowance</label>
                                <input type="number" id="conveyance_allowance" name="conveyance_allowance"
                                    value="{{ $employee->conveyance_allowance }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                    step="0.01">
                            </div>

                            <div>
                                <label for="food_allowance" class="block text-sm font-medium text-gray-700">Food
                                    Allowance</label>
                                <input type="number" id="food_allowance" name="food_allowance"
                                    value="{{ $employee->food_allowance }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                    step="0.01">
                            </div>

                            <div>
                                <label for="site_allowance" class="block text-sm font-medium text-gray-700">Site
                                    Allowance</label>
                                <input type="number" id="site_allowance" name="site_allowance"
                                    value="{{ $employee->site_allowance }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                    step="0.01">
                            </div>

                            <div>
                                <label for="statutory_bonus" class="block text-sm font-medium text-gray-700">Statutory
                                    Bonus</label>
                                <input type="number" id="statutory_bonus" name="statutory_bonus"
                                    value="{{ $employee->statutory_bonus }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                    step="0.01">
                            </div>

                            <div>
                                <label for="retrenchment_allowance"
                                    class="block text-sm font-medium text-gray-700">Retrenchment Allowance</label>
                                <input type="number" id="retrenchment_allowance" name="retrenchment_allowance"
                                    value="{{ $employee->retrenchment_allowance }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                    step="0.01">
                            </div>

                            <div>
                                <label for="medical" class="block text-sm font-medium text-gray-700">Medical</label>
                                <input type="number" id="medical" name="medical" value="{{ $employee->medical }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                    step="0.01">
                            </div>
                        </div>
                        <!-- PAN and Aadhar -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="pan_number" class="block text-sm font-medium text-gray-700">PAN
                                    Number</label>
                                <input type="text" id="pan_number" name="pan_number"
                                    value="{{ $employee->pan_number }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                            <div>
                                <label for="aadhar_number" class="block text-sm font-medium text-gray-700">Aadhar
                                    Number</label>
                                <input type="text" id="aadhar_number" name="aadhar_number"
                                    value="{{ $employee->aadhar_number }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                        </div>

                        <!-- Qualification and Mobile Number -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="qualification"
                                    class="block text-sm font-medium text-gray-700">Qualification</label>
                                <input type="text" id="qualification" name="qualification"
                                    value="{{ $employee->qualification }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                            <div>
                                <label for="mobile_number" class="block text-sm font-medium text-gray-700">Mobile
                                    Number</label>
                                <input type="text" id="mobile_number" name="mobile_number"
                                    value="{{ $employee->mobile_number }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                        </div>

                        <!-- Local Address and Emergency Contact -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="local_address" class="block text-sm font-medium text-gray-700">Local
                                    Address</label>
                                <textarea id="local_address" name="local_address"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">{{ $employee->local_address }}</textarea>
                            </div>
                            <div>
                                <label for="emergency_contact"
                                    class="block text-sm font-medium text-gray-700">Emergency Contact</label>
                                <input type="text" id="emergency_contact" name="emergency_contact"
                                    value="{{ $employee->emergency_contact }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
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

                        <!-- Father Name and Permanent Address -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="father_name" class="block text-sm font-medium text-gray-700">Father's
                                    Name</label>
                                <input type="text" id="father_name" name="father_name"
                                    value="{{ $employee->father_name }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                            <div>
                                <label for="permanent_address"
                                    class="block text-sm font-medium text-gray-700">Permanent Address</label>
                                <textarea id="permanent_address" name="permanent_address"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">{{ $employee->permanent_address }}</textarea>
                            </div>
                        </div>

                        <!-- City and State -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                <input type="text" id="city" name="city" value="{{ $employee->city }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700">State</label>
                                <input type="text" id="state" name="state" value="{{ $employee->state }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                        </div>

                        <!-- Phone and Type of Employment -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                <input type="text" id="phone" name="phone" value="{{ $employee->phone }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                            <div>
                                <label for="type_of_employment" class="block text-sm font-medium text-gray-700">Type
                                    of Employment</label>
                                <input type="text" id="type_of_employment" name="type_of_employment"
                                    value="{{ $employee->type_of_employment }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                        </div>

                        <!-- Identification Mark and PF UAN -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="identification_mark"
                                    class="block text-sm font-medium text-gray-700">Identification Mark</label>
                                <input type="text" id="identification_mark" name="identification_mark"
                                    value="{{ $employee->identification_mark }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                            <div>
                                <label for="pf_uan" class="block text-sm font-medium text-gray-700">PF UAN</label>
                                <input type="text" id="pf_uan" name="pf_uan" value="{{ $employee->pf_uan }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                        </div>

                        <!-- ESIC No and PWJBY Policy -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="esic_no" class="block text-sm font-medium text-gray-700">ESIC No</label>
                                <input type="text" id="esic_no" name="esic_no"
                                    value="{{ $employee->esic_no }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                            <div>
                                <label for="pwjby_policy" class="block text-sm font-medium text-gray-700">PWJBY
                                    Policy</label>
                                <input type="text" id="pwjby_policy" name="pwjby_policy"
                                    value="{{ $employee->pwjby_policy }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                        </div>

                        <!-- PMSBY Start Date and PMSBY Insurance -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="pmsby_start_date" class="block text-sm font-medium text-gray-700">PMSBY
                                    Start Date</label>
                                <input type="date" id="pmsby_start_date" name="pmsby_start_date"
                                    value="{{ $employee->pmsby_start_date }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                            <div>
                                <label for="pmsby_insurance" class="block text-sm font-medium text-gray-700">PMSBY
                                    Insurance</label>
                                <input type="text" id="pmsby_insurance" name="pmsby_insurance"
                                    value="{{ $employee->pmsby_insurance }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                        </div>

                        <!-- Agency Number and Bank IFSC -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="agency_number" class="block text-sm font-medium text-gray-700">Agency
                                    Number</label>
                                <input type="text" id="agency_number" name="agency_number"
                                    value="{{ $employee->agency_number }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                            <div>
                                <label for="bank_ifsc" class="block text-sm font-medium text-gray-700">Bank
                                    IFSC</label>
                                <input type="text" id="bank_ifsc" name="bank_ifsc"
                                    value="{{ $employee->bank_ifsc }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                        </div>

                        <!-- Bank Account and Medical Condition -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="bank_account" class="block text-sm font-medium text-gray-700">Bank
                                    Account</label>
                                <input type="text" id="bank_account" name="bank_account"
                                    value="{{ $employee->bank_account }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                            <div>
                                <label for="medical_condition" class="block text-sm font-medium text-gray-700">Medical
                                    Condition</label>
                                <input type="text" id="medical_condition" name="medical_condition"
                                    value="{{ $employee->medical_condition }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                        </div>

                        <!-- Nationality and Nominee Name -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="nationality"
                                    class="block text-sm font-medium text-gray-700">Nationality</label>
                                <input type="text" id="nationality" name="nationality"
                                    value="{{ $employee->nationality }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                            <div>
                                <label for="nominee_name" class="block text-sm font-medium text-gray-700">Nominee
                                    Name</label>
                                <input type="text" id="nominee_name" name="nominee_name"
                                    value="{{ $employee->nominee_name }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                        </div>

                        <!-- Nominee Address and Nominee Relation -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="nominee_address" class="block text-sm font-medium text-gray-700">Nominee
                                    Address</label>
                                <textarea id="nominee_address" name="nominee_address"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">{{ $employee->nominee_address }}</textarea>
                            </div>
                            <div>
                                <label for="nominee_relation" class="block text-sm font-medium text-gray-700">Nominee
                                    Relation</label>
                                <input type="text" id="nominee_relation" name="nominee_relation"
                                    value="{{ $employee->nominee_relation }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                        </div>

                        <!-- Nominee Phone -->
                        <div class="mb-4">
                            <label for="nominee_phone" class="block text-sm font-medium text-gray-700">Nominee
                                Phone</label>
                            <input type="text" id="nominee_phone" name="nominee_phone"
                                value="{{ $employee->nominee_phone }}"
                                class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                        </div>

                        <div>
                            <label for="refer_by" class="block text-sm font-medium text-gray-700">Refer By</label>
                            <input type="text" id="refer_by" name="refer_by" value="{{ old('refer_by') }}"
                                class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                placeholder="Refer Bt">
                            @error('refer_by')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="w-full bg-custom-blue text-white py-2 md:py-3 rounded-lg hover:bg-custom-blue-dark transition-all">
                            Update Workman
                        </button>
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
