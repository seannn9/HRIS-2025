<x-layout.guest>
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8 bg-black/8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md -mb-8 relative z-10">
            <img src="{{ asset('img/roc-logo.png') }}" alt="ROC.PH Logo" class="px-3 h-auto w-auto">
        </div>

        <div class="sm:mx-auto sm:w-full sm:max-w-md bg-white shadow-md rounded-lg p-10">
            <h2 class="mt-2 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Login to your account</h2>
            
            <div class="mt-10">
                <form class="space-y-6" action="#" method="POST">
                <div>
                    <label for="employee_number" class="block text-sm/6 font-medium text-gray-900">Employee number</label>
                    <div class="mt-2">
                    <input type="number" name="employee_number" id="employee_number" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-primary/60 sm:text-sm/6">
                    </div>
                </div>
                <div>
                    <label for="email" class="block text-sm/6 font-medium text-gray-900">Email address</label>
                    <div class="mt-2">
                    <input type="email" name="email" id="email" autocomplete="email" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-primary/60 sm:text-sm/6">
                    </div>
                </div>
            
                <div>
                    <div class="flex items-center justify-between">
                    <label for="password" class="block text-sm/6 font-medium text-gray-900">Password</label>
                    <div class="text-sm">
                        <a href="#" class="font-semibold text-primary/60 hover:text-primary-500">Forgot password?</a>
                    </div>
                    </div>
                    <div class="mt-2">
                    <input type="password" name="password" id="password" autocomplete="current-password" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-primary/60 sm:text-sm/6">
                    </div>
                </div>
            
                <div>
                    <button type="submit" class="flex w-full justify-center rounded-md bg-primary px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-primary/80 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary/60">Sign in</button>
                </div>
                </form>
            
                <p class="mt-10 text-center text-sm/6 text-gray-500">
                    Don't have an account? 
                    <a href="#" class="font-semibold text-primary/80 hover:text-primary-500">Sign up now</a>
                </p>
            </div>
        </div>
    </div>
</x-layout.guest>