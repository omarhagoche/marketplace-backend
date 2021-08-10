<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Order;
use Benwilkins\FCM\FcmMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AvailableOrder extends Notification
{
    use Queueable;

    /**
     * @var Order
     */
    private $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        //
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['fcm'];
    }

    public function toFcm($notifiable)
    {
        $message = new FcmMessage();
        $notification = [
            'title' => "Order #" . $this->order->id . " of " . $this->order->user->name . " is available for delivery",
            'text'         => $this->order->foodOrders[0]->food->restaurant->name,
            'image' => $this->order->foodOrders[0]->food->restaurant->getFirstMediaUrl('image', 'thumb'),
            'icon' => $this->order->foodOrders[0]->food->restaurant->getFirstMediaUrl('image', 'thumb'),
        ];
        $data = [
            'click_action' => "FLUTTER_NOTIFICATION_CLICK",
            'id' => '1',
            'status' => 'done',
            'message' => $notification,
        ];
        $message->content($notification)->data($data)->priority(FcmMessage::PRIORITY_HIGH);

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order['id'],
        ];
    }
}
