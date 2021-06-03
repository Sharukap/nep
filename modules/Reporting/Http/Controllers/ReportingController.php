<?php

namespace Reporting\Http\Controllers;


use App\Models\User;

use App\Models\Form_Type;
use App\Models\Organization;
use App\Models\Process_Item;
use App\Models\Province;
use App\Models\District;
use App\Models\Tree_Removal_Request;

use App\Models\Development_Project;

use App\Models\Environment_Restoration;
use App\Models\Environment_Restoration_Activity;
use App\Models\Environment_Restoration_Species;
use App\Models\Ecosystem;

use App\Models\Crime_report;
use App\Models\Crime_type;
use App\Models\Env_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PDF;

class ReportingController extends Controller
{
    //OVERVIEW TAB CHARTS
    public function overview()
    {
        if (Auth::user()->role_id < 3) {
            $process_items = Process_Item::all();
        } elseif (Auth::user()->role_id == 6) {
            $process_items = Process_Item::where('created_by_user_id', Auth::user()->id)->get();
        } else {
            $process_items = Process_Item::where('activity_organization', Auth::user()->organization_id)->get();
        }
        session()->put('processItems', $process_items);

        return view('reporting::overview', ['process_items' => $process_items]);
    }
    public function overviewReport()
    {
        $chart1 = request('chart1');
        $chart2 = request('chart2');
        $chart3 = request('chart3');
        $process_items = session('processItems');
        $pdf = PDF::loadView('reporting::overviewReport', ['process_items' => $process_items, 'chart1' => $chart1, 'chart2' => $chart2, 'chart3' => $chart3]);
        return $pdf->stream('report.pdf');
    }
    public function filterOverview()
    {
        $formType = request('form_type');
        $time = request('time');
        switch ($time) {
            case 1:
                $process_items = Process_Item::whereMonth('created_at', now()->month)->get();
                break;
            case 2:
                $month = (now()->month) - 03;
                $process_items = Process_Item::whereMonth('created_at', '>', $month)->get();
                break;
            case 3:
                $process_items = Process_Item::whereMonth('created_at', now()->year)->get();
                break;
            default:
                $process_items = Process_Item::all();
        }
        if ($formType != 0) {
            $process_items = $process_items->where('form_type_id', $formType);
        }

        if (Auth::user()->role_id < 3) {
        } elseif (Auth::user()->role_id == 6) {
            $process_items = $process_items->where('created_by_user_id', Auth::user()->id);
        } else {
            $process_items = $process_items->where('activity_organization', Auth::user()->organization_id);
        }
        session()->put('processItems', $process_items);
        return view('reporting::overview', ['process_items' => $process_items]);
    }
    //Process Item per month Line Chart
    public function getAllProcessItems()
    {
        $month_year_array = array();
        $process_items_dates = Process_Item::orderBy('created_at', 'ASC')->pluck('created_at');
        $process_items_dates = json_decode($process_items_dates);
        if (!empty($process_items_dates)) {
            foreach ($process_items_dates as $unformatted_date) {
                $date = new \DateTime($unformatted_date);
                $month_no = strval($date->format('m'));
                $year_no = strval($date->format('y'));
                $month_year_no = $month_no . $year_no;
                $month_name = $date->format('M');
                $year_name = $date->format('Y');
                $month_year_name = $month_name . (" ") . $year_name;
                $month_year_array[$month_year_no] = $month_year_name; //obtains as array of month and years
            }
        }
        return $month_year_array;
    }
    function getMonthlyProcessItemCount($month)
    {
        $month_val = intval(substr($month, 0, 2));
        $year_val = intval("20" . substr($month, 2));
        $monthly_process_item_count = Process_Item::whereYear('created_at', $year_val)->whereMonth('created_at', $month_val)->get()->count();
        return $monthly_process_item_count;
    }

