<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Aws\S3\S3Client;
use Inertia\Inertia;
use App\Models\Project;
use Redirect;
use Illuminate\Support\Facades\Validator;

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

    public function lookup (Request $request, S3Client $client) {
        $validator = Validator::make($request->all(), [
            'lookup_id' => ['required'],
            'photo_id' => ['required'],
        ]);

        if ($validator->passes()) {
            $project = Project::with(['packages', 'items'])->firstWhere('lookup_id', $request->input('lookup_id'));

            if (is_null($project)) {
                $validator->getMessageBag()->add('lookup_id', 'School ID does not exist.');
                return Redirect::back()->withErrors($validator)->withInput();
            }

            try {
                $photo = $client->getObject([
                    'Bucket' => 'kdi-photo',
                    'Key' => "{$project->directory}/{$request->input('photo_id')}.jpg"
                ]);
            } catch (\Aws\S3\Exception\S3Exception $e) {
                if($e->getStatusCode() == 404) {
                    $validator->getMessageBag()->add('photo_id', 'Photo ID does not exist.');
                }
                return Redirect::back()->withErrors($validator)->withInput();
            }
        }

        return Redirect::to("/order-form/{$request->input('lookup_id')}/{$request->input('photo_id')}");
    }
    
    public function orderForm (S3Client $client, $lookup_id, $photo_id) {
        $project = Project::with(['packages', 'items'])->firstWhere('lookup_id', $lookup_id);

        if (is_null($project)) {
            return Redirect::to("/");
        }

        try {
            $photo = $client->getObject([
                'Bucket' => 'kdi-photo',
                'Key' => "{$project->directory}/{$photo_id}.jpg"
            ]);
        } catch (\Aws\S3\Exception\S3Exception $e) {
            if($e->getStatusCode() == 404) {
                return Redirect::to("/");
            }
            throw $e;
        }

        return Inertia::render('Lookup', [
            'project' => $project,
            'photo_id' => $photo_id
        ]);
    }
}
