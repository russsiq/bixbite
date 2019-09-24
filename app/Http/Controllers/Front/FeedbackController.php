<?php

namespace BBCMS\Http\Controllers\Front;

use BBCMS\Http\Controllers\SiteController;
use BBCMS\Http\Requests\Front\FeedbackRequest;

use Illuminate\Support\Facades\Mail;

class FeedbackController extends SiteController
{
    protected $template = 'feedback';

    public function __construct()
    {
        parent::__construct();
    }

    public function create()
    {
        pageinfo([
            'title' => __('feedback.index_page'),
            'robots' => 'noindex,follow',
            'is_feedback' => true,
        ]);

        return $this->makeResponse('create');
    }

    public function send(FeedbackRequest $request)
    {
        $complete = $this->mail($request);

        if ($request->expectsJson()) {
            return $complete ?
                response()->json([
                    'message' => trans('feedback.msg.success'),
                ], 200) :
                response()->json([
                    'message' => trans('feedback.msg.failure'),
                ], 500);
        }

        return $complete ?
            redirect()->to(url()->previous())->withStatus(trans('feedback.msg.success')) :
            redirect()->route('feedback.create')->withErrors(trans('feedback.msg.failure'))->withInput();
    }

    protected function mail($request)
    {
        $data = $request->only([
            'name',
            'contact',
            'content',
        ]);

        // Тестировать отправку почты на localhost https://mailtrap.io.
        Mail::send('emails.feedback', $data, function ($message) use ($request) {
            $message->to(config('mail.from.address'))
                ->subject(trans('feedback.message.title'));

            $file = $request->file('file');

            if ($file && $file->isValid()) {
                $message->attach($file->getRealPath(), [
                    // If you want you can change original name to custom name
                    'as' => $file->getClientOriginalName(),
                    'mime' => $file->getMimeType()
                ]);
            }
        });

        return true;
    }
}

// $params = [
//     'value' => 'Значение',
// ];
//
// \Illuminate\Support\Facades\Mail::to('russsiq@************.ru')
//     ->send(new \BBCMS\Mail\Feedback($params));
