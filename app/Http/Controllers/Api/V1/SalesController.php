<?php

namespace App\Http\Controllers\Api\V1;

use App\Sales;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $records = Sales
            ::join('products', 'sales.product_id', '=', 'products.id')
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->select('sales.*', 'products.name AS product_name', 'customers.name AS customer_name');

        if (isset($request->customer_id) && !empty($request->customer_id)) {
            $records->where('sales.customer_id', $request->customer_id);
        }

        if (isset($request->period_start) && !empty($request->period_start)) {
            $start = date('Y-m-d', strtotime($request->period_start));
            $records->where('sales.date', '>=', $start);
        }

        if (isset($request->period_end) && !empty($request->period_end)) {
            $end = date('Y-m-d', strtotime($request->period_end));
            $records->where('sales.date', '<=', $end);
        }

        return $records->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $data['date']   = date('Y-m-d', strtotime($data['date']));

        $sale = Sales::create($data);
        return $sale;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Sales::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        $data['date'] = date('Y-m-d', strtotime($data['date']));

        $sale = Sales::findOrFail($id);
        $sale->update($data);

        return $sale;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sale = Sales::findOrFail($id);
        $sale->delete();
        return '';
    }

    public function report(Request $request) {
        $records = Sales
            ::groupBy('status')
            ->select(
                'sales.status',
                \DB::raw('SUM(sales.quantity) as total_sales'),
                \DB::raw('SUM(sales.amount) as total_amount')
            );

        if (isset($request->customer_id) && !empty($request->customer_id)) {
            $records->where('sales.customer_id', $request->customer_id);
        }

        if (isset($request->period_start) && !empty($request->period_start)) {
            $start = date('Y-m-d', strtotime($request->period_start));
            $records->where('sales.date', '>=', $start);
        }

        if (isset($request->period_end) && !empty($request->period_end)) {
            $end = date('Y-m-d', strtotime($request->period_end));
            $records->where('sales.date', '<=', $end);
        }

        return $records->get();
    }
}
