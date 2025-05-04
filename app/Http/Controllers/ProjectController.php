<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return inertia('Projects/Index');
    }

    public function create()
    {
        return inertia('Projects/Create');
    }

    public function store(Request $request)
    {
        // Store logic here
    }

    public function show($id)
    {
        return inertia('Projects/Show', [
            'projectId' => $id,
        ]);
    }
}
