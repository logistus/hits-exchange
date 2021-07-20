<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReferralNotification extends Notification implements ShouldQueue
{
  use Queueable;

  public $user;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct($user)
  {
    $this->user = $user;
  }

  /**
   * Get the notification's delivery channels.
   *
   * @param  mixed  $notifiable
   * @return array
   */
  public function via($notifiable)
  {
    return ['mail'];
  }

  /**
   * Get the mail representation of the notification.
   *
   * @param  mixed  $notifiable
   * @return \Illuminate\Notifications\Messages\MailMessage
   */
  public function toMail($notifiable)
  {
    return (new MailMessage)
      ->subject('Referral Notification')
      ->greeting('Hello ' . $this->user->name . ',')
      ->line('You just referred new member to ' . config('app.name'))
      ->line('You will earn commissions every time your referrer buys something at ' . config('app.name'))
      ->line('Thank you for promoting.');
  }

  /**
   * Get the array representation of the notification.
   *
   * @param  mixed  $notifiable
   * @return array
   */
  public function toArray($notifiable)
  {
    return [
      //
    ];
  }
}