    function getMonthlyProcessItemData()
    {

        $monthly_process_item_count_array = array();
        $month_year_array = $this->getAllProcessItems();
        $month_year_name_array = array();
        if (!empty($month_year_array)) {
            foreach ($month_year_array as $month_year_no => $month_year_name) {
                $monthly_process_item_count = $this->getMonthlyProcessItemCount($month_year_no);
                array_push($monthly_process_item_count_array, $monthly_process_item_count);
                array_push($month_year_name_array, $month_year_name);
            }
        }

        $max_no = max($monthly_process_item_count_array);
        $max = round(($max_no + 10 / 2) / 10) * 10;
        $monthly_process_item_data_array = array(
            'months_years' => $month_year_name_array,
            'process_item_count_data' => $monthly_process_item_count_array,
            'max' => $max,
        );

        return $monthly_process_item_data_array;
    }

    //number of forms per request bar chart
    function getProcessItemFormTypes()
    {
        $form_types = Form_Type::all()->pluck('type');
        $form_types_id = 1;
        $form_types = json_decode($form_types);
        $form_type_array = array();
        foreach ($form_types as $form_type) {
            $form_type_array[$form_types_id] = $form_type;
            $form_types_id++;
        }
        return $form_type_array;
    }

    function getProcessItemTypeCount($ftid)
    {
        $form_type_count = Process_Item::where('form_type_id', $ftid)->count();
        return $form_type_count;
    }

    function getProcessItemFormTypeData()
    {
        $process_item_type_count_array = array();
        $process_item_types_array = $this->getProcessItemFormTypes();
        $process_item_type_name_array = array();
        $form_types_id = 1;
        if (!empty($process_item_types_array)) {
            foreach ($process_item_types_array as $form_type) {
                $process_item_type_count = $this->getProcessItemTypeCount($form_types_id);
                array_push($process_item_type_count_array, $process_item_type_count);
                array_push($process_item_type_name_array, $form_type);
                $form_types_id++;
            }
        }
        $max_no = max($process_item_type_count_array);
        $max = round(($max_no + 10 / 2) / 10) * 10;
        $process_item_form_type_data_array = array(
            'form_type' => $process_item_type_name_array,
            'process_item_type_count_data' => $process_item_type_count_array,
            'max' => $max,
        );
        return $process_item_form_type_data_array;
    }


    //number of requests per assigned organization pie chart
    function getProcessItemOrganizationNames()
    {
        $activity_organizations = Organization::all()->pluck('title');
        $activity_organizations_id = 1;
        $activity_organizations = json_decode($activity_organizations);
        foreach ($activity_organizations as $activity_organization) {
            $activity_organization_type_array[$activity_organizations_id] = $activity_organization;
            $activity_organizations_id++;
        }
        return $activity_organization_type_array;
    }

    function getProcessItemOrganizationCount($oid)
    {
        $activity_organization_count = Process_Item::where('activity_organization', $oid)->count();
        return $activity_organization_count;
    }

    function getProcessItemOrganizationData()
    {
        $process_item_organization_count_array = array();
        $process_organization_array = $this->getProcessItemOrganizationNames();
        $process_organization_name_array = array();
        $organization_id = 1;
        if (!empty($process_organization_array)) {
            foreach ($process_organization_array as $activity_organization) {
                $process_item_organization_count = $this->getProcessItemOrganizationCount($organization_id);
                array_push($process_item_organization_count_array, $process_item_organization_count);
                array_push($process_organization_name_array, $activity_organization);
                $organization_id++;
            }
        }
        $max_no = max($process_item_organization_count_array);
        $max = round(($max_no + 10 / 2) / 10) * 10;
        $process_item_activity_organization_data_array = array(
            'organization_name' => $process_organization_name_array,
            'process_item_activity_organization_count_data' => $process_item_organization_count_array,
            'max' => $max,
        );
        return $process_item_activity_organization_data_array;
    }





