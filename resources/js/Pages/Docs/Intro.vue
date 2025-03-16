<script lang="ts" setup>
import DocLayout from '@/Layouts/DocLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="API Documentation" />

    <DocLayout>
        <div class="container py-10 px-4 md:px-6">
            <div class="space-y-8">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">API Documentation</h1>
                    <p class="text-muted-foreground mt-2">
                        Use our API to integrate with your applications and services.
                    </p>
                </div>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div class="rounded-lg border p-6 space-y-3">
                        <h3 class="text-xl font-semibold">Authentication</h3>
                        <p class="text-sm text-muted-foreground">
                            Learn how to authenticate your API requests using API keys or OAuth tokens.
                        </p>
                        <div class="pt-2">
                            <code class="text-sm bg-muted p-2 rounded block">
                                Authorization: Bearer YOUR_API_KEY
                            </code>
                        </div>
                    </div>

                    <div class="rounded-lg border p-6 space-y-3">
                        <h3 class="text-xl font-semibold">Transactions</h3>
                        <p class="text-sm text-muted-foreground">
                            Endpoints for managing income and expense transactions.
                        </p>
                        <div class="pt-2">
                            <code class="text-sm bg-muted p-2 rounded block">
                                GET /api/v1/transactions
                            </code>
                        </div>
                    </div>

                    <div class="rounded-lg border p-6 space-y-3">
                        <h3 class="text-xl font-semibold">Accounts</h3>
                        <p class="text-sm text-muted-foreground">
                            Manage bank accounts and view balances.
                        </p>
                        <div class="pt-2">
                            <code class="text-sm bg-muted p-2 rounded block">
                                GET /api/v1/accounts
                            </code>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h2 class="text-2xl font-bold tracking-tight">Getting Started</h2>
                    <div class="rounded-lg border">
                        <div class="p-6 space-y-4">
                            <h3 class="text-lg font-semibold">Base URL</h3>
                            <p class="text-sm text-muted-foreground">
                                All API requests should be made to the following base URL:
                            </p>
                            <code class="text-sm bg-muted p-2 rounded block">
                                https://api.example.com/v1
                            </code>

                            <h3 class="text-lg font-semibold mt-6">Request Format</h3>
                            <p class="text-sm text-muted-foreground">
                                The API accepts JSON-encoded request bodies and returns JSON-encoded responses.
                            </p>
                            <code class="text-sm bg-muted p-2 rounded block whitespace-pre">
{
  "Content-Type": "application/json"
}
                            </code>

                            <h3 class="text-lg font-semibold mt-6">Rate Limiting</h3>
                            <p class="text-sm text-muted-foreground">
                                The API is rate limited to 100 requests per minute per API key.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h2 class="text-2xl font-bold tracking-tight">Endpoints Reference</h2>
                    <div class="rounded-lg border">
                        <div class="border-b px-6 py-4">
                            <h3 class="font-semibold">GET /transactions</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <p class="text-sm text-muted-foreground">
                                Returns a list of transactions for the authenticated user.
                            </p>
                            <div class="space-y-2">
                                <h4 class="font-medium">Query Parameters</h4>
                                <ul class="list-disc list-inside text-sm space-y-1">
                                    <li><code>start_date</code> - Filter by start date (YYYY-MM-DD)</li>
                                    <li><code>end_date</code> - Filter by end date (YYYY-MM-DD)</li>
                                    <li><code>type</code> - Filter by transaction type (income, expense)</li>
                                </ul>
                            </div>
                            <div class="space-y-2 mt-4">
                                <h4 class="font-medium">Response Example</h4>
                                <code class="text-sm bg-muted p-2 rounded block whitespace-pre">
{
  "data": [
    {
      "id": "tx_123456",
      "amount": 100.00,
      "description": "Salary payment",
      "type": "income",
      "date": "2023-04-15",
      "category": "Salary"
    },
    {
      "id": "tx_123457",
      "amount": 25.50,
      "description": "Grocery shopping",
      "type": "expense",
      "date": "2023-04-16",
      "category": "Food"
    }
  ],
  "meta": {
    "total": 2,
    "page": 1,
    "per_page": 10
  }
}
                                </code>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border p-6 bg-muted/50">
                    <div class="flex items-start space-x-4">
                        <div class="rounded-full bg-primary/10 p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold">Need help?</h3>
                            <p class="text-sm text-muted-foreground mt-1">
                                If you have any questions or need assistance with our API, please contact our support team.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DocLayout>
</template>