<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Aws\S3\S3Client;
use Inertia\Inertia;
use App\Models\Project;
use Redirect;

class LookupController extends Controller
{
    public function index (S3Client $client) {
        $front_page_objects = $client->listObjectsV2([
            'Bucket' => 'kdi-photo',
            'Prefix' => 'front-page/'
        ]);
        $images = collect($front_page_objects['Contents']);
        $images->shift();
        $image_paths = $images->map(function ($image, $index) {
            return 'https://s3.amazonaws.com/kdi-photo/' . $image['Key'];
        });

        return Inertia::render('Home', [
            'slides' => $image_paths
        ]);
    }

    public function lookup (Request $request) {
        $request->validate([
            'lookup_id' => ['required'],
            'photo_id' => ['required'],
        ]);

        return Redirect::to("/order-form/{$request->input('lookup_id')}/{$request->input('photo_id')}");
    }
    
    public function orderForm ($lookup_id, $photo_id) {
        $project = Project::with(['packages', 'items'])->firstWhere('lookup_id', $lookup_id);

        return Inertia::render('Lookup', [
            'project' => $project,
            'photo_id' => $photo_id
        ]);
    }
}
