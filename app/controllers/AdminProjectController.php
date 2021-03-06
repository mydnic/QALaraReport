<?php

class AdminProjectController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$projects = Project::withTrashed()->orderBy('name')->get();
		return View::make('admin.project.index')
			->with('projects', $projects);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make(Input::all(), Project::$rules);

		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		}

		$project = new Project;
		$project->name = Input::get('name');
		$project->url = Input::get('url');
		$project->is_mobile = Input::get('is_mobile');
		$project->save();

		Flash::success('Project added !');
		return Redirect::back();
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$project = Project::withTrashed()->find($id);

		return View::make('admin.project.show')
			->with('project', $project);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$project = Project::withTrashed()->find($id);

		return View::make('admin.project.edit')
			->with('project', $project);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$validator = Validator::make(Input::all(), Project::$rules);

		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		}

		$project            = Project::withTrashed()->find($id);
		$project->name      = Input::get('name');
		$project->url       = Input::get('url');
		$project->is_mobile = Input::get('is_mobile');
		$project->save();

		Flash::success('Project updated !');
		return Redirect::back();
	}

	public function enable($id)
	{
		$project = Project::onlyTrashed()->find($id);
		$project->restore();
		return Redirect::back();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$project = Project::find($id);
		$project->delete();
		return Redirect::back();
	}


}
