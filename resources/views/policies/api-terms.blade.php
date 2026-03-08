<x-layout>
    <div class="max-w-4xl">
        <!-- Hero Section -->
        <div class="mb-12 rounded-3xl border border-blue-100 bg-linear-to-r from-blue-50 to-white px-8 py-12">
            <h1 class="text-4xl font-bold text-slate-900">API Terms</h1>
            <p class="mt-2 text-slate-600">Effective Date: March 2026</p>
        </div>

        <!-- Content -->
        <article class="space-y-8">
            <section>
                <h2 class="text-2xl font-semibold text-slate-900">1. API Access</h2>
                <p class="text-slate-700">FX Tracker provides API access to authenticated users. By using our API, you agree to these API Terms and all applicable policies.</p>
            </section>

            <section>
                <h2 class="text-2xl font-semibold text-slate-900">2. Authentication</h2>
                <p class="text-slate-700">API access requires authentication via an API key. API keys are personal and confidential. You are responsible for:</p>
                <ul class="list-inside list-disc space-y-2 text-slate-700">
                    <li>Keeping your API key secure and not sharing it</li>
                    <li>Revoking compromised keys immediately</li>
                    <li>Notifying us of unauthorized access</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-semibold text-slate-900">3. Rate Limiting</h2>
                <p class="text-slate-700">API usage is subject to rate limits:</p>
                <ul class="list-inside list-disc space-y-2 text-slate-700">
                    <li>Standard tier: 100 requests per minute</li>
                    <li>Enterprise tier: Custom limits by agreement</li>
                    <li>Exceeding limits will result in HTTP 429 responses</li>
                    <li>Persistent abuse may result in account suspension</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-semibold text-slate-900">4. API Response Accuracy</h2>
                <p class="text-slate-700">While we strive for accuracy, FX Tracker makes no warranties regarding:</p>
                <ul class="list-inside list-disc space-y-2 text-slate-700">
                    <li>Timeliness of exchange rate updates</li>
                    <li>Complete accuracy of conversion calculations</li>
                    <li>Continuous availability of the API</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-semibold text-slate-900">5. Uptime and Service Level</h2>
                <p class="text-slate-700">We aim to maintain 99% uptime, but cannot guarantee 100% availability. Scheduled maintenance and emergencies may cause temporary service interruptions.</p>
            </section>

            <section>
                <h2 class="text-2xl font-semibold text-slate-900">6. Data Usage</h2>
                <p class="text-slate-700">Data obtained through the API:</p>
                <ul class="list-inside list-disc space-y-2 text-slate-700">
                    <li>Must not be resold or redistributed</li>
                    <li>Should not be cached for more than 1 hour</li>
                    <li>Must include proper attribution to FX Tracker</li>
                    <li>Cannot be used to create competitive products</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-semibold text-slate-900">7. API Changes</h2>
                <p class="text-slate-700">FX Tracker may deprecate or modify API endpoints with 30 days' notice. Breaking changes require migration guidance and support.</p>
            </section>

            <section>
                <h2 class="text-2xl font-semibold text-slate-900">8. Suspension and Termination</h2>
                <p class="text-slate-700">We reserve the right to suspend or revoke API access for:</p>
                <ul class="list-inside list-disc space-y-2 text-slate-700">
                    <li>Violation of these API Terms</li>
                    <li>Suspicious or malicious activity</li>
                    <li>Non-payment of applicable fees</li>
                    <li>Legal or compliance requirements</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-semibold text-slate-900">9. Support and Documentation</h2>
                <p class="text-slate-700">Complete API documentation is available in your developer dashboard. Support inquiries can be directed to api@infinitefinances.com</p>
            </section>

            <section>
                <h2 class="text-2xl font-semibold text-slate-900">10. Liability Disclaimer</h2>
                <p class="text-slate-700">FX Tracker is not liable for any damages arising from API usage, including data loss, business interruption, or financial losses resulting from inaccurate conversions.</p>
            </section>
        </article>
    </div>
</x-layout>
