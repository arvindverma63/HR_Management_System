<!DOCTYPE html>
<html lang="en">
@include('partials.head')

<body class="bg-gradient-to-br from-gray-50 to-gray-200 font-sans antialiased">
    <div class="flex min-h-screen">
        @include('partials.sidebar')

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
                    <h3 class="text-xl md:text-2xl font-semibold text-gray-800 mb-4 md:mb-6">Edit Workman</h3>

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

                    <form method="POST" action="{{ route('workmen.update', $workman) }}">
                        @csrf
                        @method('PUT')

                        <!-- Location -->
                        <div class="mb-4">
                            <label for="location_id" class="block text-sm font-medium text-gray-700">Location</label>
                            <select id="location_id" name="location_id"
                                class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}"
                                        {{ $workman->location_id == $location->id ? 'selected' : '' }}>
                                        {{ $location->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Name and Surname -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" id="name" name="name" value="{{ $workman->name }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                    required>
                            </div>
                            <div>
                                <label for="surname" class="block text-sm font-medium text-gray-700">Surname</label>
                                <input type="text" id="surname" name="surname" value="{{ $workman->surname }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                    required>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" id="email" name="email" value="{{ $workman->email }}"
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
                                    <option value="" {{ is_null($workman->sex) ? 'selected' : '' }}>Select
                                    </option>
                                    <option value="male" {{ $workman->sex == 'male' ? 'selected' : '' }}>Male
                                    </option>
                                    <option value="female" {{ $workman->sex == 'female' ? 'selected' : '' }}>Female
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label for="dob" class="block text-sm font-medium text-gray-700">Date of
                                    Birth</label>
                                <input type="date" id="dob" name="dob" value="{{ $workman->dob }}"
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
                                    <option value="" {{ is_null($workman->blood_group) ? 'selected' : '' }}>
                                        Select</option>
                                    <option value="A+" {{ $workman->blood_group == 'A+' ? 'selected' : '' }}>A+
                                    </option>
                                    <option value="A-" {{ $workman->blood_group == 'A-' ? 'selected' : '' }}>A-
                                    </option>
                                    <option value="B+" {{ $workman->blood_group == 'B+' ? 'selected' : '' }}>B+
                                    </option>
                                    <option value="B-" {{ $workman->blood_group == 'B-' ? 'selected' : '' }}>B-
                                    </option>
                                    <option value="AB+" {{ $workman->blood_group == 'AB+' ? 'selected' : '' }}>AB+
                                    </option>
                                    <option value="AB-" {{ $workman->blood_group == 'AB-' ? 'selected' : '' }}>AB-
                                    </option>
                                    <option value="O+" {{ $workman->blood_group == 'O+' ? 'selected' : '' }}>O+
                                    </option>
                                    <option value="O-" {{ $workman->blood_group == 'O-' ? 'selected' : '' }}>O-
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label for="designation"
                                    class="block text-sm font-medium text-gray-700">Designation</label>
                                <select id="designation" name="designation"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">

                                    <!-- ✅ Proper placeholder: just says "Select" -->
                                    <option value=""
                                        {{ $desig = App\Models\Designation::find($workman->designation) ?? "Select" }}>
                                        {{ $desig->name ?? "----Select------" }}
                                    </option>

                                    <!-- ✅ Loop, mark selected properly -->
                                    @foreach ($designations as $d)
                                        <option value="{{ $d->id }}"
                                            {{ old('designation', $workman->designation->id ?? '') == $d->id ? 'selected' : '' }}>
                                            {{ $d->name }}
                                        </option>
                                    @endforeach

                                </select>


                            </div>
                        </div>

                        <!-- Monthly Rate and Handicapped -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="monthly_rate" class="block text-sm font-medium text-gray-700">Monthly
                                    Rate</label>
                                <input type="number" id="monthly_rate" name="monthly_rate"
                                    value="{{ $workman->monthly_rate }}" onkeyup="mothlyPay()"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                    step="0.01">
                            </div>

                            <div>
                                <label for="hourly_pay" class="block text-sm font-medium text-gray-700">Hourly
                                    Rate</label>
                                <input type="number" id="hourly_pay" name="hourly_pay"
                                    value="{{ $workman->hourly_pay }}" onkeyup="hourlyPay()"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                    step="0.01">
                            </div>
                            <div>
                                <label for="da" class="block text-sm font-medium text-gray-700">DA</label>
                                <input type="number" id="da" name="da" value="{{ $workman->da }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                    placeholder="Enter DA">
                                @error('da')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="workman_unique_id" class="block text-sm font-medium text-gray-700">HR
                                    Id</label>
                                <input type="number" id="workman_unique_id" name="workman_unique_id"
                                    value="{{ $workman->workman_unique_id }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                    placeholder="Enter HR Id">
                                @error('da')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="handicapped"
                                    class="block text-sm font-medium text-gray-700">Handicapped</label>
                                <select id="handicapped" name="handicapped"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                                    <option value="0" {{ !$workman->handicapped ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ $workman->handicapped ? 'selected' : '' }}>Yes</option>
                                </select>
                            </div>
                        </div>

                        <!-- PAN and Aadhar -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="pan_number" class="block text-sm font-medium text-gray-700">PAN
                                    Number</label>
                                <input type="text" id="pan_number" name="pan_number"
                                    value="{{ $workman->pan_number }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                            <div>
                                <label for="aadhar_number" class="block text-sm font-medium text-gray-700">Aadhar
                                    Number</label>
                                <input type="text" id="aadhar_number" name="aadhar_number"
                                    value="{{ $workman->aadhar_number }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                        </div>

                        <!-- Qualification and Mobile Number -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="qualification"
                                    class="block text-sm font-medium text-gray-700">Qualification</label>
                                <input type="text" id="qualification" name="qualification"
                                    value="{{ $workman->qualification }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                            <div>
                                <label for="mobile_number" class="block text-sm font-medium text-gray-700">Mobile
                                    Number</label>
                                <input type="text" id="mobile_number" name="mobile_number"
                                    value="{{ $workman->mobile_number }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                        </div>

                        <!-- Local Address and Emergency Contact -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="local_address" class="block text-sm font-medium text-gray-700">Local
                                    Address</label>
                                <textarea id="local_address" name="local_address"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">{{ $workman->local_address }}</textarea>
                            </div>
                            <div>
                                <label for="emergency_contact"
                                    class="block text-sm font-medium text-gray-700">Emergency Contact</label>
                                <input type="text" id="emergency_contact" name="emergency_contact"
                                    value="{{ $workman->emergency_contact }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                        </div>

                        <!-- Father Name and Permanent Address -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="father_name" class="block text-sm font-medium text-gray-700">Father's
                                    Name</label>
                                <input type="text" id="father_name" name="father_name"
                                    value="{{ $workman->father_name }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                            <div>
                                <label for="permanent_address"
                                    class="block text-sm font-medium text-gray-700">Permanent Address</label>
                                <textarea id="permanent_address" name="permanent_address"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">{{ $workman->permanent_address }}</textarea>
                            </div>
                        </div>

                        <!-- City and State -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                <input type="text" id="city" name="city" value="{{ $workman->city }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700">State</label>
                                <input type="text" id="state" name="state" value="{{ $workman->state }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                        </div>

                        <!-- Phone and Type of Employment -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                <input type="text" id="phone" name="phone" value="{{ $workman->phone }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                            <div>
                                <label for="type_of_employment" class="block text-sm font-medium text-gray-700">Type
                                    of Employment</label>
                                <input type="text" id="type_of_employment" name="type_of_employment"
                                    value="{{ $workman->type_of_employment }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                        </div>

                        <!-- Identification Mark and PF UAN -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="identification_mark"
                                    class="block text-sm font-medium text-gray-700">Identification Mark</label>
                                <input type="text" id="identification_mark" name="identification_mark"
                                    value="{{ $workman->identification_mark }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                            <div>
                                <label for="pf_uan" class="block text-sm font-medium text-gray-700">PF UAN</label>
                                <input type="text" id="pf_uan" name="pf_uan" value="{{ $workman->pf_uan }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                        </div>

                        <!-- ESIC No and PWJBY Policy -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="esic_no" class="block text-sm font-medium text-gray-700">ESIC No</label>
                                <input type="text" id="esic_no" name="esic_no" value="{{ $workman->esic_no }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                            <div>
                                <label for="pwjby_policy" class="block text-sm font-medium text-gray-700">PWJBY
                                    Policy</label>
                                <input type="text" id="pwjby_policy" name="pwjby_policy"
                                    value="{{ $workman->pwjby_policy }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                        </div>

                        <!-- PMSBY Start Date and PMSBY Insurance -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="pmsby_start_date" class="block text-sm font-medium text-gray-700">PMSBY
                                    Start Date</label>
                                <input type="date" id="pmsby_start_date" name="pmsby_start_date"
                                    value="{{ $workman->pmsby_start_date }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                            <div>
                                <label for="pmsby_insurance" class="block text-sm font-medium text-gray-700">PMSBY
                                    Insurance</label>
                                <input type="text" id="pmsby_insurance" name="pmsby_insurance"
                                    value="{{ $workman->pmsby_insurance }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                        </div>

                        <!-- Agency Number and Bank IFSC -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="agency_number" class="block text-sm font-medium text-gray-700">Agency
                                    Number</label>
                                <input type="text" id="agency_number" name="agency_number"
                                    value="{{ $workman->agency_number }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                            <div>
                                <label for="bank_ifsc" class="block text-sm font-medium text-gray-700">Bank
                                    IFSC</label>
                                <input type="text" id="bank_ifsc" name="bank_ifsc"
                                    value="{{ $workman->bank_ifsc }}"
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

                        <!-- Bank Account and Medical Condition -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="bank_account" class="block text-sm font-medium text-gray-700">Bank
                                    Account</label>
                                <input type="text" id="bank_account" name="bank_account"
                                    value="{{ $workman->bank_account }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                            <div>
                                <label for="medical_condition" class="block text-sm font-medium text-gray-700">Medical
                                    Condition</label>
                                <input type="text" id="medical_condition" name="medical_condition"
                                    value="{{ $workman->medical_condition }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                        </div>

                        <!-- Nationality and Nominee Name -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="nationality"
                                    class="block text-sm font-medium text-gray-700">Nationality</label>
                                <input type="text" id="nationality" name="nationality"
                                    value="{{ $workman->nationality }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                            <div>
                                <label for="nominee_name" class="block text-sm font-medium text-gray-700">Nominee
                                    Name</label>
                                <input type="text" id="nominee_name" name="nominee_name"
                                    value="{{ $workman->nominee_name }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                        </div>

                        <!-- Nominee Address and Nominee Relation -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="nominee_address" class="block text-sm font-medium text-gray-700">Nominee
                                    Address</label>
                                <textarea id="nominee_address" name="nominee_address"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">{{ $workman->nominee_address }}</textarea>
                            </div>
                            <div>
                                <label for="nominee_relation" class="block text-sm font-medium text-gray-700">Nominee
                                    Relation</label>
                                <input type="text" id="nominee_relation" name="nominee_relation"
                                    value="{{ $workman->nominee_relation }}"
                                    class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                            </div>
                        </div>

                        <!-- Nominee Phone -->
                        <div class="mb-4">
                            <label for="nominee_phone" class="block text-sm font-medium text-gray-700">Nominee
                                Phone</label>
                            <input type="text" id="nominee_phone" name="nominee_phone"
                                value="{{ $workman->nominee_phone }}"
                                class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
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
