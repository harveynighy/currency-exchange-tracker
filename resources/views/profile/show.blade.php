<x-layout>
    <div class="max-w-4xl mx-auto">
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 px-6 py-4 mb-6 rounded-r">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h4 class="text-sm font-semibold text-green-800">Success</h4>
                        <p class="text-sm text-green-700 mt-1">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 mb-6">
            <div class="flex items-center justify-between border-b border-gray-200 pb-6 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Profile</h1>
                    <p class="text-sm text-gray-500 mt-1">Manage your account information</p>
                </div>
                <a href="{{ route('profile.edit') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-5 rounded transition-colors duration-200">
                    Edit Profile
                </a>
            </div>

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-1">
                        <h3 class="text-sm font-semibold text-gray-700">Full Name</h3>
                        <p class="text-xs text-gray-500 mt-1">Your display name</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-lg text-gray-900">{{ $user->name }}</p>
                    </div>
                </div>

                <div class="border-t border-gray-200"></div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-1">
                        <h3 class="text-sm font-semibold text-gray-700">Email Address</h3>
                        <p class="text-xs text-gray-500 mt-1">Your account email</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-lg text-gray-900">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="border-t border-gray-200"></div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-1">
                        <h3 class="text-sm font-semibold text-gray-700">Member Since</h3>
                        <p class="text-xs text-gray-500 mt-1">Account creation date</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-lg text-gray-900">{{ $user->created_at->format('F j, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
