<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Bid;

class BidderController extends Controller
{
     public function index(Request $request)
    {
        $projects = Project::query();

        $projects->search($request->search);

        $projects = $projects->latest()
            ->paginate(5)
            ->withQueryString();
        
          
        $bids = Bid::query();
        $bids->search($request->search);
        $bids = $bids->latest()
        ->paginate(10)
        ->withQueryString();

        return view('bidder.index', compact('projects', 'bids'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => ['required','exists:projects,id'],
            'company_name' => ['required','string','max:255'],
            'proprietor' => ['required','string','max:255'],
            'bid_amount' => ['required','numeric','min:1'],
            'address' => ['required','string','max:255'],
        ],
        [
            'project_id.required'   => 'Please select a project.',
            'project_id.exists'     => 'The selected project is invalid.',

            'company_name.required' => 'Please enter the company name.',
            'company_name.string'   => 'The company name must be valid text.',
            'company_name.max'      => 'The company name may not exceed 255 characters.',

            'proprietor.required'   => 'Please enter the proprietor name.',
            'proprietor.string'     => 'The proprietor name must be valid text.',
            'proprietor.max'        => 'The proprietor name may not exceed 255 characters.',

            'bid_amount.required'   => 'Please enter a bid amount.',
            'bid_amount.numeric'    => 'The bid amount must be a valid number.',
            'bid_amount.min'        => 'The bid amount must be greater than zero.',

            'address.required'      => 'Please enter an address.',
            'address.string'        => 'The address must be valid text.',
            'address.max'           => 'The address may not exceed 255 characters.',
        ]);

        Bid::create([
            'project_id' => $request->project_id,
            'company_name' => $request->company_name,
            'proprietor' => $request->proprietor,
            'bid_amount' => $request->bid_amount,
            'address' => $request->address,
        ]);

        return back()->with('success','Bid created successfully.');
    }


    public function update(Request $request, Bid $bid)
    {
        $request->validate([
            'edit-company_name' => ['required','string','max:255'],
            'edit-proprietor' => ['required','string','max:255'],
            'edit-bid_amount' => ['required','numeric','min:1'],
            'edit-address' => ['required','string','max:255'],
        ],
        [
            'edit-company_name.required' => 'Company name is required.',
            'edit-company_name.string'   => 'Company name must be valid text.',
            'edit-company_name.max'      => 'Company name may not exceed 255 characters.',

            'edit-proprietor.required'   => 'Proprietor name is required.',
            'edit-proprietor.string'     => 'Proprietor name must be valid text.',
            'edit-proprietor.max'        => 'Proprietor name may not exceed 255 characters.',

            'edit-bid_amount.required'   => 'Bid amount is required.',
            'edit-bid_amount.numeric'    => 'Bid amount must be a valid number.',
            'edit-bid_amount.min'        => 'Bid amount must be at least 1.',

            'edit-address.required'      => 'Address is required.',
            'edit-address.string'        => 'Address must be valid text.',
            'edit-address.max'           => 'Address may not exceed 255 characters.',

        ]);

        if (
            $bid->company_name === $request->input('edit-company_name') &&
            $bid->proprietor === $request->input('edit-proprietor') &&
            $bid->bid_amount == $request->input('edit-bid_amount') && $bid->address === $request->input('edit-address')
        ) {
            return redirect()->back();
        }

        $bid->update([
            'company_name' => $request->input('edit-company_name'),
            'proprietor' => $request->input('edit-proprietor'),
            'bid_amount'        => $request->input('edit-bid_amount'),
            'address' => $request->input('edit-address'),
        ]);

        return redirect()->back()->with('clear_storage', true)->with('success', 'Project updated successfully.');
    }

    public function destroy(Bid $bid)
    {
        $bid->delete();
        return back()->with('success', 'Bid deleted successfully.');
    }
}