    public function treeRemoval()
    {
        return view('reporting::treeRemoval');
    }
    //Tree Removals per month Line Chart
    public function getAllTreeRemovals()
    {
        $month_year_array = array();
        $tree_removal_dates = Tree_Removal_Request::orderBy('created_at', 'ASC')->pluck('created_at');
        $tree_removal_dates = json_decode($tree_removal_dates);
        if (!empty($tree_removal_dates)) {
            foreach ($tree_removal_dates as $unformatted_date) {
                $date = new \DateTime($unformatted_date);
                $month_no = strval($date->format('m'));
                $year_no = strval($date->format('y'));
                $month_year_no = $month_no . $year_no;
                $month_name = $date->format('M');
                $year_name = $date->format('Y');
                $month_year_name = $month_name . (" ") . $year_name;
                $month_year_array[$month_year_no] = $month_year_name; //obtains as array of month and years
            }
        }
        return $month_year_array;
    }
    function getMonthlyTreeRemovalCount($month)
    {
        $month_val = intval(substr($month, 0, 2));
        $year_val = intval("20" . substr($month, 2));
        $monthly_tree_removal_count = Tree_Removal_Request::whereYear('created_at', $year_val)->whereMonth('created_at', $month_val)->pluck('no_of_trees')->sum();
        return $monthly_tree_removal_count;
    }

    function getMonthlyTreeRemovalData()
    {

        $monthly_tree_removal_count_array = array();
        $month_year_array = $this->getAllTreeRemovals();
        $month_year_name_array = array();
        if (!empty($month_year_array)) {
            foreach ($month_year_array as $month_year_no => $month_year_name) {
                $monthly_tree_removal_count = $this->getMonthlyTreeRemovalCount($month_year_no);
                array_push($monthly_tree_removal_count_array, $monthly_tree_removal_count);
                array_push($month_year_name_array, $month_year_name);
            }
        }

        $max_no = max($monthly_tree_removal_count_array);
        $max = round(($max_no + 10 / 2) / 10) * 10;
        $monthly_tree_removal_data_array = array(
            'months_years' => $month_year_name_array,
            'tree_removal_count_data' => $monthly_tree_removal_count_array,
            'max' => $max,
        );

        return $monthly_tree_removal_data_array;
    }



    //number of requests per Province pie chart
    function getTreeRemovalProvinceNames()
    {
        $provinces = Province::all()->pluck('province');
        $provinces_id = 1;
        $provinces = json_decode($provinces);
        foreach ($provinces as $province) {
            $province_name_array[$provinces_id] = $province;
            $provinces_id++;
        }
        return $province_name_array;
    }

    function getTreeRemovalProvinceCount($pid)
    {
        $province_count = Tree_Removal_Request::where('province_id', $pid)->pluck('no_of_trees')->sum();
        return $province_count;
    }

    function getTreeRemovalProvinceData()
    {
        $tree_removal_province_count_array = array();
        $tree_removal_province_array = $this->getTreeRemovalProvinceNames();
        $tree_removal_province_name_array = array();

        $province_id = 1;
        if (!empty($tree_removal_province_array)) {
            foreach ($tree_removal_province_array as $province) {
                $tree_removal_province_count = $this->getTreeRemovalProvinceCount($province_id);
                array_push($tree_removal_province_count_array, $tree_removal_province_count);
                array_push($tree_removal_province_name_array, $province);
                $province_id++;
            }
        }
        $tree_removal_province_data_array = array(
            'province' => $tree_removal_province_name_array,

            'tree_removal_province_count_data' => $tree_removal_province_count_array
        );
        return $tree_removal_province_data_array;
    }

    //number of requests per District pie chart
    function getTreeRemovalDistrictNames()
    {
        $districts = District::all()->pluck('district');
        $districts_id = 1;
        $districts = json_decode($districts);
        foreach ($districts as $district) {
            $district_name_array[$districts_id] = $district;
            $districts_id++;
        }
        return $district_name_array;
    }

