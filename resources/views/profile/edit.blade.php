<x-layout>
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('profile.show') }}" class="text-blue-600 hover:text-blue-700 font-semibold text-sm flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Profile
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 mb-6">
            <div class="border-b border-gray-200 pb-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900">Profile Information</h2>
                <p class="text-sm text-gray-500 mt-1">Update your account details</p>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name"
                        value="{{ old('name', $user->name) }}"
                        class="w-full px-4 py-3 text-lg border border-gray-300 rounded focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors @error('name') border-red-500 @enderror" 
                        required
                    >
                    @error('name')
                        <p class="text-red-600 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        value="{{ old('email', $user->email) }}"
                        class="w-full px-4 py-3 text-lg border border-gray-300 rounded focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors @error('email') border-red-500 @enderror" 
                        required
                    >
                    @error('email')
                        <p class="text-red-600 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <button 
                        type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2"
                    >
                        Save Changes
                    </button>
                    <a href="{{ route('profile.show') }}" class="text-gray-600 hover:text-gray-700 font-semibold">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <div class="border-b border-gray-200 pb-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900">Change Password</h2>
                <p class="text-sm text-gray-500 mt-1">Ensure your account is using a strong password</p>
            </div>

            <form method="POST" action="{{ route('profile.password') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">Current Password</label>
                    <input 
                        type="password" 
                        name="current_password" 
                        id="current_password"
                        class="w-full px-4 py-3 text-lg border border-gray-300 rounded focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors @error('current_password') border-red-500 @enderror" 
                        required
                    >
                    @error('current_password')
                        <p class="text-red-600 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        class="w-full px-4 py-3 text-lg border border-gray-300 rounded focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors @error('password') border-red-500 @enderror" 
                        required
                    >
                    @error('password')
                        <p class="text-red-600 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm New Password</label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation"
                        class="w-full px-4 py-3 text-lg border border-gray-300 rounded focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors" 
                        required
                    >
                </div>

                <!-- Submit Button -->
                <div class="flex items-center gap-4 pt-4">
                    <button 
                        type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2"
                    >
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
