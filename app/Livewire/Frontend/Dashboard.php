<?php

namespace App\Livewire\Frontend;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Dashboard extends Component
{
    public string $email = '';
    public string $password = '';
    public ?string $apiToken = null;
    public ?array $apiResponse = null;
    public ?string $errorMessage = null;
    public bool $isLoading = false;

    public function mount(): void
    {
        $this->apiToken = session('api_token');
    }

    /**
     * Login via HTTP POST — this is fine because POST completes immediately
     * and doesn't cause the self-referencing GET deadlock.
     */
    public function login(): void
    {
        $this->isLoading = true;
        $this->errorMessage = null;
        $this->apiResponse = null;

        try {
            $response = Http::timeout(10)->post(url('/api/auth/login'), [
                'email'    => $this->email,
                'password' => $this->password,
            ]);

            $data = $response->json();

            if ($response->successful() && isset($data['data']['token'])) {
                $this->apiToken = $data['data']['token'];
                session(['api_token' => $this->apiToken]);
                $this->apiResponse = $data;
            } else {
                $this->errorMessage = $data['message'] ?? 'Login failed — check email/password';
            }
        } catch (\Throwable $e) {
            $this->errorMessage = 'Connection error: ' . $e->getMessage();
            Log::error('Frontend login error', ['error' => $e->getMessage()]);
        }

        $this->isLoading = false;
    }

    public function logout(): void
    {
        if ($this->apiToken) {
            try {
                Http::timeout(5)->withToken($this->apiToken)->post(url('/api/auth/logout'));
            } catch (\Throwable) {
                // If logout API fails, still clear the local token
            }
        }

        session()->forget('api_token');
        $this->apiToken = null;
        $this->apiResponse = null;
        $this->email = '';
        $this->password = '';
    }

    public function render()
    {
        return view('livewire.frontend.dashboard')
            ->layout('frontend.layout', ['title' => 'Dashboard — Chaothuk Frontend']);
    }
}
