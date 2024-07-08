<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class OrgChartController extends Controller
{

    public function store(Request $request)
    {

        $file = $request->file('file');
        $fileContents = file($file->getPathname());
        array_shift($fileContents);
        $processed=$this->manageCSVStore($fileContents);
        $employeeData=$processed['employeeData'];
        $invalidData=$processed['invalidData'];
        $duplicateEntries=$processed['duplicateEntries'];
        $duplicateData=$processed['duplicateData'];
        $responseScenario=$this->manageResponseStore($employeeData,$invalidData,$duplicateEntries,$duplicateData);
        $responseData=$responseScenario['manageResponse'];
      

         return response()->json($responseData, 201);
    
    
    }

    public function update(Request $request)
    {
        $file = $request->file('file');
        $fileContents = file($file->getPathname());
        array_shift($fileContents);
        $processed=$this->manageCSVUpdate($fileContents);
        $employeeData=$processed['employeeData'];
        $invalidData=$processed['invalidData'];
        $duplicateEntries=$processed['duplicateEntries'];

        $responseScenario=$this->manageResponseUpdate($employeeData,$invalidData,$duplicateEntries);
        $responseData=$responseScenario['manageResponse'];


        return response()->json($responseData, 201);
    }

    private function manageCSVStore($fileContents)
    {
        $rules = $this->validationRules();
        $employeeData = [];
        $invalidData = [];
        $duplicateEntries = [];
        $duplicateData = [];
        $existingEmails = [];

        foreach ($fileContents as $line) {
            $data = str_getcsv($line);
            $email = $data[4];
            $employee = $this->employeeDetails($data);

            if (isset($existingEmails[$email])||Employee::where('email', $email)->exists()) {
                if (isset($existingEmails[$email])) {
                    $duplicateEntries[] = $employee;
                }

                if (Employee::where('email', $email)->exists()) {
                    $duplicateData[] = $employee;
                }
                $existingEmails[$email] = true;
                continue;
            }

            $existingEmails[$email] = true;
        
            $validator = Validator::make($employee, $rules);

            if ($validator->fails()) {
                $invalidData[] = $employee;
            } else {
                $employeeData[] = $employee;
            }

        }  
        return [
         'employeeData'=>$employeeData,
         'invalidData'=>$invalidData,
         'duplicateEntries'=>$duplicateEntries,
         'duplicateData'=>$duplicateData,
        ];
    }

    private function manageResponseStore($employeeData, $invalidData, $duplicateEntries, $duplicateData)
    {
        $responseData = [];
        $count = 0;
    
        if (!empty($employeeData)) {
            Employee::insert($employeeData);
            $responseData = $this->handleSuccessResponseStore($employeeData, $invalidData, $duplicateEntries, $duplicateData, $count);
        } else {
            $responseData = $this->handleUnsuccessfulResponseStore($invalidData, $duplicateEntries, $duplicateData, $count);
        }
    
        return ['manageResponse' => $responseData];
    }
    
    private function handleSuccessResponseStore($employeeData, $invalidData, $duplicateEntries, $duplicateData, &$count)
    {
        $responseData['message'][$count] = 'Success';
        $responseData['Successful Data'] = $employeeData;
    
        $this->addUnsuccessfulMessagesStore($responseData, $invalidData, 'Success with Unsuccessful Data (Invalid Data)', 'Invalid Data', $count);
        $this->addUnsuccessfulMessagesStore($responseData, $duplicateEntries, 'Success with Unsuccessful Data (Duplicate Entries)', 'Duplicate Entries Data', $count);
        $this->addUnsuccessfulMessagesStore($responseData, $duplicateData, 'Success with Unsuccessful Data (Duplicate Data in Database)', 'Duplicate Data', $count);
    
        return $responseData;
    }
    
    private function handleUnsuccessfulResponseStore($invalidData, $duplicateEntries, $duplicateData, &$count)
    {
        $responseData = [];
    
        if (empty($invalidData) && empty($duplicateData) && empty($duplicateEntries)) {
            $responseData['message'][$count] = 'Unsuccessful';
            $responseData['No Data Found'] = true;
        } else {
            $this->addUnsuccessfulMessagesStore($responseData, $invalidData, 'Unsuccessful (Invalid Data)', 'Invalid Data', $count);
            $this->addUnsuccessfulMessagesStore($responseData, $duplicateEntries, 'Unsuccessful (Duplicate Entries)', 'Duplicate Entries Data', $count);
            $this->addUnsuccessfulMessagesStore($responseData, $duplicateData, 'Unsuccessful (Duplicate Data in Database)', 'Duplicate Data', $count);
        }
    
        return $responseData;
    }
    
    private function addUnsuccessfulMessagesStore(&$responseData, $data, $message, $key, &$count)
    {
        if (!empty($data)) {
            $responseData['message'][$count] = $message;
            $responseData[$key] = $data;
            $count++;
        }
    }


    private function manageCSVUpdate($fileContents)
    {
        $rules = $this->validationRules();
        $employeeData = [];
        $invalidData = [];
        $duplicateEntries = [];
        $existingEmails = [];

        foreach ($fileContents as $line) {
            $data = str_getcsv($line);
            $email = $data[4];
            $employee = $this->employeeDetails($data);

            if (isset($existingEmails[$email])) {
                $duplicateEntries[] = $employee;
                continue;
            }
                
            $existingEmails[$email] = true;
        
            $validator = Validator::make($employee, $rules);

            if ($validator->fails()) {
                $invalidData[] = $employee;
            } else {
                $employeeData[] = $employee;
            }

        }
        return [
         'employeeData'=>$employeeData,
         'invalidData'=>$invalidData,
         'duplicateEntries'=>$duplicateEntries,
        ];
    }

    private function manageResponseUpdate($employeeData, $invalidData, $duplicateEntries)
    {
        $responseData = [];
        $count = 0;

        if (!empty($employeeData)) {
            Employee::upsert($employeeData, ['email'], ['emp_name', 'report_to_job_id', 'report_to_name']);
            $responseData = $this->handleSuccessResponseUpdate($employeeData, $invalidData, $duplicateEntries, $count);
        } else {
            $responseData = $this->handleUnsuccessfulResponseUpdate($invalidData, $duplicateEntries, $count);
        }

        return ['manageResponse' => $responseData];
    }

    private function handleSuccessResponseUpdate($employeeData, $invalidData, $duplicateEntries, &$count)
    {
        $responseData['message'][$count] = 'Success';
        $responseData['Successful Data'] = $employeeData;

        $this->addUnsuccessfulMessagesUpdate($responseData, $invalidData, 'Success with Unsuccessful Data (Invalid Data)', 'Invalid Data', $count);
        $this->addUnsuccessfulMessagesUpdate($responseData, $duplicateEntries, 'Success with Unsuccessful Data (Duplicate Entries)', 'Duplicate Entries Data', $count);

        return $responseData;
    }

    private function handleUnsuccessfulResponseUpdate($invalidData, $duplicateEntries, &$count)
    {
        $responseData = [];

        if (empty($invalidData) && empty($duplicateEntries)) {
            $responseData['message'][$count] = 'Unsuccessful';
            $responseData['No Data Found'] = true;
        } else {
            $this->addUnsuccessfulMessagesUpdate($responseData, $invalidData, 'Unsuccessful (Invalid Data)', 'Invalid Data', $count);
            $this->addUnsuccessfulMessagesUpdate($responseData, $duplicateEntries, 'Unsuccessful (Duplicate Entries)', 'Duplicate Entries Data', $count);
        }

        return $responseData;
    }

    private function addUnsuccessfulMessagesUpdate(&$responseData, $data, $message, $key, &$count)
    {
        if (!empty($data)) {
            $responseData['message'][$count] = $message;
            $responseData[$key] = $data;
            $count++;
        }
    }


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

    private function employeeDetails($data)
    {
        $current_timestamp = Carbon::now()->toDateTimeString();
        return [
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
    }


}