    function getTreeRemovalDistrictCount($pid)
    {
        $district_count = Tree_Removal_Request::where('district_id', $pid)->pluck('no_of_trees')->sum();
        return $district_count;
    }

    function rand_color()
    {
        return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }
    function getTreeRemovalDistrictData()
    {
        $tree_removal_district_count_array = array();
        $tree_removal_district_array = $this->getTreeRemovalDistrictNames();
        $tree_removal_district_name_array = array();

        $tree_removal_district_colors = array();

        $district_id = 1;
        if (!empty($tree_removal_district_array)) {
            foreach ($tree_removal_district_array as $district) {
                $tree_removal_district_count = $this->getTreeRemovalDistrictCount($district_id);
                array_push($tree_removal_district_count_array, $tree_removal_district_count);
                array_push($tree_removal_district_name_array, $district);

                $district_color = $this->rand_color();
                array_push($tree_removal_district_colors, $district_color);

                $district_id++;
            }
        }
        $tree_removal_district_data_array = array(
            'district' => $tree_removal_district_name_array,

            'tree_removal_district_count_data' => $tree_removal_district_count_array,
            'district_color' => $tree_removal_district_colors
        );
        return $tree_removal_district_data_array;
    }



    public function restoration()
    {
        return view('reporting::restoration');
    }
    //Restorations per month Line Chart
    public function getAllRestorations()
    {
        $month_year_array = array();
        $restoration_dates = Environment_Restoration::orderBy('created_at', 'ASC')->pluck('created_at');
        $restoration_dates = json_decode($restoration_dates);
        if (!empty($restoration_dates)) {
            foreach ($restoration_dates as $unformatted_date) {
                $date = new \DateTime($unformatted_date);
                $month_no = strval($date->format('m'));
                $year_no = strval($date->format('y'));
                $month_year_no = $month_no . $year_no;
                $month_name = $date->format('M');
                $year_name = $date->format('Y');
                $month_year_name = $month_name . (" ") . $year_name;
                $month_year_array[$month_year_no] = $month_year_name; //obtains as array of month and years
            }
        }
        return $month_year_array;
    }
    function getMonthlyRestorationCount($month)
    {
        $month_val = intval(substr($month, 0, 2));
        $year_val = intval("20" . substr($month, 2));
        $monthly_restoration_count = 0;
        $monthly_restoration_count_ids = Environment_Restoration::whereYear('created_at', $year_val)->whereMonth('created_at', $month_val)->pluck('id');
        foreach ($monthly_restoration_count_ids as $monthly_restoration_count_id) {
            $monthly_restoration_count += Environment_Restoration_Species::where('environment_restoration_id', $monthly_restoration_count_id)->pluck('quantity')->sum();
        }
        return $monthly_restoration_count;
    }

    function getMonthlyRestorationData()
    {

        $monthly_restoration_count_array = array();
        $month_year_array = $this->getAllRestorations();
        $month_year_name_array = array();
        if (!empty($month_year_array)) {
            foreach ($month_year_array as $month_year_no => $month_year_name) {
                $monthly_restoration_count = $this->getMonthlyRestorationCount($month_year_no);
                array_push($monthly_restoration_count_array, $monthly_restoration_count);
                array_push($month_year_name_array, $month_year_name);
            }
        }

        $max_no = max($monthly_restoration_count_array);
        $max = round(($max_no + 10 / 2) / 10) * 10;
        $monthly_restoration_data_array = array(
            'resto_months_years' => $month_year_name_array,
            'restoration_count_data' => $monthly_restoration_count_array,
            'max' => $max,
        );
        return $monthly_restoration_data_array;
    }

    //number of requests per restoration activity type bar chart
    function getRestorationActivityTypes()
    {
        $activity_types = Environment_Restoration_Activity::all()->pluck('title');
        $activity_types_id = 1;
        $activity_types = json_decode($activity_types);
        $activity_type_array = array();
        foreach ($activity_types as $activity_type) {
            $activity_type_array[$activity_types_id] = $activity_type;
            $activity_types_id++;
        }
        return $activity_type_array;
    }

