<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class OrgChartController extends Controller
{
    private function validationRules()
    {
        return [
            'job_id' => 'required|string',
            'job_title' => 'required|string',
            'emp_name' => 'required|string',
            'emp_id' => 'required|integer',
            'email' => 'required|email',
        ];
    }

    private function manageCSV($fileContents)
    {
        $rules = $this->validationRules();
        $current_timestamp = Carbon::now()->toDateTimeString();
        $employeeData = [];
        $invalidData = [];

        foreach ($fileContents as $line) {
            $data = str_getcsv($line);
            $employee = [
                'job_id' => $data[0],
                'job_title' => $data[1],
                'emp_name' => $data[2],
                'emp_id' => $data[3],
                'email' => $data[4],
                'report_to_job_id' => $data[5],
                'report_to_name' => $data[6],
                'role_priority' => $data[7],
                'job_level' => $data[8],
                'is_root' => $data[9],
                'created_at' => $current_timestamp,
                'updated_at' => $current_timestamp
            ];

            $validator = Validator::make($employee, $rules);

            if ($validator->fails()) {
                $invalidData[] = $employee;
            } else {
                $employeeData[] = $employee;
            }
    }
        return [
            'employeeData' => $employeeData,
            'invalidData' => $invalidData,
        ];
    }




   public function store(Request $request)
    {

        $file = $request->file('file');
        $fileContents = file($file->getPathname());
        array_shift($fileContents);
        $processed=$this->manageCSV($fileContents);
        $employeeData=$processed['employeeData'];
        $invalidData=$processed['invalidData'];
      
        if (!empty($employeeData) &&  empty($invalidData)) { //To check if only got valid data in the csv 
            Employee::insert($employeeData);
           return response()->json(['message' => 'Success','data'=>$employeeData ], 201);
        } elseif(!empty($employeeData) && !empty($invalidData)){ // to check if got valid data and invalid data in the csv
            Employee::insert($employeeData);
           return response()->json(['message' => 'Success with Unsuccessful Data','Sucsessful Data'=>$employeeData , 'Unsuccessful Data'=>$invalidData ], 201);
        }elseif(!empty($invalidData)){ // To check if only got invalid data in the csv 
           return response()->json(['message' => 'Unsuccessful','Unsuccessful Data'=>$invalidData ], 201);
        }else{ // To check if no data in csv
            return response()->json(['message' => 'Unsuccessful','No data found' ], 201);
        }
    
    }

    public function update(Request $request)
    {
        $file = $request->file('file');
        $fileContents = file($file->getPathname());
        array_shift($fileContents);
        $processed=$this->manageCSV($fileContents);
        $employeeData=$processed['employeeData'];
        $invalidData=$processed['invalidData'];

        if (!empty($employeeData) &&  empty($invalidData)) {
            Employee::upsert($employeeData,['email'],['emp_name','report_to_job_id','report_to_name']);
            return response()->json(['message' => 'Success', 'data' =>$employeeData ], 201);
        } elseif(!empty($employeeData) && !empty($invalidData)){
            Employee::upsert($employeeData,['email'],['emp_name','report_to_job_id','report_to_name']);
           return response()->json(['message' => 'Success with Unsuccessful Data','Sucsessful Data'=>$employeeData , 'Unsuccessful Data'=>$invalidData ], 201);
        }elseif(!empty($invalidData)){
            return response()->json(['message' => 'Unsuccessful','Unsuccessful Data'=>$invalidData ], 201);
         }else{
            return response()->json(['message' => 'Unsuccessful','No data found' ], 201);
        }
    
    }
}
// http://127.0.0.1:8000/api/orgchart/add  to test in postman
//key - file and upload the orgchart csv file


