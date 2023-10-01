<?php
namespace App\Services;

use DateTime;
use Carbon\Carbon;
use App\Models\Checkin;
use App\Models\MerchandiserTimeSheet;

class CheckinService
{
    public function processOverdueCheckins()
    {
        $time = 8;
        print("checking process | ");

        //for edit the timesheet if the last visit is not checkout
        $timeSheets = MerchandiserTimeSheet::all();
        if ($timeSheets && count($timeSheets) > 0) 
        {
            print("timesheeet process | ");
            foreach ($timeSheets as $key => $timeSheet) {
                $records = $timeSheet->timeSheetRecords;
                $recordsCount = count($records);

                print($records[$recordsCount-1]->status);

                if($records[$recordsCount-1]->status != 'check-out'){
                    // foreach ($records as $key => $record) {
                    //     if($record->status == 'end-break-time'){
                    //         $time = 8.50;
                    //         $time_diff = $record->time - $records[$key-1]->time;
                            
                    //     }

                    //     if($record->status == 'end-lunch-time'){
                            
                    //     }
                    // }
                    
                    print("checkout process | ");
    
                    $checkin = $records[0];
                    $checkinTime = $checkin->time;
                    $checkinDate = $checkin->date;
                    $checkinDateTime = $checkinTime . " " . $checkinDate;
                    $dateFormat = new DateTime($checkinDateTime);
                    if($dateFormat <= Carbon::now()->subHours($time)){
                        print("set checkout  process | ");

                        $checkoutDateTime = clone $dateFormat; // Create a copy to avoid modifying the original object
                        $checkoutDateTime->modify('+8 hours');

                        // Add 8 hours to the date component
                        // Separate date and time
                        $checkoutDate = $checkoutDateTime->format('Y-m-d');
                        $checkoutTime = $checkoutDateTime->format('H:i:s');
                        
                        $recordArray=[
                            'date'=>$checkoutDate,
                            'time'=> $checkoutTime,
                            'status'=> 'check-out',
                            'gps_location'=> $records[$recordsCount-1]->gps_location
                        ];
                        
                        print($checkoutDate . " | ");
                        // $time_sheet->signature=null;
                        $timeSheetRecord = $timeSheet->timeSheetRecords()->create($recordArray);
                        print($timeSheetRecord);

                    }
                }
            }
        }
    }
}
