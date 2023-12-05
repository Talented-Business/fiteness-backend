<?php

namespace App\Observers;

use App\Models\AdminAction;

class AdminActionObserver
{
    /**
     * Handle the admin action "created" event.
     *
     * @param  \App\Models\AdminAction  $adminAction
     * @return void
     */
    public function created(AdminAction $adminAction)
    {
        switch($adminAction->type){
            case 'mute':
                \App\Models\Notification::muteByAdmin($adminAction->object_id, $adminAction->admin_id,$adminAction->content['days']);
            break;
            case 'unmute':
            break;
        }   
    }
}
