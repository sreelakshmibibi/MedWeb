<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;


use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;


class WelcomeVerifyNotification extends Notification
{

    // use Queueable;



    /**
     * The user's name.
     *
     * @var string
     */
    public $userName;

    /**
     * The temporary password.
     *
     * @var string
     */
    public $email;

    /**
     * The temporary password.
     *
     * @var string
     */
    public $password;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var (\Closure(mixed, string): \Illuminate\Notifications\Messages\MailMessage|\Illuminate\Contracts\Mail\Mailable)|null
     */
    public static $toMailCallback;



    /**
     * Create a new notification instance.
     * @param  string  $userName
     * @param  string  $email
     * @param  string  $password
     * @param  string  $token
     * 
     * @return void
     */
    public function __construct($userName, $email, $password, $token)
    {
        $this->userName = $userName;
        $this->email = $email;
        $this->password = $password;
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }


    /**
     * Get the mail representation of the notification.
     *   * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $verificationUrl = URL::temporarySignedRoute(
            'user.verify',
            now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'token' => $this->token,
            ]
        );

        return (new MailMessage)
            ->subject(Lang::get('Welcome to Our Application'))
            ->greeting(Lang::get('Hello ' . $this->userName . '!'))
            ->line(Lang::get('Welcome to MedWeb! We are excited to have you on board.'))
            ->line(Lang::get('Your username is: ' . $this->email))
            ->line(Lang::get('Your temporary password is: ' . $this->password))
            ->action(Lang::get('Verify Email Address'), $verificationUrl)
            ->line(Lang::get('After verifying your email, you will be redirected to change your password.'))
            ->line(Lang::get('If you have any questions, feel free to contact us.'));


    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param  \Closure(mixed, string): (\Illuminate\Notifications\Messages\MailMessage|\Illuminate\Contracts\Mail\Mailable)  $callback
     * @return void
     */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}
