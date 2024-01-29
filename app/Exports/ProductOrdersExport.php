<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductOrdersExport implements FromCollection, WithHeadings, WithMapping
{
  protected $orders;

  public function __construct($orders)
  {
    $this->orders = $orders;
  }

  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    return $this->orders;
  }

  public function headings(): array
  {
    return [
      'Order No.',
      'Billing Name',
      'Billing Email',
      'Billing Phone',
      'Billing Address',
      'Billing City',
      'Billing State',
      'Billing Country',
      'Shipping Name',
      'Shipping Email',
      'Shipping Phone',
      'Shipping Address',
      'Shipping City',
      'Shipping State',
      'Shipping Country',
      'Price',
      'Discount',
      'Shipping Method',
      'Shipping Cost',
      'Tax',
      'Grand Total',
      'Paid via',
      'Payment Status',
      'Order Status',
      'Order Date'
    ];
  }

  /**
   * @var $order
   */
  public function map($order): array
  {
    // price
    $price = ($order->currency_text_position == 'left' ? $order->currency_text . ' ' : '') . $order->total . ($order->currency_text_position == 'right' ? ' ' . $order->currency_text : '');

    // discount
    if (is_null($order->discount)) {
      $discount = '-';
    } else {
      $discount = ($order->currency_text_position == 'left' ? $order->currency_text . ' ' : '') . $order->discount . ($order->currency_text_position == 'right' ? ' ' . $order->currency_text : '');
    }

    // shipping method
    if (is_null($order->product_shipping_charge_id)) {
      $shippingMethod = '-';
    } else {
      $shippingMethod = $order->shippingMethod;
    }

    // shipping cost
    if (is_null($order->shipping_cost)) {
      $shippingCost = '-';
    } else {
      $shippingCost = ($order->currency_text_position == 'left' ? $order->currency_text . ' ' : '') . $order->shipping_cost . ($order->currency_text_position == 'right' ? ' ' . $order->currency_text : '');
    }

    // tax
    $tax = ($order->currency_text_position == 'left' ? $order->currency_text . ' ' : '') . $order->tax . ($order->currency_text_position == 'right' ? ' ' . $order->currency_text : '');

    // grand total
    $grandTotal = ($order->currency_text_position == 'left' ? $order->currency_text . ' ' : '') . $order->grand_total . ($order->currency_text_position == 'right' ? ' ' . $order->currency_text : '');

    return [
      '#' . $order->order_number,
      $order->billing_name,
      $order->billing_email,
      $order->billing_contact_number,
      $order->billing_address,
      $order->billing_city,
      is_null($order->billing_state) ? '-' : $order->billing_state,
      $order->billing_country,
      $order->shipping_name,
      $order->shipping_email,
      $order->shipping_contact_number,
      $order->shipping_address,
      $order->shipping_city,
      is_null($order->shipping_state) ? '-' : $order->shipping_state,
      $order->shipping_country,
      $price,
      $discount,
      $shippingMethod,
      $shippingCost,
      $tax,
      $grandTotal,
      $order->payment_method,
      ucwords($order->payment_status),
      ucwords($order->order_status),
      $order->createdAt
    ];
  }
}