    function getRestorationActivityTypeCount($erid)
    {
        $activity_type_count = Environment_Restoration::where('environment_restoration_activity_id', $erid)->count();
        return $activity_type_count;
    }

    function getRestorationActivityTypeData()
    {
        $restoration_type_count_array = array();
        $restoration_types_array = $this->getRestorationActivityTypes();
        $restoration_type_name_array = array();
        $activity_types_id = 1;
        if (!empty($restoration_types_array)) {
            foreach ($restoration_types_array as $activity_type) {
                $restoration_type_count = $this->getRestorationActivityTypeCount($activity_types_id);
                array_push($restoration_type_count_array, $restoration_type_count);
                array_push($restoration_type_name_array, $activity_type);
                $activity_types_id++;
            }
        }
        $max_no = max($restoration_type_count_array);
        $max = round(($max_no + 10 / 2) / 10) * 10;
        $restoration_activity_type_data_array = array(
            'activity_type' => $restoration_type_name_array,
            'restoration_type_count_data' => $restoration_type_count_array,
            'max' => $max,
        );
        return $restoration_activity_type_data_array;
    }

    //number of Restoration Requests per assigned Ecosystem pie chart
    function getRestorationEcosystemNames()
    {
        $ecosystems = Env_type::all()->pluck('type');
        $ecosystems_id = 1;
        $ecosystems = json_decode($ecosystems);
        foreach ($ecosystems as $ecosystem) {
            $ecosystem_type_array[$ecosystems_id] = $ecosystem;
            $ecosystems_id++;
        }
        return $ecosystem_type_array;
    }

    function getRestorationEcosystemCount($eid)
    {
        $ecosystem_count = Environment_Restoration::where('eco_system_id', $eid)->count();
        return $ecosystem_count;
    }

    function getRestorationEcosystemData()
    {
        $ecosystem_count_array = array();
        $ecosystem_array = $this->getRestorationEcosystemNames();
        $ecosystem_name_array = array();
        $ecosystem_id = 1;
        if (!empty($ecosystem_array)) {
            foreach ($ecosystem_array as $ecosystem) {
                $ecosystem_count = $this->getRestorationEcosystemCount($ecosystem_id);
                array_push($ecosystem_count_array, $ecosystem_count);
                array_push($ecosystem_name_array, $ecosystem);
                $ecosystem_id++;
            }
        }
        $max_no = max($ecosystem_count_array);
        $max = round(($max_no + 10 / 2) / 10) * 10;
        $ecosystem_data_array = array(
            'ecosystem_name' => $ecosystem_name_array,
            'ecosystem_count_data' => $ecosystem_count_array,
            'max' => $max,
        );
        return $ecosystem_data_array;
    }





    //Development Project Charts
    public function devProject()
    {
        return view('reporting::developmentProject');
    }
    //Development Projects per month Line Chart
    public function getAllDevelopmentProjects()
    {
        $month_year_array = array();
        $development_project_dates = Development_Project::orderBy('created_at', 'ASC')->pluck('created_at');
        $development_project_dates = json_decode($development_project_dates);
        if (!empty($development_project_dates)) {
            foreach ($development_project_dates as $unformatted_date) {
                $date = new \DateTime($unformatted_date);
                $month_no = strval($date->format('m'));
                $year_no = strval($date->format('y'));
                $month_year_no = $month_no . $year_no;
                $month_name = $date->format('M');
                $year_name = $date->format('Y');
                $month_year_name = $month_name . (" ") . $year_name;
                $month_year_array[$month_year_no] = $month_year_name; //obtains as array of month and years
            }
        }
        return $month_year_array;
    }
    function getMonthlyDevelopmentProjectCount($month)
    {
        $month_val = intval(substr($month, 0, 2));
        $year_val = intval("20" . substr($month, 2));
        $monthly_development_project_count = Development_Project::whereYear('created_at', $year_val)->whereMonth('created_at', $month_val)->count();
        return $monthly_development_project_count;
    }

