<x-layout>
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <div class="border-b border-gray-200 pb-6 mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Create Account</h1>
                <p class="text-sm text-gray-500 mt-1">Get started with Currency Exchange Tracker</p>
            </div>

            <form method="POST" action="/register" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name"
                        value="{{ old('name') }}"
                        class="w-full px-4 py-3 text-lg border border-gray-300 rounded focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors @error('name') border-red-500 @enderror" 
                        placeholder="John Doe"
                        required
                        autofocus
                    >
                    @error('name')
                        <p class="text-red-600 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 text-lg border border-gray-300 rounded focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors @error('email') border-red-500 @enderror" 
                        placeholder="you@example.com"
                        required
                    >
                    @error('email')
                        <p class="text-red-600 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        class="w-full px-4 py-3 text-lg border border-gray-300 rounded focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors @error('password') border-red-500 @enderror" 
                        placeholder="••••••••"
                        required
                    >
                    @error('password')
                        <p class="text-red-600 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation"
                        class="w-full px-4 py-3 text-lg border border-gray-300 rounded focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors" 
                        placeholder="••••••••"
                        required
                    >
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3.5 px-6 rounded transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2"
                >
                    Create Account
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-center text-sm text-gray-600">
                    Already have an account?
                    <a href="/login" class="text-blue-600 hover:text-blue-700 font-semibold">Sign in</a>
                </p>
            </div>
        </div>
    </div>
</x-layout>
