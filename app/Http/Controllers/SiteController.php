<?php

namespace App\Http\Controllers;

use App\Site;
use Illuminate\Http\Request;
use App\Http\Resources\Site as SiteResource;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get sites
        // $sites = Site::orderBy('created_at', 'desc')->paginate(5);
        $sites = Site::latest()->paginate(5);

        // Return collection of sites as a resource
        return SiteResource::collection($sites);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Get the site and method
        $site = $request->isMethod('put') ? Site::findOrFail($request->id) : new Site;
        $site->id = $request->input('site_id');
        $site->name = $request->input('name');
        $site->description = $request->input('description');

        // Perform save
        if ($site->save()) {
            return new SiteResource($site);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get site
        $site = Site::findOrFail($id);

        // Return single site as a resource
        return new SiteResource($site);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function edit(Site $site)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Site $site)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get site
        $site = Site::findOrFail($id);

        // Perform delete
        if($site->delete()) {
            return new SiteResource($site);
        }  
    }
}
