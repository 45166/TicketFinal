<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegisterDevice;
use App\Models\Device; // Import the Device model

class RegisterDeviceController extends Controller
{
    public function create()
    {
        // Fetch all devices from the 'devices' table
        $devices = Device::all();

        // Pass the devices to the view
        return view('register_device.create', compact('devices'));
    }

    public function store(Request $request)
    {
        // Validate ข้อมูลที่กรอก
        $validated = $request->validate([
            'EquipmentNumber' => 'required|string|max:255',
            'Brand' => 'required|string|max:255',
            'Model' => 'required|string|max:255',
            'DeviceID' => 'required|exists:devices,DeviceID', // Validation for DeviceID
            'OtherFeatures' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        // บันทึกข้อมูลลงฐานข้อมูล
        RegisterDevice::create($validated);

        return redirect()->route('register_device.create')->with('success', 'Device registered successfully.');
    }
}
