<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CoinpaymentsButton extends Component
{
  public $amount;
  public $item;
  public $id;
  public $cancelUrl;
  public $successUrl;
  /**
   * Create a new component instance.
   *
   * @return void
   */
  public function __construct($amount, $id, $item, $cancelUrl, $successUrl)
  {
    $this->amount = $amount;
    $this->id = $id;
    $this->item = $item;
    $this->cancelUrl = $cancelUrl;
    $this->successUrl = $successUrl;
  }

  /**
   * Get the view / contents that represent the component.
   *
   * @return \Illuminate\Contracts\View\View|\Closure|string
   */
  public function render()
  {
    return view('components.coinpayments-button');
  }
}
