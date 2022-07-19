<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CoinpaymentsButton extends Component
{
  public $amount;
  public $item;
  public $id;
  /**
   * Create a new component instance.
   *
   * @return void
   */
  public function __construct($amount, $id, $item)
  {
    $this->amount = $amount;
    $this->id = $id;
    $this->item = $item;
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
