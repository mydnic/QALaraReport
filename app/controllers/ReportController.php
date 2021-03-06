<?php

class ReportController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
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
		$project = Project::find(Input::get('project_id'));

		$report = new Report;
		$report->project_id = $project->id;
		$report->user_id = Auth::id();
		$report->version_id = 0;
		$report->platform_version = Input::get('os');
		$report->status = 'Sent';
		$report->note = Input::get('note');
		$report->save();
	
		if (Input::hasFile('images')) {
			
			$files = Input::file('images');
			
			foreach($files as $file) {
				$destinationPath = public_path().'/uploads/';
				$filename        = str_random(6) . '_' . $file->getClientOriginalName();
				$uploadSuccess   = $file->move($destinationPath, $filename);

				$image = new Image;
				$image->report_id = $report->id;
				$image->filename = $filename;
				$image->save();
			}
		}


		foreach ($project->features as $feature) {
			if (Input::has($feature->id)) {
				foreach (Input::get($feature->id) as $platform_id) {
					$test = new Test;
					$test->report_id = $report->id;
					$test->feature_id = $feature->id;
					if ($platform_id == 'mobile') {
						$test->platform_id = 0;
					} else {
						$test->platform_id = $platform_id;
					}
					$test->functional = true;
					$test->save();
				}
			}
		}

		Flash::success('Reporting has been sent!');
		return Redirect::route('report.show', $report->id);

	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$report = Report::find($id);
		return View::make('report.show')
			->with('report', $report);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
