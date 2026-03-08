<x-layout>
    <div class="mx-auto max-w-md space-y-6">
        <section class="rounded-3xl border border-blue-100 bg-linear-to-r from-blue-50 to-white px-6 py-8">
            <p class="mb-2 text-xs font-semibold uppercase tracking-[0.14em] text-blue-700">Welcome Back</p>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">Sign in</h1>
            <p class="mt-2 text-sm text-slate-600">Access your FX Tracker workspace.</p>
        </section>

        <div class="glass-panel p-8">
            <div class="mb-6 border-b border-slate-200 pb-6">
                <h2 class="text-xl font-semibold text-slate-900">Account Login</h2>
                <p class="mt-1 text-sm text-slate-600">Use your credentials to continue.</p>
            </div>

            <form method="POST" action="/login" class="space-y-6">
                @csrf

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
                        autofocus
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

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        name="remember" 
                        id="remember"
                        class="h-4 w-4 rounded border-slate-300 bg-white text-blue-600 focus:ring-blue-500/40"
                    >
                    <label for="remember" class="ml-2 text-sm text-slate-700">Remember me</label>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="primary-btn w-full py-3.5"
                >
                    Sign In
                </button>
            </form>

            <div class="mt-6 border-t border-slate-200 pt-6">
                <p class="text-center text-sm text-slate-600">
                    Don't have an account?
                    <a href="/register" class="font-semibold text-blue-700 hover:text-blue-600">Create one</a>
                </p>
            </div>
        </div>
    </div>
</x-layout>
