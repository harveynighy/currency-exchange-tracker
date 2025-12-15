<x-layout>
    <div class="max-w-4xl mx-auto">
        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 px-6 py-4 mb-6 rounded-r">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
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
                <a href="{{ route('profile.edit') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-5 rounded transition-colors duration-200">
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

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 mb-6">
            <div class="border-b border-gray-200 pb-4 mb-6">
                <h2 class="text-xl font-bold text-gray-900">API Access</h2>
                <p class="text-sm text-gray-500 mt-1">Manage your API key for programmatic access</p>
            </div>

            @if (session('api_key'))
                <div class="bg-blue-50 border-l-4 border-blue-500 px-6 py-4 mb-6 rounded-r">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <div class="flex-1">
                            <h4 class="text-sm font-semibold text-blue-800 mb-2">Your API Key (Save it now!)</h4>
                            <div class="bg-white border border-blue-200 rounded p-3 mb-2">
                                <code class="text-sm font-mono text-blue-900 break-all">{{ session('api_key') }}</code>
                            </div>
                            <p class="text-xs text-blue-700">⚠️ This key will only be shown once. Copy it now and store
                                it securely.</p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($user->api_key)
                <div class="space-y-4">
                    <div class="bg-green-50 border border-green-200 rounded p-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-semibold text-green-800">API Key Active</span>
                        </div>
                        <p class="text-xs text-green-700 mt-2">You have an active API key. Use it in your requests with
                            the header: <code class="bg-green-100 px-1 py-0.5 rounded">Authorization: Bearer
                                YOUR_KEY</code></p>
                    </div>

                    <div class="flex items-center gap-4">
                        <form method="POST" action="{{ route('profile.api-key.generate') }}">
                            @csrf
                            <button type="submit"
                                onclick="return confirm('This will invalidate your current API key. Continue?')"
                                class="bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2.5 px-5 rounded transition-colors duration-200">
                                Regenerate Key
                            </button>
                        </form>

                        <form method="POST" action="{{ route('profile.api-key.revoke') }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                onclick="return confirm('Are you sure you want to revoke your API key? This cannot be undone.')"
                                class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-5 rounded transition-colors duration-200">
                                Revoke Key
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    <p class="text-sm text-gray-600">You don't have an API key yet. Generate one to access the Currency
                        Exchange API programmatically.</p>

                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">API Endpoint</h4>
                        <code class="text-xs font-mono text-gray-600">GET
                            {{ config('app.url') }}/api/v1/convert?amount=100&from=GBP&to=USD</code>
                    </div>

                    <form method="POST" action="{{ route('profile.api-key.generate') }}">
                        @csrf
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-5 rounded transition-colors duration-200">
                            Generate API Key
                        </button>
                    </form>
                </div>
            @endif
        </div>

    </div>
</x-layout>
