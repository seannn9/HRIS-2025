<x-layout.auth>
    <div class="bg-primary/20 p-6">
        <div class="max-w-6xl mx-auto bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-2xl font-bold text-primary mb-8">Attendance</h1>

            <div class="flex flex-col md:flex-row gap-6 mb-8">
                <!-- Left Side - Buttons -->
                <div class="flex flex-col gap-4 md:w-1/4 mt-2">
                    <x-button text="MORNING SHIFT TIME-IN" class="px-4 py-2" />
                    <x-button text="MORNING SHIFT TIME-OUT" containerColor="accent2" class="px-4 py-2" />
                    <x-button text="AFTERNOON SHIFT TIME-IN" containerColor="accent1" class="px-4 py-2" />
                    <x-button text="AFTERNOON SHIFT TIME-OUT" containerColor="accent3" class="px-4 py-2" />
                </div>

                <!-- Right Side - Form -->
                <div class="md:w-3/4">
                    <form class="space-y-4">
                        <div>
                            <x-form.input type="number" name="employee-number" label="Employee ID number" required />
                        </div>

                        {{-- <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <x-form.input type="text" name="first-name" label="First name" required />
                            <x-form.input type="text" name="middle-name" label="Middle name" />
                            <x-form.input type="text" name="last-name" label="Last name" required />
                        </div> --}}

                        {{-- <div>
                            <x-form.input type="email" name="email" id="email" label="Email" autocomplete="email"
                                required />
                        </div> --}}

                        <x-form.select 
                            label="Employment Program Type"
                            name="employment_type"
                            :options="[
                                'k12' => 'K12 Work Immersion',
                                'intern' => 'College Internship',
                                'apprentice' => 'Graduate Apprenticeship'
                            ]" required />


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-form.select 
                                label="Department"
                                name="department"
                                :options="[
                                    'manage' => 'Management',
                                    'digital' => 'Digital Operations',
                                ]" required />
                            
                            <x-form.select 
                                label="Department Team"
                                name="department_team"
                                :options="[
                                    'corp' => 'Coporate Services',
                                    'client' => 'Client Services',
                                    'mmt' => 'Creative Multimedia',
                                    'webdev' => 'Web & Mobile Development',
                                ]" required />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-form.select 
                                label="Department Role"
                                name="department"
                                :options="[
                                    'tl' => 'Team Leader',
                                    'gl' => 'Group Leader',
                                    'member' => 'Group Member',
                                ]" required />
                            
                            <x-form.select 
                                label="Department Team Group Assigned"
                                name="department_team"
                                :options="[
                                    '1' => 'Group 1',
                                    '2' => 'Group 2',
                                    '3' => 'Group 3',
                                    '4' => 'Group 4',
                                    '5' => 'Group 5',
                                    '6' => 'Group 6',
                                    '7' => 'Group 7',
                                    '8' => 'Group 8',
                                ]" required />
                        </div>

                        <div>
                            <x-form.label name="selfie-proof-pic" label="Acceptable Workstation Selfie" />
                            <div class="flex gap-4 mt-2">
                                <x-button text="📸 Selfie Capture" class="px-4 py-2" />
                                <span class="self-center">or</span>
                                <input id="selfie-proof-pic" name="selfie-proof-pic" type="file" class="sr-only">
                                <x-button text="Choose File" class="px-4 py-2" type="button"  onclick="document.getElementById('selfie-proof-pic').click();" />
                            </div>
                        </div>

                        <div>
                            <x-form.label name="cgc-proof-pic" label="Acceptable Company Group Chat Screenshot" />
                            <div class="mt-2">
                                <input id="cgc-proof-pic" name="cgc-proof-pic" type="file" class="sr-only">
                                <x-button text="Choose File" class="px-4 py-2" type="button"  onclick="document.getElementById('cgc-proof-pic').click();" />
                            </div>
                        </div>

                        <div>
                            <x-form.label name="dept-proof-pic" label="Acceptable Department Chat Screenshot" />
                            <div class="mt-2">
                                <input id="dept-proof-pic" name="dept-proof-pic" type="file" class="sr-only">
                                <x-button text="Choose File" class="px-4 py-2" type="button"  onclick="document.getElementById('dept-proof-pic').click();" />
                            </div>
                        </div>
                        
                        <div>
                            <x-form.label name="team-proof-pic" label="Acceptable Team Chat Screenshot" />
                            <div class="mt-2">
                                <input id="team-proof-pic" name="team-proof-pic" type="file" class="sr-only">
                                <x-button text="Choose File" class="px-4 py-2" type="button"  onclick="document.getElementById('team-proof-pic').click();" />
                            </div>
                        </div>
                        
                        <div>
                            <x-form.label name="group-proof-pic" label="Acceptable Group Chat Screenshot" />
                            <div class="mt-2">
                                <input id="group-proof-pic" name="group-proof-pic" type="file" class="sr-only">
                                <x-button text="Choose File" class="px-4 py-2" type="button"  onclick="document.getElementById('group-proof-pic').click();" />
                            </div>
                        </div>

                        <div class="text-sm text-gray-600">
                            <p class="mb-4">After submitting this form, you can check if a ticket has been generated in
                                the "My Tickets" section. Please ensure that all necessary attachments are included. If
                                any are missing, kindly reply to the ticket and attach the required files to ensure
                                proper documentation.</p>
                            <p>The time and date of submission will be recorded based on when the form was submitted.
                            </p>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" class="h-4 w-4">
                            <label class="text-sm">I have read and agree to the <a href="#"
                                    class="text-primary underline">Terms and Conditions</a> and <a href="#"
                                    class="text-primary underline">Privacy Policy</a></label>
                        </div>

                        <x-button text="Choose File" class="px-4 py-2" type="submit" required />
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout.guest>

<script>
    function dropdown() {
        return {
            options: [
                { name: 'Wade Cooper', image: 'https://images.unsplash.com/photo-1491528323818-fdd1faba62cc?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80' }
            ],
            selectedOption: null,
            highlightedIndex: null,
            selectOption(option) {
                this.selectedOption = option;
            }
        };
    }
</script>