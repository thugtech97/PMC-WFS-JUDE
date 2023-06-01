<?php

namespace App\Http\Controllers\Approver;

use App\Http\Controllers\Controller;
use App\EmailRecipient;
use Facades\App\Helpers\ListingHelper;
use App\Helpers\Webfocus\Setting;
use App\Http\Requests\ContactUsRequest;
use App\Mail\InquiryAdminMail;
use App\Mail\InquiryMail;
use App\Page;
use App\SupplierModels\SupplierDetails;
use App\Helpers\FileHelper;
use App\SupplierModels\Supplier;
use App\SupplierModels\SupplierApplicantProducts;
use App\SupplierModels\ApproverSteps;
use App\SupplierModels\Approvals;
use App\SupplierModels\ApproverTemplates;
use Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Message;
use App\User;
use Carbon\Carbon;
use App\Mail\MessageSentToApproverNotification;
use App\Mail\MessageSentToSupplierNotification;
use App\Mail\FinalApprovalNotification;
use App\SupplierModels\SupplierApplicants;

class ApproverController extends Controller
{

	private $searchFields = ['id'];

	public function login()
    {

        if(Auth::user()) return redirect()->route(Auth::user()->user_role_redirect());
        
        $page = new Page();
        $page->name = 'Login';
        return view('theme.'.env('FRONTEND_TEMPLATE').'.'.env('SP_TEMPLATE').'.pages.approver.login',compact('page'));

    }


    public function dashboard () 
    {
    	$page = new Page();
        $page->name = 'Dashboard';

        $supplier_collection = ApproverSteps::where('status','!=','Reject')
            ->where('approver_id',Auth::id())
            ->where('is_current', 1);

        $applicants = ListingHelper::simple_search_using_collection($supplier_collection, $this->searchFields);

        $filter = ListingHelper::get_filter($this->searchFields);
        
        $searchType = 'simple_search';

        return view('theme.'.env('FRONTEND_TEMPLATE').'.'.env('SP_TEMPLATE').'.pages.approver.dashboard',compact('page', 'applicants', 'filter', 'searchType'));
    }
    

    public function sendMessage (Request $request) 
    {

        Message::create($request->all());

        $receiver = User::where('email', $request->to)->first();
        $sender = User::where('email', $request->from)->first();

        if($receiver->role_id == env('APPROVER_ID')) {

            // send to approver
            Mail::to($receiver->email)->send(new MessageSentToApproverNotification($receiver,Setting::info(), $sender));


        } else {

            // send to supplier
            Mail::to($receiver->email)->send(new MessageSentToSupplierNotification($receiver,Setting::info()));

        }

        return response()->json(['status' => 'success', 'message' => 'Message Successfully Send']);

    }
    

    public function messages () 
    {

        $page = new Page();
        $page->name = 'Messages';

        $messages = Message::where('to', Auth::user()->email)
            ->orderBy('is_read', 'ASC')
            ->orderBy('created_at', 'DESC')
            ->get()
            ->groupBy('from');

        return view('theme.'.env('FRONTEND_TEMPLATE').'.'.env('SP_TEMPLATE').'.pages.approver.messages.index', compact('messages','page'));

    }


    public function getConvo ($email) 
    {
        $convo = Message::where(function($query) use  ($email){
                $query->where('to', Auth::user()->email)
                    ->where('from', $email);
            })->orWhere(function($query) use ($email) {
                $query->where('to', $email)
                    ->where('from', Auth::user()->email);
            })
            ->get();

        foreach($convo as $c){
            if($c->to == Auth::user()->email) {
                $c->is_read = 1;
                $c->save();
            }
        }

        return view('theme.'.env('FRONTEND_TEMPLATE').'.'.env('SP_TEMPLATE').'.pages.approver.messages.messages', compact('convo'));
    }
        

