<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        //
        $tasks = Tasks::latest()->get();
        return response()->json($tasks);
    }

    public function search(Request $request){
        if($request->type == 'daily'){
            $tasks = Tasks::where('user_id', Auth::id())->whereDate('created_at', date('Y-m-d', strtotime($request->date)))->get()->toArray();
        }else{
            $endDate = date("Y-m-d",strtotime("+1day",strtotime($request->date)));
            if($request->end_date){
                $endDate = date('Y-m-d', strtotime($request->end_date));
            }

            $tasks = Tasks::where('user_id', Auth::id())->whereBetween('created_at', [date('Y-m-d', strtotime($request->date)), $endDate])->get();
        }
        return response()->json($tasks);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        //
        $this->validate(request(),[
            'name' => 'required'
        ]);

        $data = $request->all();

        $todo = new Tasks();
        $todo->name = $data['name'];
        $todo->user_id = Auth::id();

        $todo->save();

        return response()->json(['message' => 'Task Created Successfully!', 'task' => $todo], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param Tasks $tasks
     * @return JsonResponse
     */
    public function show(Tasks $tasks)
    {
        //
        return response()->json($tasks, 200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Tasks $tasks
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request)
    {
        //
        $this->validate(request(),[
            'description' => 'required'
        ]);

        $data = $request->all();
        $tasks = Tasks::find($request->id);
        $tasks->description = $data['description'];
        $tasks->save();

        return response()->json(['message' => 'Task Updated Successfully!', $tasks], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Tasks $tasks
     * @return JsonResponse
     */
    public function destroy($id)
    {
        //
        $tasks = Tasks::find($id);

        $tasks->delete();

        return response()->json(['message' => 'Task Deleted Successfully!'], 200);
    }
    public function complete(Request $request)
    {
        $tasks = Tasks::find($request->id);

        $tasks->completed = !$tasks->completed;

        $tasks->save();

        return response()->json(['message' => 'Task Completed Successfully!'], 200);
    }
    public function pinned(Request $request)
    {
        $tasks = Tasks::find($request->id);

        $tasks->pinned = !$tasks->pinned;

        $tasks->save();

        return response()->json(['message' => 'Task Completed Successfully!'], 200);
    }
}
