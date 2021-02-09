<?php

namespace App\Observers;

use App\Models\Atachment;

class AtachmentObserver
{
    /**
     * Handle the Atachment "created" event.
     *
     * @param  \App\Models\Atachment  $atachment
     * @return void
     */
    public function created(Atachment $atachment)
    {
        //
    }

    /**
     * Handle the Atachment "updated" event.
     *
     * @param  \App\Models\Atachment  $atachment
     * @return void
     */
    public function updated(Atachment $atachment)
    {
        //
    }

    /**
     * Handle the Atachment "deleted" event.
     *
     * @param  \App\Models\Atachment  $atachment
     * @return void
     */
    public function deleted(Atachment $atachment)
    {
        //
    }

    /**
     * Handle the Atachment "restored" event.
     *
     * @param  \App\Models\Atachment  $atachment
     * @return void
     */
    public function restored(Atachment $atachment)
    {
        //
    }

    /**
     * Handle the Atachment "force deleted" event.
     *
     * @param  \App\Models\Atachment  $atachment
     * @return void
     */
    public function forceDeleted(Atachment $atachment)
    {
        //
    }
}
