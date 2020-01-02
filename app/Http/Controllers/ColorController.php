<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Color;
use App\Task;
use App\Http\Resources\Color as ColorResource;
use App\Http\Resources\Task as TaskResource;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $colors = Color::all();

        return ColorResource::collection($colors);
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
        $color = new Color;
        $color->title = $request->input('title');
        $color->description = $request->input('description');
        $color->color = $request->input('color');

        if($color->save()) {
            return new ColorResource($color);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $color = Color::findOrFail($id);

        return new ColorResource($color);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $color = Color::findOrFail($id);
        $color->title = $request->input('title');
        $color->description = $request->input('description');
        $color->color = $request->input('color');

        if($color->save()) {
            return new ColorResource($color);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $color = Color::findOrFail($id);
        $color->title = $request->input('title');
        $color->description = $request->input('description');
        $color->color = $request->input('color');

        if($color->save()) {
            return new ColorResource($color);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $color = Color::findOrFail($id);
        //we must put null in the color_id row
        $tasks = Task::where('color_id', $id)->get();
        foreach ($tasks as $task) {
            $task->color_id = null;
            $task->save();

        }

        if ($color->delete()) {
            return new ColorResource($color);
        }
    }
}
