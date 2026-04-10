<?php

declare(strict_types=1);

namespace TTBooking\AdvancedChat\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use TTBooking\AdvancedChat\Events\Attachment\Uploaded;
use TTBooking\AdvancedChat\Http\Requests\StoreAttachmentRequest;
use TTBooking\AdvancedChat\Http\Resources\AttachmentResource;
use TTBooking\AdvancedChat\Models\Attachment;
use TTBooking\AdvancedChat\Models\Message;

class AttachmentController extends Controller
{
    /**
     * Store a newly created attachment in storage.
     */
    public function store(StoreAttachmentRequest $request, Message $message): AttachmentResource
    {
        $attachmentFile = $request->file('attachment');
        $attachment = $message->getAttachment($attachmentFile->getClientOriginalName());
        $attachmentFile->storeAs($message->attachmentPath, $attachment->name, config('advanced-chat.disk'));

        broadcast(new Uploaded($attachment))->toOthers();

        /** @var AttachmentResource */
        return $attachment->toResource();
    }

    /**
     * Download the specified attachment.
     */
    public function show(Message $message, Attachment $attachment): StreamedResponse
    {
        return Storage::disk(config('advanced-chat.disk'))->download($attachment->attachmentPath);
    }

    /**
     * Remove the specified attachment from storage.
     */
    public function destroy(Message $message, Attachment $attachment): \Illuminate\Http\Response
    {
        $attachment->delete();

        return Response::noContent();
    }
}
