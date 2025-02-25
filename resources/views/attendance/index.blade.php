<x-layout.auth>
    <div class="bg-primary/20 p-6">
        <div class="max-w-6xl mx-auto bg-white rounded-lg shadow-lg p-8">
            <div class="flex gap-4 mb-8 divide-gray-200 items-center">
                <h1 class="text-2xl font-bold text-primary">Attendance</h1>
                -
                <p id="clock" class="text-2xl font-bold text-primary"></p>
            </div>
            @if(session('success'))
            <p>{{ session('success') }}</p>
            <img src="{{ Storage::disk('public')->url(session('file')) }}" alt="Uploaded File">
        @endif
        @if(session('error'))
            <p>{{ session('error') }}</p>
        @endif
            
            <form class="space-y-8" action="{{ route('attendance.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div>
                    <x-form.input type="number" name="employee_id" label="Employee ID"
                        value="{{ $employee->id }}"
                        required />
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-form.select 
                        label="Shift"
                        name="shift_type"
                        selected="{{ old('shift_type') ?? $currentShiftType }}"
                        :options="$shiftTypes" required />
                    
                    <x-form.select 
                        label="Attendance Type"
                        name="type"
                        selected="{{ old('type') ?? $currentAttendanceType }}"
                        :options="$attendanceTypes" required />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-form.select 
                        label="Work mode"
                        name="work_mode"
                        selected="{{ old('work_mode') }}"
                        :options="$workModes" required />
    
                    <x-form.select 
                        label="Employment Program Type"
                        name="employment_type"
                        selected="{{ old('employment_type') ?? $employee->employment_type->value }}"
                        :options="$employmentTypes" required />
                </div>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-form.select 
                        label="Department"
                        name="department"
                        selected="{{ old('department') ?? $employee->department->value }}"
                        :options="$departments" required />
                    
                    <x-form.select 
                        label="Department Team"
                        name="department_team"
                        :options="$departmentTeams" required />
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
                    <x-form.label name="screenshot_workstation_selfie" label="Acceptable Workstation Selfie" />
                    <div class="mt-2">
                        <input id="screenshot_workstation_selfie" name="screenshot_workstation_selfie" type="file" class="sr-only" accept=".jpeg,.jpg,.png" required>
                        <x-button text="Choose File" class="px-4 py-2" type="button" onclick="document.getElementById('screenshot_workstation_selfie').click();" />
                    </div>
                </div>

                <div>
                    <x-form.label name="screenshot_cgc_chat" label="Acceptable Company Group Chat Screenshot" />
                    <div class="mt-2">
                        <input id="screenshot_cgc_chat" name="screenshot_cgc_chat" type="file" class="sr-only" accept=".jpeg,.jpg,.png" required>
                        <x-button text="Choose File" class="px-4 py-2" type="button" onclick="document.getElementById('screenshot_cgc_chat').click();" />
                    </div>
                </div>

                <div>
                    <x-form.label name="screenshot_department_chat" label="Acceptable Department Chat Screenshot" />
                    <div class="mt-2">
                        <input id="screenshot_department_chat" name="screenshot_department_chat" type="file" class="sr-only" accept=".jpeg,.jpg,.png" required>
                        <x-button text="Choose File" class="px-4 py-2" type="button" onclick="document.getElementById('screenshot_department_chat').click();" />
                    </div>
                </div>
                
                <div>
                    <x-form.label name="screenshot_team_chat" label="Acceptable Team Chat Screenshot" />
                    <div class="mt-2">
                        <input id="screenshot_team_chat" name="screenshot_team_chat" type="file" class="sr-only" accept=".jpeg,.jpg,.png" required>
                        <x-button text="Choose File" class="px-4 py-2" type="button" onclick="document.getElementById('screenshot_team_chat').click();" />
                    </div>
                </div>
                
                <div>
                    <x-form.label name="screenshot_group_chat" label="Acceptable Group Chat Screenshot" />
                    <div class="mt-2">
                        <input id="screenshot_group_chat" name="screenshot_group_chat" type="file" class="sr-only" accept=".jpeg,.jpg,.png" required>
                        <x-button text="Choose File" class="px-4 py-2" type="button" onclick="document.getElementById('screenshot_group_chat').click();" />
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
                    <input type="checkbox" class="h-4 w-4" required>
                    <label class="text-sm">I have read and agree to the <a href="#"
                            class="text-primary underline">Terms and Conditions</a> and <a href="#"
                            class="text-primary underline">Privacy Policy</a></label>
                </div>

                <x-button text="Submit" class="px-4 py-2" type="submit" />
            </form>
        </div>
    </div>
</x-layout.guest>

<script>
    function updateClock() {
        const now = new Date();
        let hours = now.getHours();
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12 || 12;
        document.getElementById('clock').textContent = `${hours}:${minutes}:${seconds} ${ampm}`;
    }
    
    setInterval(updateClock, 1000);
    updateClock();
</script>