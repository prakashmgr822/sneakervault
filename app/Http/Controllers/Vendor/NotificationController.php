<?php

namespace App\Http\Controllers\Vendor;

use App\Helpers\NotificationsHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifications = Auth::user()->notifications->whereNull('read_at')->all();
        $dropdownHtml = ' ';

        foreach ($notifications as $key => $notification) {

            $link = route('notifications.show', $notification->id);
            $title = NotificationsHelper::getDetail($notification)['title'];

            $dropdownHtml .= "<a class='w-100'
                        href='" . $link . "'>
                        <div class='d-flex flex-column ml-3 pt-2 { $notification->read_at ? '' : 'notification-hover'} rounded px-3 py-2 notification flex-grow-1 w-100'>
                            <div class='d-flex align-items-center justify-content-between'>
                            </div>
                            <div class='row'>
                            <div class='col-md-6'>
                             <p class='mb-2 text-dark'>{$title} </p>
                             </div>
                             <div class='col-md-6'>
                            <small class='text-dark float-right mr-4'>{$notification->created_at->diffForHumans()}</small>
                            </div>
                            </div>
                        </div>
                    </a>";

            if ($key < count($notifications) -1) {
                $dropdownHtml .= "<div class='dropdown-divider'></div>";
            }
        }

        // Return the new notification data.

        return [
            'label' => count($notifications),
            'label_color' => 'danger',
            'icon_color' => 'dark',
            'dropdown' => $dropdownHtml,
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     * @param $id
     * @return
     */
    public function show($id)
    {
        $notification = Auth::user()->notifications->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
        $redirectPath = NotificationsHelper::getDetail($notification)['link'] ?? url('/');
        return redirect()->to($redirectPath);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
