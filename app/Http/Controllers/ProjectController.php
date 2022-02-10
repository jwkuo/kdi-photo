<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Package;
use App\Models\Item;
use Inertia\Inertia;
use Aws\S3\S3Client;
use Redirect;

class ProjectController extends Controller
{
    public function list (S3Client $client) {
        $projects = Project::latest()->get();
        return Inertia::render('Admin/Projects', [
            'projects' => $projects
        ]);
    }

    public function create () {
        return Inertia::render('Admin/CreateProject');
    }

    public function createProject (Request $request) {
        $request->validate([
            'projectName' => ['required'],
            'lookupId' => ['required'],
            'directory' => ['required']
        ]);

        $project = Project::create([
            'name' => $request->input('projectName'),
            'lookup_id' => $request->input('lookupId'),
            'directory' => $request->input('directory')
        ]);

        return Redirect::to('/admin/projects/' . $project->id);
    }

    public function editProject ($id) {
        $project = Project::with(['packages', 'items'])->findOrFail($id);

        return Inertia::render('Admin/EditProject', [
            'project' => $project
        ]);
    }

    public function saveProject (Request $request, $id) {
        $project = Project::find($id);
        
        $request->validate([
            'projectName' => ['required'],
            'lookupId' => ['required'],
            'directory' => ['required']
        ]);

        $project->name = $request->input('projectName');
        $project->lookup_id = $request->input('lookupId');
        $project->directory = $request->input('directory');
        $project->sheet_options = $request->input('sheet_options') ?? '';
        $project->sheet_prices = $request->input('sheet_prices') ?? '';
        $project->sheet_promo = $request->input('sheet_promo') ?? '';

        // Packages

        $submitted_packages = collect($request->input('packages'));

        // Remove any packages that aren't submitted
        foreach ($project->packages as $package) {
            if (!$submitted_packages->contains('id', $package['id'])) {
                $package->delete();
            }
        }

        $new_packages = collect();

        foreach ($submitted_packages as $package) {
            if (is_null($package['id'])) {
                $new_packages->push(new Package([
                    'name' => $package['name'],
                    'description' => $package['description'],
                    'price' => $package['price'],
                    'sheet_options' => $package['sheet_options']
                ]));
            } else {
                $pack = $project->packages->find($package['id']);
                $pack->name = $package['name'];
                $pack->description = $package['description'];
                $pack->price = $package['price'];
                $pack->sheet_options = $package['sheet_options'];
                $pack->save();
            }
        }

        // Items

        $submitted_items = collect($request->input('items'));

        foreach ($project->items as $item) {
             if (!$submitted_items->contains('id', $item['id'])) {
                $item->delete();
            }
        }

        $new_items = collect();

        foreach ($submitted_items as $item) {
            if (is_null($item['id'])) {
                $new_items->push(new Item([
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'description' => $item['description'] ?? '',
                    'multi' => $item['multi'],
                    'auto' => $item['auto'],
                    'input_option' => $item['input_option'],
                    'input_label' => $item['input_label'] ?? ''
                ]));
            } else {
                $existing_item = $project->items->find($item['id']);
                $existing_item->name = $item['name'];
                $existing_item->price = $item['price'];
                $existing_item->description = $item['description'] ?? '';
                $existing_item->multi = $item['multi'];
                $existing_item->auto = $item['auto'];
                $existing_item->input_option = $item['input_option'];
                $existing_item->input_label = $item['input_label'] ?? '';
                $existing_item->save();
            }
        }

        $project->save();

        $project->packages()->saveMany($new_packages);

        $project->items()->saveMany($new_items);

        return Redirect::route('Projects');
    }

    public function duplicateProject(Request $request) {
        $project = Project::find($request->input('project_id'));

        $packages = $project->packages;

        $items = $project->items;

        $new_project = $project->replicate();

        $new_project->save();

        $new_packages = [];
        foreach($packages as $pack) {
            $new_packages[] = $pack->replicate();
        }
        $new_project->packages()->saveMany($new_packages);

        $new_items = [];
        foreach($items as $item) {
            $new_items[] = $item->replicate();
        }
        $new_project->items()->saveMany($new_items);

        return Redirect::to("/admin/projects/{$new_project->id}");
    }

    public function deleteProject(Request $request) {
        $project = Project::find($request->input('project_id'));

        foreach($project->packages as $package) {
            $package->delete();
        }

        foreach($project->items as $item) {
            $item->delete();
        }

        $project->delete();

        return Redirect::route('Projects');
    }
}
