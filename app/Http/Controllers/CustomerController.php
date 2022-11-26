<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Carbon\Carbon;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    /**
     * Display a listing of the resource.
     * @method GET
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $customers = Customer::all();

            if (!$customers) {
                return response()->json([
                    'status' => true,
                    'message' => 'Customer Does not Exist',
                    'data' => []
                ], 200);
            }

            $customerConatiner = [];
            $serialNumber = 1;
            foreach ($customers as $key => $customer) {
                array_push($customerConatiner, [
                    'customerId' => $customer->id,
                    'serialNumber' => $serialNumber,
                    'customerName' => $customer->name,
                    'customerEmail' => $customer->email,
                    'customerDOB' => $customer->date_of_birth,
                    'customerContactNumber' => $customer->contact_number,
                    'customerJoiningDate' => $customer->joining_date,
                    'customerAddress' => $customer->residental_address
                ]);
                $serialNumber++;
            }

            return response()->json([
                'status' => true,
                'message' => 'Everthing is Good',
                'totalCustomer' => count($customerConatiner),
                'data' => $customerConatiner
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return "Hii, i am in create";
    }

    /**
     * Store a newly created resource in storage.
     * @method POST
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        try {

            $validated = $request->validate([
                'customerName' => 'required',
                'customerEmail' => 'required|email:rfc,dns',
                'customerContactNumber' => 'required',
                'customerJoiningDate' => 'required',
                'customerAddress' => 'required',
            ]);

            $createCustomer = new Customer();
            $createCustomer->name = $request->customerName;
            $createCustomer->email = $request->customerEmail;
            if (isset($request->customerDOB)) {
                $createCustomer->date_of_birth = $request->customerDOB;
            }
            $createCustomer->contact_number = $request->customerContactNumber;
            $createCustomer->joining_date = $request->customerJoiningDate;
            $createCustomer->residental_address = $request->customerAddress;

            if ($createCustomer->save()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Customer registered successfully!'
                ], 200);
            } else {
                throw new \Exception('Error Occured');
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     * @method GET with {id}
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //

        try {
            if ($customer) {
                return response()->json([
                    'status' => false,
                    'message' => 'Something went wrong',
                    'data' => [
                        'customerId' => $customer->id,
                        'customerName' => $customer->name,
                        'customerEmail' => $customer->email,
                        'customerDOB' => $customer->date_of_birth,
                        'customerContactNumber' => $customer->contact_number,
                        'customerJoiningDate' => $customer->joining_date,
                        'customerAddress' => $customer->residental_address
                    ]
                ], 200);
            } else {
                throw new \Exception('Customer Not Found');
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
        return "Hii, i am in Edit";
    }

    /**
     * Update the specified resource in storage.
     * @method PUT
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        //

        try {

            if (isset($request->customerName)) {
                $customer->name = $request->customerName;
            }

            if (isset($request->customerEmail)) {
                $customer->name = $request->customerEmail;
            }

            if (isset($request->customerContactNumber)) {
                $customer->contact_number = $request->customerContactNumber;
            }

            if (isset($request->customerDOB)) {
                $customer->date_of_birth = $request->customerDOB;
            }

            if (isset($request->customerJoiningDate)) {
                $customer->joining_date = $request->customerJoiningDate;
            }

            if (isset($request->customerAddress)) {
                $customer->residental_address = $request->customerAddress;
            }

            if ($customer->save()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Customer details updated successfully!'
                ], 200);
            } else {
                throw new \Exception('Error Occured');
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'reason' => 'Something went wrong',
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @method DELETE
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
        try {
            if ($customer) {
                if ($customer->delete()) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Customer details deleted successfully!',
                    ], 200);
                } else {
                    throw new \Exception('Server Error');
                }
            } else {
                throw new \Exception('User not found');
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }


    public function notify()
    {

        try {

            $notifies = Customer::select('*')
                ->where('joining_date', '>', now()->subDays(90)->endOfDay())
                ->get();

            if (!$notifies) {
                return response()->json([
                    'status' => true,
                    'message' => 'there are no customer who crossed 90 days.',
                    'data' => []
                ], 200);
            }

            $notifyContainer = [];

            foreach ($notifies as $key => $notify) {
                array_push($notifyContainer, [
                    'customerId' => $notify->id,
                    'customerName' => $notify->name,
                    'customerEmail' => $notify->email,
                    'customerDOB' => $notify->date_of_birth,
                    'customerContactNumber' => $notify->contact_number,
                    'customerJoiningDate' => $notify->joining_date,
                    'customerAddress' => $notify->residental_address
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Everthing is Good',
                'totalAvailableCustomer' => count($notifyContainer),
                'data' => $notifyContainer
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'mess' => $e->getMessage()
            ], 200);
        }
    }
}