    function getMonthlyDevelopmentProjectData()
    {

        $monthly_development_project_count_array = array();
        $month_year_array = $this->getAllDevelopmentProjects();
        $month_year_name_array = array();
        if (!empty($month_year_array)) {
            foreach ($month_year_array as $month_year_no => $month_year_name) {
                $monthly_development_project_count = $this->getMonthlyDevelopmentProjectCount($month_year_no);
                array_push($monthly_development_project_count_array, $monthly_development_project_count);
                array_push($month_year_name_array, $month_year_name);
            }
        }

        $max_no = max($monthly_development_project_count_array);
        $max = round(($max_no + 10 / 2) / 10) * 10;
        $monthly_development_project_data_array = array(
            'months_years' => $month_year_name_array,
            'development_project_count_data' => $monthly_development_project_count_array,
            'max' => $max,
        );

        return $monthly_development_project_data_array;
    }


    //number of Development Project Requests per assigned Organization pie chart
    function getDevelopmentProjectOrganizationNames()
    {
        $organizations = Organization::all()->pluck('title');
        $organizations_id = 1;
        $organizations = json_decode($organizations);
        foreach ($organizations as $organization) {
            $organization_type_array[$organizations_id] = $organization;
            $organizations_id++;
        }
        return $organization_type_array;
    }

    function getDevelopmentProjectOrganizationCount($oid)
    {
        $developmentprojs=Development_Project::all()->pluck('governing_organizations');
        $organization_count=0;
        foreach($developmentprojs as $developmentproj){
            foreach($developmentproj as $arrayval){
                if($arrayval==$oid)
                $organization_count++;
            }
        }
        return $organization_count;
    }

    function getDevelopmentProjectOrganizationData()
    {
        $organization_count_array = array();
        $organization_array = $this->getDevelopmentProjectOrganizationNames();
        $organization_name_array = array();
        $organization_id = 1;
        if (!empty($organization_array)) {
            foreach ($organization_array as $organization) {
                $organization_count = $this->getDevelopmentProjectOrganizationCount($organization_id);
                array_push($organization_count_array, $organization_count);
                array_push($organization_name_array, $organization);
                $organization_id++;
            }
        }
        $max_no = max($organization_count_array);
        $max = round(($max_no + 10 / 2) / 10) * 10;
        $organization_data_array = array(
            'organization_name' => $organization_name_array,
            'organization_count_data' => $organization_count_array,
            'max' => $max,
        );
        return $organization_data_array;
    }




    //CRIME CHARTS
    public function crimeReport()
    {
        return view('reporting::crimeReport');
    }
    //Crime Reports per month Line Chart
    public function getAllCrimeReports()
    {
        $month_year_array = array();
        $crime_report_dates = Crime_report::orderBy('created_at', 'ASC')->pluck('created_at');
        $crime_report_dates = json_decode($crime_report_dates);
        if (!empty($crime_report_dates)) {
            foreach ($crime_report_dates as $unformatted_date) {
                $date = new \DateTime($unformatted_date);
                $month_no = strval($date->format('m'));
                $year_no = strval($date->format('y'));
                $month_year_no = $month_no . $year_no;
                $month_name = $date->format('M');
                $year_name = $date->format('Y');
                $month_year_name = $month_name . (" ") . $year_name;
                $month_year_array[$month_year_no] = $month_year_name; //obtains as array of month and years
            }
        }
        return $month_year_array;
    }
    function getMonthlyCrimeReportCount($month)
    {
        $month_val = intval(substr($month, 0, 2));
        $year_val = intval("20" . substr($month, 2));
        $monthly_crime_report_count = Crime_report::whereYear('created_at', $year_val)->whereMonth('created_at', $month_val)->count();
        return $monthly_crime_report_count;
    }

