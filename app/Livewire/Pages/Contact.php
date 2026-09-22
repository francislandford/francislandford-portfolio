<?php

namespace App\Livewire\Pages;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Contact extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $subject = '';
    public string $message = '';

    public bool $sent = false;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ];
    }

    public function send(): void
    {
        $validated = $this->validate();

        $contactMessage = ContactMessage::create($validated);

        $recipient = Setting::get('email');

        if ($recipient) {
            try {
                Mail::to($recipient)->send(new ContactMessageReceived($contactMessage));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        $this->reset(['name', 'email', 'phone', 'subject', 'message']);
        $this->sent = true;
    }

    public function render()
    {
        $name = Setting::get('name');

        return view('livewire.pages.contact')->layout('components.layouts.app', [
            'title' => 'Contact',
            'description' => "Have a project in mind? Get in touch with {$name}.",
        ]);
    }
}
