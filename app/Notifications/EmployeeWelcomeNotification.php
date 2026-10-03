<?php

namespace App\Notifications;

use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmployeeWelcomeNotification extends Notification
{
    use Queueable;

    public Employee $employee;
    public string $plainPassword;

    public function __construct(Employee $employee, string $plainPassword)
    {
        $this->employee = $employee;
        $this->plainPassword = $plainPassword;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $shop = $this->employee->shop;
        $branch = $this->employee->branch;
        $role = ucfirst(str_replace('_', ' ', $this->employee->role));

        return (new MailMessage)
            ->subject('Your ' . $shop->shop_name . ' Employee Account is Ready')
            ->greeting('Hi ' . $notifiable->name . '! 👋')
            ->line($shop->user->name . ' has created an employee account for you at ' . $shop->shop_name . '.')
            ->line('**Role:** ' . $role)
            ->line('**Branch:** ' . $branch->name)
            ->line('**Login Email:** ' . $notifiable->email)
            ->line('**Temporary Password:** ' . $this->plainPassword)
            ->line('For security, please log in and change your password as soon as possible.')
            ->action('Log In to Your Account', route('livewire.auth.login'))
            ->line('If you did not expect this email, you can safely ignore it.')
            ->salutation('— Web-based Multi-Tenant Bakeshop System');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'employee_welcome',
            'shop_name' => $this->employee->shop->shop_name,
            'branch_name' => $this->employee->branch->name,
            'role_label' => ucfirst(str_replace('_', ' ', $this->employee->role)),
            'owner_name' => $this->employee->shop->user->name,
            'message' => $this->employee->shop->user->name . ' created your employee account at ' . $this->employee->shop->shop_name,
            'url' => route('livewire.auth.login'),
        ];
    }
}
