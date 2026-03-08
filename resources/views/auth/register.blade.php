<x-layout>
    <div class="mx-auto max-w-md space-y-6">
        <section class="rounded-3xl border border-blue-100 bg-linear-to-r from-blue-50 to-white px-6 py-8">
            <p class="mb-2 text-xs font-semibold uppercase tracking-[0.14em] text-blue-700">Get Started</p>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">Create account</h1>
            <p class="mt-2 text-sm text-slate-600">Build your conversion workflow in seconds.</p>
        </section>

        <div class="glass-panel p-8">
            <div class="mb-6 border-b border-slate-200 pb-6">
                <h2 class="text-xl font-semibold text-slate-900">Registration</h2>
                <p class="mt-1 text-sm text-slate-600">Set up your account details.</p>
            </div>

            <form method="POST" action="/register" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="form-label">Full Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name"
                        value="{{ old('name') }}"
                        class="form-input text-lg @error('name') border-red-400 @enderror" 
                        placeholder="John Doe"
                        required
                        autofocus
                    >
                    @error('name')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        value="{{ old('email') }}"
                        class="form-input text-lg @error('email') border-red-400 @enderror" 
                        placeholder="you@example.com"
                        required
                    >
                    @error('email')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="form-label">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        class="form-input text-lg @error('password') border-red-400 @enderror" 
                        placeholder="••••••••"
                        required
                    >
                    @error('password')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation"
                        class="form-input text-lg" 
                        placeholder="••••••••"
                        required
                    >
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="primary-btn w-full py-3.5"
                >
                    Create Account
                </button>
            </form>

            <div class="mt-6 border-t border-slate-200 pt-6">
                <p class="text-center text-sm text-slate-600">
                    Already have an account?
                    <a href="/login" class="font-semibold text-blue-700 hover:text-blue-600">Sign in</a>
                </p>
            </div>
        </div>
    </div>
</x-layout>