    function getMonthlyCrimeReportData()
    {

        $monthly_crime_report_count_array = array();
        $month_year_array = $this->getAllCrimeReports();
        $month_year_name_array = array();
        if (!empty($month_year_array)) {
            foreach ($month_year_array as $month_year_no => $month_year_name) {
                $monthly_crime_report_count = $this->getMonthlyCrimeReportCount($month_year_no);
                array_push($monthly_crime_report_count_array, $monthly_crime_report_count);
                array_push($month_year_name_array, $month_year_name);
            }
        }

        $max_no = max($monthly_crime_report_count_array);
        $max = round(($max_no + 10 / 2) / 10) * 10;
        $monthly_crime_report_data_array = array(
            'months_years' => $month_year_name_array,
            'crime_report_count_data' => $monthly_crime_report_count_array,
            'max' => $max,
        );

        return $monthly_crime_report_data_array;
    }

    //number of requests per crime type bar chart
    function getCrimeReportTypes()
    {
        $crime_types = Crime_type::all()->pluck('type');
        $crime_types_id = 1;
        $crime_types = json_decode($crime_types);
        $crime_type_array = array();
        foreach ($crime_types as $crime_type) {
            $crime_type_array[$crime_types_id] = $crime_type;
            $crime_types_id++;
        }
        return $crime_type_array;
    }

    function getCrimeReportTypeCount($cid)
    {
        $crime_type_count = crime_report::where('crime_type_id', $cid)->count();
        return $crime_type_count;
    }

    function getCrimeReportTypeData()
    {
        $crime_report_type_count_array = array();
        $crime_report_types_array = $this->getCrimeReportTypes();
        $crime_report_type_name_array = array();
        $crime_types_id = 1;
        if (!empty($crime_report_types_array)) {
            foreach ($crime_report_types_array as $crime_type) {
                $crime_report_type_count = $this->getCrimeReportTypeCount($crime_types_id);
                array_push($crime_report_type_count_array, $crime_report_type_count);
                array_push($crime_report_type_name_array, $crime_type);
                $crime_types_id++;
            }
        }
        $max_no = max($crime_report_type_count_array);
        $max = round(($max_no + 10 / 2) / 10) * 10;
        $crime_report_crime_type_data_array = array(
            'crime_type' => $crime_report_type_name_array,
            'crime_report_type_count_data' => $crime_report_type_count_array,
            'max' => $max,
        );
        return $crime_report_crime_type_data_array;
    }


    //Crime Action Taken Chart
    function getCrimeReportActionTakenCount($cid)
    {
        $crime_action_taken_count = crime_report::where('action_taken', $cid)->count();
        return $crime_action_taken_count;
    }

    function getCrimeReportActionTakenData()
    {
        $crime_report_action_taken_count_array = array();
        //$crime_report_actions_taken_array = ['0','1'];
        $crime_report_action_taken_name_array = ["Crime Report Pending action", "Request Resolved"];
        // if (!empty($crime_report_action_takens_array)) {
        //     foreach ($crime_report_actions_taken_array as $crime_action_taken) {
        //         $crime_report_action_taken_count = $this->getCrimeReportActionTakenCount($crime_action_taken_id);
        //         array_push($crime_report_action_taken_count_array, $crime_report_action_taken_count);
        //         $crime_action_taken_id++;
        //     }
        // }
        for ($count = 0; $count < 2; $count++) {
            $crime_report_action_taken_count = $this->getCrimeReportActionTakenCount($count);
            array_push($crime_report_action_taken_count_array, $crime_report_action_taken_count);
        }
        $max_no = max($crime_report_action_taken_count_array);
        $max = round(($max_no + 10 / 2) / 10) * 10;
        $crime_report_crime_action_taken_data_array = array(
            'crime_action_taken' => $crime_report_action_taken_name_array,
            'crime_report_action_taken_count_data' => $crime_report_action_taken_count_array,
            'max' => $max,
        );
        return $crime_report_crime_action_taken_data_array;
    }
}
