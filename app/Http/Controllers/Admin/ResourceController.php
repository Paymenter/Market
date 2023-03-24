<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resources = Resource::all();
        return view('admin.resources.index', compact('resources'));
    }


    public function show(Resource $resource)
    {
        return view('admin.resources.show', compact('resource'));
    }

    public function approve(Resource $resource)
    {
        $resource->status = 'published';
        $resource->save();
        // Post created webhook to discord
        $webhookUrl = env('DISCORD_NEW_RESOURCE');
        $timestamp = date("c", strtotime("now"));

        $json_data = [
            'content' => 'New resource Approved',
            'username' => 'Resources',
            'tts' => false,
            'embeds' => [
                [
                    'title' => $resource->name,
                    'type' => 'rich',
                    'description' => $resource->description,
                    'url' => url('/resource/' . $resource->id),
                    'timestamp' => $timestamp,
                    'color' => hexdec("0000"),
                    'footer' => [
                        'text' => 'Resources',
                        'icon_url' => url('/storage/avatars/' . $resource->user()->get()->first()->avatar)
                    ],
                    'thumbnail' => [
                        'url' => url('/storage/resource/' . $resource->image)
                    ],
                    'author' => [
                        'name' => $resource->user()->get()->first()->name,
                        'url' => url('/users/' . $resource->user()->get()->first()->id),
                        'icon_url' => url('/storage/avatars/' . $resource->user()->get()->first()->avatar)
                    ],
                ],
            ],

        ];
        // Set headers
        $headers = [
            'Content-Type' => 'application/json',
        ];
        $response = Http::post($webhookUrl, $json_data, $headers);
        return redirect()->route('admin.resources.index')->with('success', 'Resource approved');
    }

    public function reject(Resource $resource)
    {
        $resource->status = 'rejected';
        $resource->save();
        return redirect()->route('admin.resources.index')->with('success', 'Resource rejected');
    }

    public function delete(Resource $resource)
    {
        if(auth()->user()->role == 'resourcemod')
            return redirect()->route('admin.resources.index')->with('error', 'You are not authorized to access this page');
        $resource->delete();
        return redirect()->route('admin.resources.index')->with('success', 'Resource deleted');
    }
}