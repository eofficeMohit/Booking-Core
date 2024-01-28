<?php

namespace Modules\Booking\Gateways;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;
use Modules\Booking\Events\BookingCreatedEvent;
use Modules\Booking\Gateways\BaseGateway;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\Payment;

class PeachGateway extends BaseGateway
{
    protected $id = 'peach_payment';

    public $name = 'Peach Payment';

    protected $gateway;

    public function process(Request $request, $booking, $service)
    {
        $service->beforePaymentProcess($booking, $this);
        // Simple change status to processing

        if ($booking->paid <= 0) {
            $booking->status = $booking::PROCESSING;
        } else {
            if ($booking->paid < $booking->total) {
                $booking->status = $booking::PARTIAL_PAYMENT;
            } else {
                $booking->status = $booking::PAID;
            }
        }

        $booking->save();
        try {
            event(new BookingCreatedEvent($booking));
        } catch (\Swift_TransportException $e) {
            Log::warning($e->getMessage());
        }

        $service->afterPaymentProcess($booking, $this);
        return response()
            ->json([
                'url' => $booking->getDetailUrl(),
            ])
            ->send();
    }

    public function processNormal($payment)
    {
        $payment->status = 'processing';
        $payment->save();

        return [true, __('Thank you, we will contact you shortly')];
    }

    public function getOptionsConfigs()
    {
        return [
            [
                'type' => 'checkbox',
                'id' => 'enable',
                'label' => __('Enable Peach Payment'),
            ],
            [
                'type' => 'input',
                'id' => 'name',
                'label' => __('Custom Name'),
                'std' => __('Debit or Credit Card'),
                'multi_lang' => '1',
            ],
            [
                'type' => 'upload',
                'id' => 'logo_id',
                'label' => __('Custom Logo'),
            ],
            [
                'type' => 'input',
                'id' => 'PEACHPAYMENT_ENVIRONMENT',
                'label' => __('PEACH PAYMENT ENVIRONMENT'),
            ],
            [
                'type' => 'input',
                'id' => 'PEACHPAYMENT_ENTITY_ID',
                'label' => __('PEACHPAYMENT ENTITY ID'),
            ],
            [
                'type' => 'input',
                'id' => 'PEACHPAYMENT_CLIENT_ID',
                'label' => __('PEACH PAYMENT CLIENT ID'),
            ],
            [
                'type' => 'input',
                'id' => 'PEACHPAYMENT_CLIENT_SECRET',
                'label' => __('PEACH PAYMENT CLIENT SECRET'),
            ],
            [
                'type' => 'input',
                'id' => 'PEACHPAYMENT_MERCHANT_ID',
                'label' => __('PEACH PAYMENT MERCHANT ID'),
            ],
            [
                'type' => 'input',
                'id' => 'PEACHPAYMENT_DOMAIN',
                'label' => __('PEACH PAYMENT DOMAIN'),
            ],
            [
                'type' => 'input',
                'id' => 'PEACHPAYMENT_CURRENCY',
                'label' => __('PEACHPAYMENT_CURRENCY'),
            ],
            [
                'type' => 'checkbox',
                'id' => 'peach_enable_sandbox',
                'label' => __('Enable Sandbox Mode'),
            ],
            [
                'type' => 'input',
                'id' => 'PEACHPAYMENT_ENVIRONMENT',
                'label' => __('PEACH PAYMENT ENVIRONMENT'),
            ],
            [
                'type' => 'input',
                'id' => 'PEACHPAYMENT_ENTITY_ID',
                'label' => __('PEACHPAYMENT ENTITY ID'),
            ],
            [
                'type' => 'input',
                'id' => 'PEACHPAYMENT_CLIENT_ID',
                'label' => __('PEACH PAYMENT CLIENT ID'),
            ],
            [
                'type' => 'input',
                'id' => 'PEACHPAYMENT_CLIENT_SECRET',
                'label' => __('PEACH PAYMENT CLIENT SECRET'),
            ],
            [
                'type' => 'input',
                'id' => 'PEACHPAYMENT_MERCHANT_ID',
                'label' => __('PEACH PAYMENT MERCHANT ID'),
            ],
            [
                'type' => 'input',
                'id' => 'PEACHPAYMENT_DOMAIN',
                'label' => __('PEACH PAYMENT DOMAIN'),
            ],
            [
                'type' => 'input',
                'id' => 'PEACHPAYMENT_CURRENCY',
                'label' => __('PEACHPAYMENT_CURRENCY'),
                'desc' => __('Webhook url: <code>:code</code>', ['code' => $this->getWebhookUrl()]),
            ],
        ];
    }
}
