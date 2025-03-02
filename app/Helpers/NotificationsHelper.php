<?php


namespace App\Helpers;


use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationsHelper
{
    static function getDetail(DatabaseNotification $notification)
    {
        $data = [];
        switch ($notification->type) {
            case "App\Notifications\Vendor\NewOrderNotification";
                $data['title'] = $notification->data['title'];
                $data['link'] = route('orders.index');
                $data['notification'] = $notification;
                break;
            default:
                break;
        }
        return $data;
    }

    static function unreadCount()
    {
        return Auth::user()->unreadNotifications->count();
    }
}
