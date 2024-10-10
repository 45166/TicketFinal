@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm border-primary">
        <div class="card-header bg-secondary text-white">
            <h5 class="card-title">ผลการค้นหาสำหรับแท็กหมายเลข: {{ $tagNumber }}</h5>
        </div>
        <div class="card-body">
            @if($results->isEmpty())
                <p class="text-center">ไม่พบข้อมูลการแจ้งซ่อมสำหรับหมายเลขแท็กนี้</p>
            @else
                <!-- ใช้ table-responsive เพื่อทำให้ตารางเลื่อนได้ในแนวนอนเมื่อหน้าจอเล็ก -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">RequestID</th>
                                 <th scope="col">TicketID</th>
                                <th scope="col">เวลา/วัน/เดือน/ปี</th>
                                <th scope="col">สถานะ</th>
                                <th scope="col">อุปกรณ์</th>
                              
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $request)
                                <tr>
                                    <td>{{ $request->TicketNumber }}</td>
                                    <td>{{ $request->TagNumber }}</td>
                                    <td>{{ \Carbon\Carbon::parse($request->Date)->format('H:i/d/m/Y ')}}</td>
                                    
                                    <td>
                                        @switch($request->status->Statusname)
                                            @case('Pending')
                                                <span class="badge bg-warning text-dark">รอดำเนินการ</span>
                                                @break
                                            @case('รับเรื่อง')
                                                <span class="badge bg-info text-white">รับเรื่องแล้ว</span>
                                                @break
                                            @case('กำลังดำเนินการ')
                                                <span class="badge bg-primary text-white">กำลังดำเนินการ</span>
                                                @break
                                            @case('ดำเนินการแล้ว')
                                                <span class="badge bg-success text-white">ดำเนินการแล้ว</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">Unknown</span>
                                        @endswitch
                                    </td>
                                   
                                    <td>{{ $request->device->Devicename ?? 'N/A' }}</td>
                       
                                 
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- ปุ่มกลับไปยังหน้า -->
            <div class="row mt-4">
                <div class="col text-center">
                    @if(Auth::user()->role == 2)
                        <a href="{{ route('repair_request.index') }}" class="btn btn-primary">กลับ</a>
                    @elseif(Auth::user()->role == 1)
                        <a href="{{ route('some_route_for_role_1') }}" class="btn btn-primary">กลับ</a>
                    @elseif(Auth::user()->role == 0)
                        <a href="{{ route('admin') }}" class="btn btn-primary">กลับ</a>
                    @else
                        <a href="{{ route('home') }}" class="btn btn-primary">กลับไปยังหน้าแรก</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
