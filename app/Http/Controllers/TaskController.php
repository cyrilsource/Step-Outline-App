<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Task;
use App\Http\Resources\Task as TaskResource;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::with('color')->orderBy('order')->get();

        return TaskResource::collection($tasks);

    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function html()
    {
        $tasks = Task::with('color')->orderBy('order')->get();

        return view('html', ['tasks' => $tasks]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $count, $type)
    {
        $last = $count +1;
        $task = new Task;
        $task->title = $request->input('title');
        $task->description = $request->input('description');
        $task->color_id = $request->input('color_id');
        $task->type = $type;

        //if no choice order put the task in the end
        if ($request->input('order') === null) {
            $task->order = $last;
        }
        else {
            $task->order =  $request->input('order');
        }

        if($task->save()) {
            return new TaskResource($task);
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
        $task = Task::with('color')->findOrFail($id);

        return new TaskResource($task);
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

        $task = Task::findOrFail($id);
        $task->title = $request->input('title');
        $task->description = $request->input('description');
        $task->color_id = $request->input('color_id');

        if($task->save()) {
            return new TaskResource($task);
        }
    }

    public function updateOrder(Request $request)
    {
        Task::truncate();
        $tasks = $request->all();
        foreach ($tasks as $task) {
            Task::create([
                'id' => $task['id'],
                'title' => $task['title'],
                'description' => $task['description'],
                'type' => $task['type'],
                'color_id' => $task['color_id'],
                'order' => $task['order'],
            ]);
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
        $task = Task::findOrFail($id);

        if ($task->delete()) {
            return new TaskResource($task);
        }
    }

    /**
     * Search a name or first name in the list of customers
     *
     * @return \Illuminate\Http\Response
     */
    public function search($query=null)
    {
        if ($search = $query) {
            $tasks = Task::where("title", "LIKE", "%".$search."%")
            ->orWhere("description", "LIKE", "%".$search."%")
            ->with('color')
            ->orderBy('order')
            ->get();
        }
        else {
            $tasks = Task::with('color')->orderBy('order')->get();
        }

        return TaskResource::collection($tasks);
    }
}
