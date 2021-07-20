<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Order;
use App\Models\Commission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CommissionEarned extends Notification implements ShouldQueue
{
  use Queueable;

  public $upline;
  public $user;
  public $order;
  public $commission;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct(User $upline, $user, Order $order, Commission $commission)
  {
    $this->upline = $upline;
    $this->user = $user;
    $this->order = $order;
    $this->commission = $commission;
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
      ->subject('Commission Earned')
      ->greeting('Hello ' . $this->upline->name . ',')
      ->line('One of your referral has sent a payment to ' . config('app.name'))
      ->line('You just earned commission!')
      ->line($this->user->username . ' has purchased ' . $this->order->order_item . ' for $' . $this->order->price)
      ->line('Your commission is $' . $this->commission->amount . ' (' . $this->upline->type->commission_ratio . '%)');
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
