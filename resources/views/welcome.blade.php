<!DOCTYPE html>
<html lang="en">
@include('partials.head')

<body class="bg-gradient-to-br from-gray-50 to-gray-200 font-sans antialiased">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
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

            <!-- New Workmen Joining & Screening Form -->
            <section id="new-workmen">
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-lg transform hover:scale-[1.01] transition-all">
                    <h3 class="text-xl md:text-2xl font-semibold text-gray-800 mb-4 md:mb-6">New Workmen Joining &
                        Screening Form</h3>
                    <form class="grid grid-cols-1 gap-4 md:gap-6">
                        <!-- Personal Details -->
                        <div class="border-b pb-4">
                            <h4 class="text-lg font-medium text-gray-700 mb-4">Personal Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" id="name"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter name">
                                </div>
                                <div>
                                    <label for="surname"
                                        class="block text-sm font-medium text-gray-700">Surname</label>
                                    <input type="text" id="surname"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter surname">
                                </div>
                                <div>
                                    <label for="sex" class="block text-sm font-medium text-gray-700">Sex
                                        (Male/Female)</label>
                                    <select id="sex"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                                        <option value="">Select Sex</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="dob" class="block text-sm font-medium text-gray-700">Date of Birth
                                        (General/OBC/SC/ST)</label>
                                    <input type="date" id="dob"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                                </div>
                                <div>
                                    <label for="blood-group" class="block text-sm font-medium text-gray-700">Blood
                                        Group</label>
                                    <select id="blood-group"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                                        <option value="">Select Blood Group</option>
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O-</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="designation" class="block text-sm font-medium text-gray-700">Designation
                                        (HSW/SSW/USW)</label>
                                    <select id="designation"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                                        <option value="">Select Designation</option>
                                        <option value="HSW">HSW</option>
                                        <option value="SSW">SSW</option>
                                        <option value="USW">USW</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="monthly-rate" class="block text-sm font-medium text-gray-700">Monthly
                                        Rate</label>
                                    <input type="number" id="monthly-rate"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter monthly rate">
                                </div>
                                <div>
                                    <label for="handicapped" class="block text-sm font-medium text-gray-700">Handicapped
                                        (True/False)</label>
                                    <select id="handicapped"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                                        <option value="">Select</option>
                                        <option value="true">True</option>
                                        <option value="false">False</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Identification -->
                        <div class="border-b pb-4">
                            <h4 class="text-lg font-medium text-gray-700 mb-4">Identification</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                <div>
                                    <label for="pan-number" class="block text-sm font-medium text-gray-700">PAN
                                        Number</label>
                                    <input type="text" id="pan-number"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter PAN number">
                                </div>
                                <div>
                                    <label for="aadhar-number" class="block text-sm font-medium text-gray-700">Aadhar
                                        Number</label>
                                    <input type="text" id="aadhar-number"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter Aadhar number">
                                </div>
                                <div>
                                    <label for="qualification"
                                        class="block text-sm font-medium text-gray-700">Qualification</label>
                                    <input type="text" id="qualification"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter qualification">
                                </div>
                                <div>
                                    <label for="mobile-number" class="block text-sm font-medium text-gray-700">Mobile
                                        Number</label>
                                    <input type="tel" id="mobile-number"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter mobile number">
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="border-b pb-4">
                            <h4 class="text-lg font-medium text-gray-700 mb-4">Contact Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                <div>
                                    <label for="local-address" class="block text-sm font-medium text-gray-700">Local
                                        Address</label>
                                    <textarea id="local-address"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter local address" rows="3"></textarea>
                                </div>
                                <div>
                                    <label for="emergency-contact"
                                        class="block text-sm font-medium text-gray-700">Emergency Contact Name</label>
                                    <input type="text" id="emergency-contact"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter emergency contact name">
                                </div>
                                <div>
                                    <label for="father-name" class="block text-sm font-medium text-gray-700">Father
                                        Name</label>
                                    <input type="text" id="father-name"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter father's name">
                                </div>
                                <div>
                                    <label for="permanent-address"
                                        class="block text-sm font-medium text-gray-700">Permanent Address</label>
                                    <textarea id="permanent-address"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter permanent address" rows="3"></textarea>
                                </div>
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                    <input type="text" id="city"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter city">
                                </div>
                                <div>
                                    <label for="state"
                                        class="block text-sm font-medium text-gray-700">State</label>
                                    <input type="text" id="state"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter state">
                                </div>
                                <div>
                                    <label for="phone"
                                        class="block text-sm font-medium text-gray-700">Phone</label>
                                    <input type="tel" id="phone"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter phone number">
                                </div>
                            </div>
                        </div>

                        <!-- Employment Details -->
                        <div class="border-b pb-4">
                            <h4 class="text-lg font-medium text-gray-700 mb-4">Employment Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                <div>
                                    <label for="type-of-employment"
                                        class="block text-sm font-medium text-gray-700">Type of Employment</label>
                                    <input type="text" id="type-of-employment"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter type of employment">
                                </div>
                                <div>
                                    <label for="identification-mark"
                                        class="block text-sm font-medium text-gray-700">Identification Mark
                                        (Optional)</label>
                                    <input type="text" id="identification-mark"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter identification mark">
                                </div>
                            </div>
                        </div>

                        <!-- Financial & Insurance Details -->
                        <div class="border-b pb-4">
                            <h4 class="text-lg font-medium text-gray-700 mb-4">Financial & Insurance Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                <div>
                                    <label for="pf-uan" class="block text-sm font-medium text-gray-700">PF UAN
                                        Number (Optional)</label>
                                    <input type="text" id="pf-uan"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter PF UAN number">
                                </div>
                                <div>
                                    <label for="esic-no" class="block text-sm font-medium text-gray-700">ESIC No.
                                        (Optional)</label>
                                    <input type="text" id="esic-no"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter ESIC number">
                                </div>
                                <div>
                                    <label for="pwjby-policy" class="block text-sm font-medium text-gray-700">PWJBY
                                        Policy Number</label>
                                    <input type="text" id="pwjby-policy"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter PWJBY policy number">
                                </div>
                                <div>
                                    <label for="pmsby-start-date"
                                        class="block text-sm font-medium text-gray-700">PMSBY Start Date</label>
                                    <input type="date" id="pmsby-start-date"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all">
                                </div>
                                <div>
                                    <label for="pmsby-insurance" class="block text-sm font-medium text-gray-700">PMSBY
                                        Insurance Company</label>
                                    <input type="text" id="pmsby-insurance"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter PMSBY insurance company">
                                </div>
                                <div>
                                    <label for="agency-number" class="block text-sm font-medium text-gray-700">Agency
                                        Number</label>
                                    <input type="text" id="agency-number"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter agency number">
                                </div>
                                <div>
                                    <label for="bank-ifsc" class="block text-sm font-medium text-gray-700">Bank IFSC
                                        Code</label>
                                    <input type="text" id="bank-ifsc"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter bank IFSC code">
                                </div>
                                <div>
                                    <label for="bank-account" class="block text-sm font-medium text-gray-700">Bank
                                        Account Number</label>
                                    <input type="text" id="bank-account"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter bank account number">
                                </div>
                            </div>
                        </div>

                        <!-- Nominee Details -->
                        <div class="pb-4">
                            <h4 class="text-lg font-medium text-gray-700 mb-4">Nominee Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                <div>
                                    <label for="medical-condition"
                                        class="block text-sm font-medium text-gray-700">Medical Condition</label>
                                    <input type="text" id="medical-condition"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter medical condition">
                                </div>
                                <div>
                                    <label for="nationality"
                                        class="block text-sm font-medium text-gray-700">Nationality</label>
                                    <input type="text" id="nationality"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter nationality">
                                </div>
                                <div>
                                    <label for="nominee-name" class="block text-sm font-medium text-gray-700">Nominee
                                        Name</label>
                                    <input type="text" id="nominee-name"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter nominee name">
                                </div>
                                <div>
                                    <label for="nominee-address"
                                        class="block text-sm font-medium text-gray-700">Nominee Address</label>
                                    <textarea id="nominee-address"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter nominee address" rows="3"></textarea>
                                </div>
                                <div>
                                    <label for="nominee-relation"
                                        class="block text-sm font-medium text-gray-700">Nominee Relation</label>
                                    <input type="text" id="nominee-relation"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter nominee relation">
                                </div>
                                <div>
                                    <label for="nominee-phone" class="block text-sm font-medium text-gray-700">Nominee
                                        Phone</label>
                                    <input type="tel" id="nominee-phone"
                                        class="mt-1 w-full p-2 md:p-3 border rounded-lg focus:ring-2 focus:ring-custom-blue focus:border-custom-blue transition-all"
                                        placeholder="Enter nominee phone">
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


</body>

@include('partials.js')

</html>
