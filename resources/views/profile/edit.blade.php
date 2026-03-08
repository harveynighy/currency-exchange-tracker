<x-layout>
    <div class="mx-auto max-w-5xl space-y-8">
        <section class="rounded-3xl border border-blue-100 bg-linear-to-r from-blue-50 to-white px-8 py-10">
            <p class="mb-2 text-xs font-semibold uppercase tracking-[0.14em] text-blue-700">Profile Settings</p>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">Manage Account</h1>
            <p class="mt-2 text-sm text-slate-600">Update profile details and credentials securely.</p>
        </section>

        <div class="mb-6">
            <a href="{{ route('profile.show') }}" class="flex items-center text-sm font-semibold text-blue-700 hover:text-blue-600">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Profile
            </a>
        </div>

        <div class="glass-panel p-8 sm:p-10">
            <div class="mb-6 border-b border-slate-200 pb-6">
                <h2 class="text-xl font-semibold text-slate-900">Profile Information</h2>
                <p class="mt-1 text-sm text-slate-600">Update your account details</p>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="form-label">Full Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name"
                        value="{{ old('name', $user->name) }}"
                        class="form-input text-lg @error('name') border-red-400 @enderror" 
                        required
                    >
                    @error('name')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        value="{{ old('email', $user->email) }}"
                        class="form-input text-lg @error('email') border-red-400 @enderror" 
                        required
                    >
                    @error('email')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <button 
                        type="submit" 
                        class="primary-btn"
                    >
                        Save Changes
                    </button>
                    <a href="{{ route('profile.show') }}" class="font-semibold text-slate-600 hover:text-slate-800">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <div class="glass-panel p-8 sm:p-10">
            <div class="mb-6 border-b border-slate-200 pb-6">
                <h2 class="text-xl font-semibold text-slate-900">Change Password</h2>
                <p class="mt-1 text-sm text-slate-600">Ensure your account is using a strong password</p>
            </div>

            <form method="POST" action="{{ route('profile.password') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="form-label">Current Password</label>
                    <input 
                        type="password" 
                        name="current_password" 
                        id="current_password"
                        class="form-input text-lg @error('current_password') border-red-400 @enderror" 
                        required
                    >
                    @error('current_password')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="form-label">New Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        class="form-input text-lg @error('password') border-red-400 @enderror" 
                        required
                    >
                    @error('password')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation"
                        class="form-input text-lg" 
                        required
                    >
                </div>

                <!-- Submit Button -->
                <div class="flex items-center gap-4 pt-4">
                    <button 
                        type="submit" 
                        class="primary-btn"
                    >
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
