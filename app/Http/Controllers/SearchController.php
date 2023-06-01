<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\HRISDavaoEmployee;
use App\HRISAgusanEmployee;


class SearchController extends Controller
{   

    public function search_hris_employee(Request $req)
    {
        $query = $req->get('query');

        $dataAgusan = HRISAgusanEmployee::where('EmpID','like',"%$query%")->orWhere('FullName','LIKE',"%$query%")->where('Active','=','1')->with(['posDetails','comDetails','deptDetails'])->get();
        $dataDavao  = HRISDavaoEmployee::where('EmpID','like',"%$query%")->orWhere('FullName','LIKE',"%$query%")->where('Active','=','1')->with(['posDetails','comDetails','deptDetails'])->get();

        $mergedData = array_merge($dataAgusan->toArray(), $dataDavao->toArray());

        $output = '<ul class="form-control" style="display:block; position:relative; list-style:none;">';

        $data = [];

        if(!empty($mergedData)) {
            foreach($mergedData as $rs) {
                            
                // $output .= "<li class='emp_list'><a href='#'>".$rs['EmpID'].': '.$rs['FullName']."<span style='display:none;'>=".$rs['EmpID']."=".$rs['FName']."=".$rs['MName']."=".$rs['LName']."=".$rs['com_details']['CompanyName']."=".$rs['dept_details']['DeptDesc']."=".$rs['pos_details']['PositionDesc']."</span></a></li>";
                $output .= "<li class='emp_list'><a href='#'>".$rs['EmpID'].': '.$rs['FullName']."<span style='display:none;'>=".$rs['EmpID']."=".$rs['FullName']."=".$rs['com_details']['CompanyName']."=".$rs['dept_details']['DeptDesc']."=".$rs['pos_details']['PositionDesc']."</span></a></li>";
            }
        } else {
             $output .= "<li class='emp_list'><a href='#'>NO RECORDS FOUND!<span style='display:none;'></span></a></li>";
        }

        $output .= '</ul>';

        echo $output;
    }



}
