<?php

namespace App\Http\Controllers;

use App\Models\Schedule; 
use App\Models\RepairRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Log, Http};
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index()
    {
        return view('schedule.index');
    }

   public function create(Request $request)
{
    $item = new Schedule();
    $item->user_id = Auth::id();
    $item->title = $request->title;
    $item->start = Carbon::parse($request->start)->setTimezone('Asia/Bangkok');

    // กำหนดเวลาเสร็จจากเวลาเริ่ม หรือตั้งเวลาเริ่มและสิ้นสุดให้เหมือนกัน
    $item->end = $item->start; // หรือกำหนดให้สิ้นสุดในช่วงเวลาที่ต้องการ

    $item->description = $request->description;
    $item->color = $request->color;
    $item->TicketID = $request->TicketID; 
    $item->save();

    return redirect('/fullcalender');
}

public function showAddScheduleForm($ticketID)
{
    $ticket = RepairRequest::where('TicketID', $ticketID)->first();

    // ตรวจสอบว่า $ticket ไม่เป็น null ก่อนส่งไปยัง View
    if (!$ticket) {
        return redirect('/some-error-page')->with('error', 'Ticket not found');
    }

    return view('schedule.add', compact('ticket'));
}
    

public function getEvents()
{
    $schedules = Schedule::with(['repairRequest.user'])
        ->select('id', 'title', 'start', 'end', 'color', 'TicketID')
        ->get()
        ->map(function($schedule) {
            // ตรวจสอบว่ามี RepairRequest หรือไม่
            if ($schedule->repairRequest) {
                // Log ข้อมูลที่เกี่ยวข้อง
                Log::info('Schedule RepairRequest:', [
                    'repairRequest' => $schedule->repairRequest->toArray(), // แปลงเป็น array
                ]);
                $userName = $schedule->repairRequest->user->name ?? 'ไม่ทราบ';
                $repairDetail = $schedule->repairRequest->RepairDetail ?? 'ไม่มีรายละเอียด';
            } else {
                Log::info('No RepairRequest found for Schedule ID: ' . $schedule->id);
                $userName = 'ไม่ทราบ';
                $repairDetail = 'ไม่มีรายละเอียด';
            }

            return [
                'id' => $schedule->id,
                'title' => $schedule->title,
                'start' => $schedule->start,
                'end' => $schedule->end,
                'color' => $schedule->color,
                'extendedProps' => [
                    'user_name' => $userName,
                    'RepairDetail' => $repairDetail
                ]
            ];
        });

    Log::info('Schedules:', $schedules->toArray());
    return response()->json($schedules);
}



    public function deleteEvent($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();
        return response()->json(['message' => 'Event deleted successfully']);
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->update([
            'start' => Carbon::parse($request->input('start_date'))->setTimezone('Asia/Bangkok'),
            'end' => Carbon::parse($request->input('end_date'))->setTimezone('Asia/Bangkok'),
        ]);
        return response()->json(['message' => 'Event moved successfully']);
    }

    public function resize(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);
        $newEndDate = Carbon::parse($request->input('end_date'))->setTimezone('Asia/Bangkok');
        $schedule->update(['end' => $newEndDate]);
        return response()->json(['message' => 'Event resized successfully.']);
    }
}