    public function upcomingApproval() {

        $page = new Page();
        $page->name = 'Upcoming Approval';

        $upcomingApproval = collect();

        // get all unique applications
        //$supplier_applications = ListingHelper::get_unique_item_by_column(ApproverSteps::class, 'approval_id');
        $curr_logged_user = ApproverTemplates::where('approver_id', Auth::id())->first();

        $applications = ApproverSteps::where('is_current', 1)
            ->where('status', '!=','Reject')
            ->where('approver_id', '!=', Auth::id())
            ->where('sequence', '<', $curr_logged_user->sequence_no);

        $applicants = ListingHelper::simple_search_using_collection($applications, $this->searchFields);

        $filter = ListingHelper::get_filter($this->searchFields);
        
        $searchType = 'simple_search';

        return view('theme.'.env('FRONTEND_TEMPLATE').'.'.env('SP_TEMPLATE').'.pages.approver.upcoming-approval',compact('page', 'applicants', 'filter', 'searchType'));

    }


    public function accreditedSuppliers() {

        $page = new Page();
        $page->name = 'Active Suppliers';

        $supplier_collection = SupplierDetails::where('status', 'Active');

        $applicants = ListingHelper::simple_search_using_collection($supplier_collection, $this->searchFields);

        $filter = ListingHelper::get_filter($this->searchFields);
        
        $searchType = 'simple_search';

        return view('theme.'.env('FRONTEND_TEMPLATE').'.'.env('SP_TEMPLATE').'.pages.approver.accredited-suppliers',compact('page', 'applicants', 'filter', 'searchType'));
    }


    public function finalApproval() {

        $page = new Page();
        $page->name = 'For Approval';

        $supplier_collection = SupplierDetails::where('status', 'Waiting For Vendor Code');

        $applicants = ListingHelper::simple_search_using_collection($supplier_collection, $this->searchFields);

        $filter = ListingHelper::get_filter($this->searchFields);

        $searchType = 'simple_search';

        return view('theme.'.env('FRONTEND_TEMPLATE').'.'.env('SP_TEMPLATE').'.pages.approver.final-approval',compact('page', 'applicants', 'filter', 'searchType'));

    }

    public function approveFinalApproval(Request $request) {

        $this->validate($request, [
            'code'  => 'size:6|alpha_num'
        ]);

        $applicant = SupplierDetails::find($request->applicant_id);
        $applicant->code = $request->code;
        $applicant->status = 'Active';
        $applicant->save();

        // send vendor code to supplier
        $supp = User::find($applicant->supplier_id);
        Mail::to($supp->email)->send(new FinalApprovalNotification($supp, Setting::info()));

        return back()->with('success', 'Vendor code Successfully added!!');

    }

    public function passwordReset() {
        $page = new Page();
        $page->name = 'Password Reset';
        return view('theme.pmc_sms.supplier-portal.pages.approver.account.reset', compact('page'));
    }

    public function passwordUpdate(Request $request) {
        $personalInfo = $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) {
                if (!\Hash::check($value, auth()->user()->password)) {
                    return $fail(__('The current password is incorrect.'));
                }
            }],
            'password' => 'required|min:8' ,
            'confirm_password' => 'required|same:password',
        ]);

        auth()->user()->update(['password' => bcrypt($personalInfo['password'])]);

        auth()->logout();

        return redirect()->route('approver.login')->with('success', 'Password has been updated');
    }
    


    public function newApplications () 
    {

        $this->searchFields = ['name'];
        $page = new Page();
        $page->name = 'New Applicants';

        $applicants = ListingHelper::simple_search(SupplierApplicants::class, $this->searchFields);

        $filter = ListingHelper::get_filter($this->searchFields);

        $searchType = 'simple_search';

        return view('theme.'.env('FRONTEND_TEMPLATE').'.'.env('SP_TEMPLATE').'.pages.approver.applicants',compact('page', 'applicants', 'filter', 'searchType'));

    }
    
    public function notifyApprover() {

        // get all is_current and pending approval steps

        $pendingSteps = ApproverSteps::where('is_current', '1')
            ->where('status', 'Pending')
            ->get();

        foreach($pendingSteps as $pending) {
            // 2 days || 5 days || 10 days
            if($pending->date_started->diffInDays(Carbon::now()) == 2 ||
                $pending->date_started->diffInDays(Carbon::now()) == 5 || 
                $pending->date_started->diffInDays(Carbon::now()) == 10 ) {
                    dd('aw');
            }

        }

    }


}